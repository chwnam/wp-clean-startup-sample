<?php
/**
 * Plugin Name: WordPress Clean Startup Sample
 * Description: GIST <a href="https://gist.github.com/chwnam/9f3bd818f65ce4ed3e337c834f7f2ba2">WordPress Clean Startup</a> 코드의 예제 플러그인입니다.
 * Version:     1.0.0
 * Author:      changwoo
 * Author URI:  cs.chwnam@gmail.com
 * Plugin URI:  https://github.com/chwnam/wp-clean-startup-sample
 */

function wpcs_query_vars( $query_vars ) {
	$query_vars[] = 'wpcs';
	return $query_vars;
}

add_filter( 'query_vars', 'wpcs_query_vars' );

function wpcs_clean_page() {
	include __DIR__ . '/includes/header.php';
	include __DIR__ . '/includes/clean-page.php';
	include __DIR__ . '/includes/footer.php';
}

function wpcs_template_redirect() {
	global $wp;
	if ( isset( $wp->query_vars['wpcs'] ) ) {
		wpcs_clean_page();
		exit;
	}
}

add_action( 'template_redirect', 'wpcs_template_redirect' );

function wpcs_request_flush_rewrite() {
	if ( ! has_action( 'shutdown', 'flush_rewrite_rules' ) ) {
		add_action( 'shutdown', 'flush_rewrite_rules' );
	}
}

function wpcs_rewrite_rules() {
	add_rewrite_rule( '^clean-page/?$', 'index.php?wpcs=clean-page', 'top' );
}

add_action( 'init', 'wpcs_rewrite_rules' );

function wpcs_activation() {
	wpcs_rewrite_rules();
	wpcs_request_flush_rewrite();
}

function wpcs_deactivation() {
	wpcs_request_flush_rewrite();
}

register_activation_hook( __FILE__, 'wpcs_activation' );
register_deactivation_hook( __FILE__, 'wpcs_deactivation' );

function wpcs_edit_description( $all_plugins ) {
	if ( isset( $all_plugins['wp-clean-startup-sample/wpcs-sample.php'] ) ) {
		$all_plugins['wp-clean-startup-sample/wpcs-sample.php']['Description'] .= ' 활성화 후 <a href="' . esc_url( home_url( 'clean-page' ) ) . ' ">여기</a>로 접속해서 확인해 보세요.';
	}

	return $all_plugins;
}

add_filter( 'all_plugins', 'wpcs_edit_description' );
