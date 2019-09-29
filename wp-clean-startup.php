<?php
if ( ! function_exists( 'wp_clean_startup_is_enabled' ) ) {
	function wp_clean_startup_is_enabled() {
		// Implement your code here. 시작 조건의 코드를 여기에 구현.
		$result = isset( $_SERVER['REQUEST_URI'] ) && '/clean-page/' === $_SERVER['REQUEST_URI'];
		return apply_filters( 'wp_clean_startup_is_enabled', $result );
	}
}

if ( wp_clean_startup_is_enabled() ) {
	if ( ! function_exists( 'wp_clean_startup_active_plugins' ) ) {
		function wp_clean_startup_active_plugins( $option ) {
			// Your plugin whitelist here. 플러그인의 화이트리스트.
			$whitelist = array('wp-clean-startup-sample/wpcs-sample.php');

			return array_intersect(
				$option,
				apply_filters( 'wp_clean_startup_plugin_whitelist', $whitelist )
			);
		}

		add_filter( 'option_active_plugins', 'wp_clean_startup_active_plugins' );
	}

	if ( ! function_exists( 'wp_clean_startup_return_fake_path' ) ) {
		function wp_clean_startup_return_fake_path() {
			return ABSPATH . 'fake-path/does-not-exist';
		}

		add_filter( 'template_directory', 'wp_clean_startup_return_fake_path' );
		add_filter( 'stylesheet_directory', 'wp_clean_startup_return_fake_path' );
	}

	if ( ! function_exists( 'wp_clean_startup_remove_wp_head' ) ) {
		function wp_clean_startup_remove_wp_head() {
			remove_action( 'wp_head', 'noindex', 1 );
			remove_action( 'wp_head', 'wp_post_preview_js', 1 );
			remove_action( 'wp_head', 'wp_resource_hints', 2 );
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
			remove_action( 'wp_head', 'rsd_link', 10 );
			remove_action( 'wp_head', 'wlwmanifest_link', 10 );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
			remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
			remove_action( 'wp_head', 'wp_generator', 10 );
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
			remove_action( 'wp_head', 'wp_oembed_add_host_js', 10 );
			remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
			remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );
		}

		add_action( 'init', 'wp_clean_startup_remove_wp_head' );
	}

	if ( ! function_exists( 'wp_clean_startup_document_title_parts' ) ) {
		global $_wp_theme_features;
		$_wp_theme_features['title-tag'] = true;

		function wp_clean_startup_document_title_parts() {
			return array(
				// Your site title here. 사이트의 창 제목 표시.
				'title' => apply_filters( 'wp_clean_startup_document_title', 'Your Cleaned WordPress' ),
				'page'  => 'Clean Page',
				'site'  => get_bloginfo( 'name', 'display' ),
			);
		}

		add_filter( 'document_title_parts', 'wp_clean_startup_document_title_parts' );
	}
}
