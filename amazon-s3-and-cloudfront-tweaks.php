<?php
/*
Plugin Name: WP Offload S3 Tweaks
Plugin URI: http://github.com/deliciousbrains/wp-amazon-s3-and-cloudfront-tweaks
Description: Examples of using WP Offload S3's filters
Author: Delicious Brains
Version: 0.1.1
Author URI: http://deliciousbrains.com
*/
// Copyright (c) 2015 Delicious Brains. All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************

class Amazon_S3_and_CloudFront_Tweaks {
	function __construct() {
		// Uncomment the following lines to initiate an action / filter

		// each setting has a filter, e.g, object-prefix would be the following
		//add_action( 'as3cf_setting_object-prefix', array( $this, 'get_setting' ), 10, 2 );
		//add_action( 'as3cf_allowed_mime_types', array( $this, 'allowed_mime_types' ), 10, 1 );
		//add_action( 'as3cf_pre_update_attachment_metadata', array( $this, 'pre_update_attachment_metadata' ), 10, 3 );
		//add_action( 'as3cf_upload_acl', array( $this, 'upload_acl' ), 10, 3 );
		//add_action( 'as3cf_hidpi_suffix', array( $this, 'hidpi_suffix' ), 10, 1 );
		//add_action( 'as3cf_get_object_version_string', array( $this, 'get_object_version_string' ), 10, 1 );
		//add_action( 'as3cf_wp_get_attachment_url', array( $this, 'wp_get_attachment_url' ), 10, 2 );
		//add_action( 'as3cf_use_ssl', array( $this, 'use_ssl' ), 10, 1 );
		//add_action( 'as3cf_get_attachment_url', array( $this, 'get_attachment_url' ), 10, 4 );
		//add_action( 'as3cf_get_attached_file_copy_back_to_local', array( $this, 'get_attached_file_copy_back_to_local' ), 10, 3 );
		//add_action( 'as3cf_legacy_ms_subsite_prefix', array( $this, 'legacy_ms_subsite_prefix' ), 10, 2 );
	}

	/**
	 * This filter allows your to override specific settings before the are used
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return string
	 */
	function get_setting( $key, $value ) {
		if ( 'object-prefix' == $key ) {
			$value = '/my/custompath/';
		}

		return $value;
	}

	/**
	 * This filter allows your limit specific mime types of files that
	 * can be uploaded to S3. They will still be uploaded to the WordPress media library
	 * but ignored from the S3 upload process
	 *
	 * @param array $types
	 *
	 * @return array
	 */
	function allowed_mime_types( $types ) {
		// disallow PDFs from S3
		unset( $types['pdf'] );

		return $types;
	}

	/**
	 * This filter allows the upload to S3 to be aborted for any reason on a per
	 * attachment basis
	 *
	 * @param bool  $pre_update
	 * @param array $data    attachment metadata
	 * @param int   $post_id attachment ID
	 *
	 * @return mixed
	 */
	function pre_update_attachment_metadata( $pre_update, $data, $post_id ) {

		if ( 55 == $post_id ) {
			$pre_update = true; // abort the upload
		}

		return $pre_update;
	}

	/**
	 * This filter allows your to change the default Access Control List (ACL)
	 * permission for an uploaded file to S3
	 *
	 * @param string $acl defaults to 'public-read'
	 * @param array  $data
	 * @param int    $post_id
	 *
	 * @return string
	 */
	function upload_acl( $acl, $data, $post_id ) {
		// make all uploaded files on S3 private
		$acl = 'private';

		return $acl;
	}

	/**
	 * This filter allows you to change the file suffix used to denote HiDPI files
	 * when using a specific HiDPI plugin
	 *
	 * @param string $suffix defaults to '@2x'
	 *
	 * @return string
	 */
	function hidpi_suffix( $suffix ) {
		$suffix = '-2x';

		return $suffix;
	}

	/**
	 * This filter allows you to change the object version prefix added to files
	 * as they are uploaded to S3
	 *
	 * @param string $object_version
	 *
	 * @return string
	 */
	function get_object_version_string( $object_version ) {
		$object_version .= 'my-string/';

		return $object_version;
	}

	/**
	 * This filter allows you to change the S3 URL for an attachment
	 *
	 * @param string $url
	 * @param int    $post_id
	 *
	 * @return string
	 */
	function wp_get_attachment_url( $url, $post_id ) {

		return $url;
	}

	/**
	 * This filter allows you to control the scheme for S3 URLs
	 *
	 * @param string $use_ssl
	 *
	 * @return string
	 */
	function use_ssl( $use_ssl ) {
		$use_ssl = 'https';

		return $use_ssl;
	}

	/**
	 * This filter is applied to the URL earlier than 'wp_get_attachment_url`
	 *
	 * @param string $url
	 * @param array  $s3object
	 * @param int    $post_id
	 * @param int    $expires
	 *
	 * @return string
	 */
	function get_attachment_url( $url, $s3object, $post_id, $expires ) {

		return $url;
	}

	/**
	 * This filter is applied during the plugins own filtering of 'get_attached_file'
	 * which by default returns the local file path, or if the attachment has been removed
	 * from the server after upload to S3, the URL. Using this filter we can allow the plugin
	 * to copy back the file from S3 to the local server so it can be used for reasons
	 * such as editing the physical file
	 *
	 * This should be used when we know the local file will get removed again
	 * via wp_update_attachment_metadata
	 *
	 * @param bool   $copy_back_to_local default is false
	 * @param string $file               file path of local file
	 * @param int    $attachment_id
	 *
	 * @return bool
	 */
	function get_attached_file_copy_back_to_local( $copy_back_to_local, $file, $attachment_id ) {
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			return $copy_back_to_local;
		}

		if ( isset( $_POST['action'] ) && 'some-plugin-action' == $_POST['action'] ) {
			$copy_back_to_local = true;
		}

		return $copy_back_to_local;
	}

	/**
	 * This filter allows you to change the Multisite subsite prefix in the file and URL
	 *
	 * @param string $legacy_ms_prefix defaults to '<sitename>/files/'
	 * @param object $details          MS subsite details object
	 *
	 * @return string
	 */
	function legacy_ms_subsite_prefix( $legacy_ms_prefix, $details ) {
		$legacy_ms_prefix = 'sites/' . $details->blog_id . '/';

		return $legacy_ms_prefix;
	}

}

new Amazon_S3_and_CloudFront_Tweaks();
