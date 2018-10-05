<?php
/*
Plugin Name: WP Offload Media Tweaks
Plugin URI: http://github.com/deliciousbrains/wp-amazon-s3-and-cloudfront-tweaks
Description: Examples of using WP Offload Media's filters
Author: Delicious Brains
Version: 0.2.0
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

	/**
	 * The constructor holds the `add_filter` and `add_action` statements that can be uncommented to activate them.
	 *
	 * Please only uncomment the statements you need after making sure their respective functions are correctly
	 * updated for your needs.
	 */
	function __construct() {
		/*
		 * WP Offload Media & WP Offload Media Lite
		 *
		 * https://deliciousbrains.com/wp-offload-media/
		 * https://wordpress.org/plugins/amazon-s3-and-cloudfront/
		 */

		/*
		 * Settings related filters.
		 *
		 *  Each setting has a filter, e.g, object-prefix ("Path" prefix in bucket) would be the following.
		 */
		//add_action( 'as3cf_setting_object-prefix', array( $this, 'get_setting_object_prefix' ), 10, 1 );
		//add_filter( 'as3cf_show_deprecated_domain_setting', array( $this, 'show_deprecated_domain_setting' ) );

		/*
		 * Storage Provider related filters.
		 */

		//add_filter( 'aws_get_client_args', array( $this, 'aws_get_client_args' ), 10, 1 );

		/*
		 * Storage related filters.
		 */

		//add_filter( 'as3cf_allowed_mime_types', array( $this, 'allowed_mime_types' ), 10, 1 );
		//add_filter( 'as3cf_pre_update_attachment_metadata', array( $this, 'pre_update_attachment_metadata' ), 10, 4 );
		//add_filter( 'as3cf_pre_upload_attachment', array( $this, 'pre_upload_attachment' ), 10, 3 );
		//add_filter( 'as3cf_legacy_ms_subsite_prefix', array( $this, 'legacy_ms_subsite_prefix' ), 10, 2 );
		//add_filter( 'as3cf_get_object_version_string', array( $this, 'get_object_version_string' ), 10, 1 );
		//add_filter( 'as3cf_upload_acl', array( $this, 'upload_acl' ), 10, 3 );
		//add_filter( 'as3cf_upload_acl_sizes', array( $this, 'upload_acl_sizes' ), 10, 4 );
		//add_filter( 'as3cf_object_meta', array( $this, 'object_meta' ), 10, 4 );
		//add_filter( 'as3cf_attachment_file_paths', array( $this, 'attachment_file_paths' ), 10, 3 );
		//add_filter( 'as3cf_upload_attachment_local_files_to_remove', array( $this, 'upload_attachment_local_files_to_remove' ), 10, 3 );
		//add_filter( 'as3cf_preserve_file_from_local_removal', array( $this, 'preserve_file_from_local_removal' ), 10, 2 );

		/*
		 * URL Rewrite related filters.
		 */
		//add_filter( 'as3cf_local_domains', array( $this, 'local_domains' ), 10, 1 );
		//add_filter( 'as3cf_use_ssl', array( $this, 'use_ssl' ), 10, 1 );
		//add_filter( 'as3cf_get_attachment_url', array( $this, 'get_attachment_url' ), 10, 4 );
		//add_filter( 'as3cf_wp_get_attachment_url', array( $this, 'wp_get_attachment_url' ), 10, 2 );
		//add_filter( 'as3cf_get_attached_file_copy_back_to_local', array( $this, 'get_attached_file_copy_back_to_local' ), 10, 3 );
		//add_filter( 'as3cf_expires', array( $this, 'default_expires' ), 10, 1 );
		//add_filter( 'as3cf_cloudfront_path_parts', array( $this, 'cloudfront_path_parts' ), 10, 2 );

		/*
		 * WP Offload Media (Pro)
		 *
		 * https://deliciousbrains.com/wp-offload-media/
		 */

		//add_filter( 'as3cfpro_media_actions_capability', array( $this, 'media_actions_capability' ), 10, 1 );

		/*
		 * WP Offload Media - Assets Pull Addon
		 *
		 * https://deliciousbrains.com/wp-offload-media/doc/assets-pull-addon/
		 */

		//add_filter( 'as3cf_assets_pull_test_endpoint_sslverify', array( $this, 'assets_pull_test_endpoint_sslverify' ), 10, 2 );
	}

	/**
	 * The "as3cf_setting_{key} filter allows your to override specific settings before they are used.
	 *
	 * @handles `as3cf_setting_object-prefix`
	 *
	 * @param mixed $value
	 *
	 * @return string
	 *
	 * Note: Settings keys can be found in the Settings Constants doc.
	 * https://deliciousbrains.com/wp-offload-media/doc/settings-constants/
	 */
	function get_setting_object_prefix( $value ) {
		return '/my/custompath/';
	}

	/**
	 * Show the old Domain options in the Media Library settings tab.
	 *
	 * @handles `as3cf_show_deprecated_domain_setting`
	 *
	 * @param bool $show
	 *
	 * @return bool
	 */
	function show_deprecated_domain_setting( $show ) {
		return true;
	}

	/**
	 * This filter allows you to adjust the arguments passed to the AWS client.
	 *
	 * This example would allow you to connect to Beijing, which is an isolated region.
	 *
	 * @handles `aws_get_client_args`
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function aws_get_client_args( $args ) {
		$args['region'] = 'cn-north-1';

		return $args;
	}

	/**
	 * This filter allows your limit specific mime types of files that
	 * can be uploaded to the bucket. They will still be uploaded to the
	 * WordPress media library but ignored from the offload process.
	 *
	 * @handles `as3cf_allowed_mime_types`
	 *
	 * @param array $types
	 *
	 * @return array
	 */
	function allowed_mime_types( $types ) {
		// Disallow offload of PDFs.
		unset( $types['pdf'] );

		// Allow offload of PDFs.
		$types['pdf'] = 'application/pdf';

		return $types;
	}

	/**
	 * This filter allows the offload to the bucket to be aborted on a per attachment basis.
	 *
	 * @handles `as3cf_pre_update_attachment_metadata`
	 *
	 * @param bool  $abort
	 * @param array $data    attachment metadata
	 * @param int   $post_id attachment ID
	 * @param mixed $old_provider_object
	 *
	 * @return mixed
	 *
	 * Note: Filter fires when attachment uploaded to Media Library, edited or metadata otherwise
	 * updated by some process.
	 */
	function pre_update_attachment_metadata( $abort, $data, $post_id, $old_provider_object ) {
		if ( 55 == $post_id ) {
			$abort = true; // abort the upload
		}

		return $abort;
	}

	/**
	 * This filter allows the offload to the bucket to be aborted on a per attachment basis.
	 *
	 * @handles `as3cf_pre_upload_attachment`
	 *
	 * @param bool  $abort
	 * @param int   $post_id attachment ID
	 * @param array $data    attachment metadata
	 *
	 * @return mixed
	 *
	 * Note: Filter fires when attachment is about to be offloaded for any reason,
	 * including using Pro's bulk offload tools.
	 */
	function pre_upload_attachment( $abort, $post_id, $data ) {
		if ( 55 == $post_id ) {
			$abort = true; // abort the upload
		}

		return $abort;
	}

	/**
	 * This filter allows you to change the Multisite subsite prefix used to store the object in the bucket.
	 *
	 * @handles `legacy_ms_subsite_prefix`
	 *
	 * @param string $legacy_ms_prefix defaults to '<sitename>/files/'
	 * @param object $details          MS subsite details object
	 *
	 * @return string
	 *
	 * Note: Only fires when multisite still configured with pre WP 3.5 file paths, e.g. not using "sites/NN/" paths.
	 * Overrides WP Offload Media's Year/Month setting etc, but is appended to custom Path Prefix and suffixed by
	 * Object Versioning path if turned on.
	 * The `$legacy_ms_prefix` should not start with "/".
	 * The `$legacy_ms_prefix` should end with "/".
	 */
	function legacy_ms_subsite_prefix( $legacy_ms_prefix, $details ) {
		$legacy_ms_prefix = 'sites/' . $details->blog_id . '/';

		return $legacy_ms_prefix;
	}

	/**
	 * This filter allows you to change the object version prefix added to files
	 * as they are offloaded to the bucket.
	 *
	 * @handles `as3cf_get_object_version_string`
	 *
	 * @param string $object_version
	 *
	 * @return string
	 *
	 * Note: THis filter only fires when "Object Versioning" is turn on.
	 * The `$object_version` contains just the version segment of the object path, not the entire key path prefix.
	 * The `$object_version` should not start with "/".
	 * The `$object_version` should end with "/".
	 */
	function get_object_version_string( $object_version ) {
		// This appends "my-string/" to the current object version string.
		// e.g. "235959/" becomes "235959/my-string/".
		$object_version .= 'my-string/';

		return $object_version;
	}

	/**
	 * This filter allows your to change the default Access Control List (ACL)
	 * permission for an original file when offloaded to bucket.
	 *
	 * @handles `as3cf_upload_acl`
	 *
	 * @param string $acl defaults to 'public-read'
	 * @param array  $data
	 * @param int    $post_id
	 *
	 * @return string
	 */
	function upload_acl( $acl, $data, $post_id ) {
		return 'private';
	}

	/**
	 * This filter allows your to change the default Access Control List (ACL)
	 * permission for intermediate image sizes when offloaded to bucket.
	 *
	 * @handles `as3cf_upload_acl_sizes`
	 *
	 * @param string $acl defaults to 'public-read'
	 * @param string $size
	 * @param int    $post_id
	 * @param array  $data
	 *
	 * @return string
	 */
	function upload_acl_sizes( $acl, $size, $post_id, $data ) {
		// Make only thumbnail and medium image sizes private in bucket.
		if ( 'medium' === $size || 'thumbnail' === $size ) {
			return 'private';
		}

		return $acl;
	}

	/**
	 * This filter allows your to change the arguments passed to the cloud storage SDK client when
	 * offloading a file to the bucket.
	 *
	 * @handles `as3cf_object_meta`
	 *
	 * @param array  $args
	 * @param int    $post_id
	 * @param string $image_size small, medium, large
	 * @param bool   $copy       true if the object is being copied between buckets
	 *
	 * @return array
	 *
	 * Note: Only fires for the "original" media file, image sizes etc. will be placed next to original in bucket.
	 */
	function object_meta( $args, $post_id, $image_size, $copy ) {
		// Example places (potentially large) movie files in a different bucket than configured.
		// Also changes path prefix to match that used in CDN behavior's "Path Prefix" for this second origin.
		$extension = pathinfo( $args['Key'], PATHINFO_EXTENSION );
		if ( in_array( $extension, array( 'mp4', 'mov' ) ) ) {
			// Change bucket.
			$args['Bucket'] = 'my-cheaper-infrequent-access-bucket';

			// Change key (don't do this for images, thumbnails will not get new prefix and will not be usable).
			$filename    = pathinfo( $args['Key'], PATHINFO_FILENAME ) . '.' . $extension;
			$args['Key'] = 'movies/' . $filename;
		}

		return $args;
	}

	/**
	 * This filter allows you to add or remove paths of files that will be uploaded
	 * to the bucket. This can be used to upload associated images to an attachment used by a plugin.
	 *
	 * @handles `as3cf_attachment_file_paths`
	 *
	 * @param array $paths
	 * @param int   $attachment_id
	 * @param array $meta
	 *
	 * @return array
	 */
	function attachment_file_paths( $paths, $attachment_id, $meta ) {
		// Example adds some backup files created for original and all thumbnails by some plugin, if they exist.
		foreach ( $paths as $file ) {
			$pathinfo   = pathinfo( $file );
			$extra_file = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-backup-copy.' . $pathinfo['extension'];
			if ( file_exists( $extra_file ) ) {
				$paths[] = $extra_file;
			}
		}

		return $paths;
	}

	/**
	 * This filter allows you to control the files that are being removed from the server
	 * after offload to the bucket.
	 *
	 * @handles `as3cf_upload_attachment_local_files_to_remove`
	 *
	 * @param array  $files_to_remove
	 * @param int    $post_id
	 * @param string $file_path
	 *
	 * @return array
	 *
	 * Note: Filter only fires when a media item is being (re)offloaded to bucket and "Remove Files From Server" is turned on.
	 */
	function upload_attachment_local_files_to_remove( $files_to_remove, $post_id, $file_path ) {
		// Example stops the original path/to/file.jpg from being removed from server when copying to bucket.
		if ( 'path/to/file.jpg' === $file_path ) {
			$files_to_remove = array_diff( $files_to_remove, array( $file_path ) );
		}

		return $files_to_remove;
	}

	/**
	 * This filter allows you to stop files from being removed from the local server
	 * even when using WP Offload Media's "Remove all files from server" tool.
	 *
	 * @handles `as3cf_preserve_file_from_local_removal`
	 *
	 * @param bool   $preserve
	 * @param string $file_path
	 *
	 * @return bool
	 */
	function preserve_file_from_local_removal( $preserve, $file_path ) {
		// Example stops movie files from being removed from the local server.
		$extension = pathinfo( $file_path, PATHINFO_EXTENSION );
		if ( in_array( $extension, array( 'mp4', 'mov' ) ) ) {
			return true;
		}

		return $preserve;
	}

	/**
	 * This filter allows you to alter the local domains that can be filtered to bucket URLs.
	 *
	 * If you're dynamically altering the site's URL with something like the following...
	 *
	 * define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] );
	 * define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );
	 *
	 * ... then you'll need to append all known domains with this filter so that
	 * any URLs inserted into content with an alternate domain are matched as local.
	 *
	 * @handles `as3cf_local_domains`
	 *
	 * @param array $domains
	 *
	 * @return array
	 *
	 * Note: First entry in `$domains` *should* be akin to `siteurl`, but as returned by `wp_upload_dir()`.
	 * This however can be altered by domain mapping plugins or custom code as shown above.
	 * Therefore it's a good idea to "double down" and include configured domain as well as alternates here.
	 */
	function local_domains( $domains ) {
		// Example allows local URLs to be matched when site accessed as any of the 3 examples.
		$domains[] = 'example.com';
		$domains[] = 'example-pro.com';
		$domains[] = 'example-deluxe.com';

		// Example makes sure that the current multisite's canonical domain is included
		// in match check even if domain mapping etc. has changed the URL of site.
		if ( is_multisite() ) {
			$blog_details = get_blog_details();

			if ( false !== $blog_details && ! in_array( $blog_details->domain, $domains ) ) {
				$domains[] = $blog_details->domain;
			}
		}

		return $domains;
	}

	/**
	 * This filter allows you to control the scheme for bucket URLs, overrides "force-https" setting.
	 *
	 * @handles `as3cf_use_ssl`
	 *
	 * @param string $use_ssl
	 *
	 * @return bool
	 */
	function use_ssl( $use_ssl ) {
		$use_ssl = true;

		return $use_ssl;
	}

	/**
	 * This filter allows you to change the cloud storage URL for an attachment.
	 *
	 * @handles `as3cf_get_attachment_url`
	 *
	 * @param string $url
	 * @param array  $provider_object
	 * @param int    $post_id
	 * @param int    $expires
	 *
	 * @return string
	 *
	 * Note: Runs earlier than `as3cf_wp_get_attachment_url`
	 */
	function get_attachment_url( $url, $provider_object, $post_id, $expires ) {
		// Example changes domain to another CDN configured for dedicated movies bucket.
		if ( 'my-cheaper-infrequent-access-bucket' === $provider_object['bucket'] ) {
			// Get current hostname in URL.
			$hostname = parse_url( $url, PHP_URL_HOST );

			// Replace hostname in URL (only if not adorned with port or username/password in this example).
			$url = str_replace( '//' . $hostname . '/', '//movies.example.com/', $url );
		}

		return $url;
	}

	/**
	 * This filter allows you to change the cloud storage URL for an attachment.
	 *
	 * @handles `as3cf_wp_get_attachment_url`
	 *
	 * @param string $url
	 * @param int    $post_id
	 *
	 * @return string
	 *
	 * Note: Runs later than `as3cf_get_attachment_url`
	 */
	function wp_get_attachment_url( $url, $post_id ) {

		return $url;
	}

	/**
	 * This filter is applied during the plugins own filtering of 'get_attached_file'
	 * which by default returns the local file path, or if the attachment has been removed
	 * from the server after offload to the bucket, the URL. Using this filter we can allow the plugin
	 * to copy back the file from the bucket to the local server so it can be used for reasons
	 * such as editing the physical file.
	 *
	 * This should be used when we know the local file will get removed again
	 * via `wp_update_attachment_metadata`.
	 *
	 * @handles `as3cf_get_attached_file_copy_back_to_local`
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
	 * This filter allows you to adjust the path of a CDN (such as CloudFront) URL.
	 * Useful when using a CDN distribution which uses a subdirectory of a bucket as its source.
	 *
	 * @handles `as3cf_cloudfront_path_parts`
	 *
	 * @param array  $path_parts
	 * @param string $domain
	 *
	 * @return array
	 *
	 * Note: Filter uses 'cloudfront' in name for historical reasons, can be used with any CDN that
	 * supports the ability to use a path prefix with bucket as source.
	 */
	function cloudfront_path_parts( $path_parts = array(), $domain = '' ) {
		// Example would allow a CDN distribution of cdn.example.com/media/ to serve files as cdn.example.com.
		// Its important to remember that the CDN distribution must have been set up accordingly.
		if ( 'cdn.example.com' === $domain && 1 < count( $path_parts ) && 'media' === $path_parts[0] ) {
			unset( $path_parts[0] );
		}

		return $path_parts;
	}

	//
	// WP Offload Media (Pro)
	//


	/**
	 * This filter allows you to control the default capability for using
	 * the on-demand bucket media actions.
	 *
	 * The default capability is "manage_options" which only Administrators have
	 * of the roles that you get out-of-the-box with WordPress.
	 *
	 * Return "do_not_allow" to disable these for _all_ users.
	 *
	 * E.g. "Copy to Bucket" or "Remove from Bucket"
	 *
	 * @handles `as3cfpro_media_actions_capability`
	 *
	 * @param string $capability Registered capability identifier
	 *
	 * @return string
	 */
	function media_actions_capability( $capability ) {
		// Example capability would allow users with an Editor role to use the
		// on-demand actions as well.
		return 'delete_others_posts';
	}


	//
	// Assets Pull Addon Examples
	//


	/**
	 * By default HTTPS certificates are verified during Assets Pull's domain check,
	 * you might want to turn that off for self-signed dev certificates.
	 *
	 * @handles `as3cf_assets_pull_test_endpoint_sslverify`
	 *
	 * @param bool   $verify
	 * @param string $domain
	 *
	 * @return bool
	 */
	function assets_pull_test_endpoint_sslverify( $verify, $domain ) {
		return false;
	}
}

new Amazon_S3_and_CloudFront_Tweaks();
