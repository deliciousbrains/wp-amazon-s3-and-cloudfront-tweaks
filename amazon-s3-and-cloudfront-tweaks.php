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

		// Each setting has a filter, e.g, object-prefix would be the following
		//add_action( 'as3cf_setting_object-prefix', array( $this, 'get_setting' ), 10, 2 );
		//add_filter( 'as3cf_allowed_mime_types', array( $this, 'allowed_mime_types' ), 10, 1 );
		//add_filter( 'as3cf_pre_update_attachment_metadata', array( $this, 'pre_update_attachment_metadata' ), 10, 4 );
		//add_filter( 'as3cf_upload_acl', array( $this, 'upload_acl' ), 10, 3 );
		//add_filter( 'as3cf_upload_acl_sizes', array( $this, 'upload_acl_sizes' ), 10, 4 );
		//add_filter( 'as3cf_object_meta', array( $this, 'object_meta' ), 10, 2 );
		//add_filter( 'as3cf_hidpi_suffix', array( $this, 'hidpi_suffix' ), 10, 1 );
		//add_filter( 'as3cf_get_object_version_string', array( $this, 'get_object_version_string' ), 10, 1 );
		//add_filter( 'as3cf_wp_get_attachment_url', array( $this, 'wp_get_attachment_url' ), 10, 2 );
		//add_filter( 'as3cf_use_ssl', array( $this, 'use_ssl' ), 10, 1 );
		//add_filter( 'as3cf_get_attachment_url', array( $this, 'get_attachment_url' ), 10, 4 );
		//add_filter( 'as3cf_get_attached_file_copy_back_to_local', array( $this, 'get_attached_file_copy_back_to_local' ), 10, 3 );
		//add_filter( 'as3cf_legacy_ms_subsite_prefix', array( $this, 'legacy_ms_subsite_prefix' ), 10, 2 );
		//add_filter( 'as3cf_attachment_file_paths', array( $this, 'attachment_file_paths' ), 10, 3 );
		//add_filter( 'as3cf_show_deprecated_domain_setting', array( $this, 'show_deprecated_domain_setting' ) );
		//add_filter( 'as3cf_upload_attachment_local_files_to_remove', array( $this, 'local_files_to_remove' ), 10, 3 );
		//add_filter( 'as3cf_cloudfront_path_parts', array( $this, 'cloudfront_path_parts' ), 10, 2 );
		//add_filter( 'aws_get_client_args', array( $this, 'aws_client_args' ), 10, 1 );
		//add_filter( 'as3cf_expires', array( $this, 'default_expires' ), 10, 1 );

		// Assets Addon https://deliciousbrains.com/wp-offload-s3/doc/assets-addon/
		//add_filter( 'as3cf_assets_locations_in_scope_to_scan', array( $this, 'assets_locations' ) );
		//add_filter( 'as3cf_assets_ignore_file', array( $this, 'assets_ignore_file' ), 10, 3 );
		//add_filter( 'as3cf_minify_exclude_files', array( $this, 'assets_minify_exclude' ) );
		//add_filter( 'as3cf_gzip_mime_types', array( $this, 'assets_gzip_mimes' ), 10, 2 );
		//add_filter( 'as3cf_assets_expires', array( $this, 'assets_default_expires' ), 10, 1 );
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
	 * @param mixed $old_s3object
	 *
	 * @return mixed
	 */
	function pre_update_attachment_metadata( $pre_update, $data, $post_id, $old_s3object ) {
		if ( 55 == $post_id ) {
			$pre_update = true; // abort the upload
		}

		return $pre_update;
	}

	/**
	 * This filter allows your to change the default Access Control List (ACL)
	 * permission for an original file to S3
	 *
	 * @param string $acl defaults to 'public-read'
	 * @param array  $data
	 * @param int    $post_id
	 *
	 * @return string
	 */
	function upload_acl( $acl, $data, $post_id ) {
		// Make original files on S3 private
		return 'private';
	}

	/**
	 * This filter allows your to change the default Access Control List (ACL)
	 * permission for intermediate image sizes to S3
	 *
	 * @param string $acl defaults to 'public-read'
	 * @param string $size
	 * @param int    $post_id
	 * @param array  $data
	 *
	 * @return string
	 */
	function upload_acl_sizes( $acl, $size, $post_id, $data ) {
		// Make thumbnail and medium image sizes on S3 private
		if ( 'medium' === $size || 'thumbnail' === $size ) {
			return 'private';
		}

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
	 * This filter allows your to change the arguments passed to S3 when uploading
	 * a file to S3.
	 *
	 * @param array $args
	 * @param int   $post_id
	 *
	 * @return array
	 */
	function object_meta( $args, $post_id ) {
		return $args;
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

	/**
	 * This filter allows you to add or remove paths of files that will be uploaded
	 * to S3. This can be used to upload associated images to an attachment used by a plugin.
	 *
	 * @param string $paths
	 * @param int    $attachment_id
	 * @param array  $meta
	 *
	 * @return array
	 */
	function attachment_file_paths( $paths, $attachment_id, $meta ) {
		global $as3cf;

		foreach ( $paths as $file ) {
			$extra_file = $as3cf->apply_file_suffix( $file, '-plugin-copy' );
			if ( file_exists( $extra_file ) ) {
				$paths[] = $extra_file;
			}
		}

		return $paths;
	}

	/**
	 * Show the old Domain options in the Media Library settings tab
	 *
	 * @param bool $show
	 *
	 * @return bool
	 */
	function show_deprecated_domain_setting( $show ) {
		return true;
	}

	/**
	 * This filter allows you to adjust the path of a CloudFront URL.
	 * Useful when using a CloudFront distribution which uses a subdirectory of a bucket as its source.
	 *
	 * This example would allow a CloudFront distribution of s3.example.com/wpos3 to serve files as s3.example.com.
	 *
	 * @param array  $path_parts
	 * @param string $domain
	 *
	 * @return array
	 */
	function cloudfront_path_parts( $path_parts = array(), $domain = '' ) {
		if ( 's3.example.com' === $domain && 1 < count( $path_parts ) && 'wpos3' === $path_parts[0] ) {
			unset( $path_parts[0] );
		}

		return $path_parts;
	}

	/**
	 * This filter allows you to adjust the arguments passed to the AWS client.
	 *
	 * This example would allow you to connect to Beijing, which is an isolated region.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function aws_client_args( $args ) {
		$args['region'] = 'cn-north-1';

		return $args;
	}

	/**
	 * This filter allows you to adjust the expires time for private files.
	 *
	 * @param int $expires
	 *
	 * @return int
	 */
	function default_expires( $expires ) {
		return 60 * 60; // 1 hour
	}

	/**
	 * This filter allows you to exclude locations from the assets addon.
	 * Possible locations are 'core', 'themes', 'plugins' and 'mu-plugins'.
	 *
	 * @param array $locations
	 *
	 * @return array
	 */
	function assets_locations( $locations ) {
		unset( $locations[3] ); // mu-plugins

		return $locations;
	}

	/**
	 * This filter allows you to exclude files from the assets addon. It provides more
	 * granular control over the above filter, allowing you to exclude files by absolute
	 * path or extension.
	 *
	 * @param bool   $ignore
	 * @param string $file
	 * @param array  $details
	 *
	 * @return bool
	 */
	function assets_ignore_file( $ignore, $file, $details ) {
		if ( 'eot' === $details['extension'] ) {
			return true; // Ignore Embedded OpenType fonts
		}

		return false;
	}

	/**
	 * This filter allows you to exclude files from the assets addon minifier.
	 * File paths should be absolute.
	 *
	 * @param array $exclude
	 *
	 * @return array
	 */
	function assets_minify_exclude( $exclude ) {
		$exclude[] = '/abspath/wp-content/themes/twentyfifteen/genericons/genericons.css';

		return $exclude;
	}

	/**
	 * This filter allows you to control which file types will be gzipped.
	 * The `$media_library` parameter shows the upload context. True when uploaded via the
	 * Media Library or false when uploaded via the assets addon.
	 *
	 * @param array $mimes
	 * @param bool  $media_library
	 *
	 * @return array
	 */
	function assets_gzip_mimes( $mimes, $media_library ) {
		unset( $mimes[1] ); // Don't gzip Embedded OpenType fonts

		return $mimes;
	}

	/**
	 * This filter allows you to adjust the expires time for private assets.
	 *
	 * @param int $expires
	 *
	 * @return int
	 */
	function default_assets_expires( $expires ) {
		return 60 * 60; // 1 hour
	}

	/**
	 * This filter allows you to control the files that are being removed from the server
	 * after upload to S3.
	 *
	 * @param array  $files_to_remove
	 * @param int    $post_id
	 * @param string $file_path
	 *
	 * @return array
	 */
	function local_files_to_remove( $files_to_remove, $post_id, $file_path ) {
		if ( 'path/to/file.jpg' === $file_path ) {
			$files_to_remove = array_diff( $files_to_remove, array( $file_path ) );
		}

		return $files_to_remove;
	}
}

new Amazon_S3_and_CloudFront_Tweaks();
