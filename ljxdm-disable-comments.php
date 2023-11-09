<?php
/**
 * Plugin Name: Simple Disable Comments
 * Description: Simply disables comments. No marketing, no invasive menus, no hassle.
 * Author: ljxdm
 * Author URI: https://ljx.uk
 * Version: 1.0.1
 * Plugin Slug: ljxdm-disable-comments
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'LJXDM_DISABLE_COMMENTS_VERSION', '1.0.1' );

class LJXDM_Disable_Comments {

	private static $instance;
	protected $plugin_file;
	protected $plugin_basename;

	public function __construct() {
		$this->plugin_file     = __FILE__;
		$this->plugin_basename = plugin_basename( $this->plugin_file );

		add_action( 'admin_init', array( &$this, 'redirect_admin_menu' ) );
		add_action( 'admin_init', array( &$this, 'remove_meta_box' ) );
		add_action( 'admin_init', array( &$this, 'remove_post_types_support' ) );
		add_action( 'admin_menu', array( &$this, 'remove_admin_menu' ) );
		add_action( 'wp_before_admin_bar_render', array( &$this, 'remove_admin_bar_menu' ) );

		add_filter( 'comments_array', array( &$this, 'empty_array' ), 10, 2 );
		add_filter( 'comments_open', array( &$this, 'false' ), 20, 2 );
		add_filter( 'pings_open', array( &$this, 'false' ), 20, 2 );
		add_action( 'pre_comment_on_post', array( &$this, 'disable_post_pre_comment' ) );
	}

	public function false() {
		return false;
	}

	public function empty_array( $comments ) {
		return array();
	}

	public function remove_post_types_support() {

		$post_types = get_post_types();

		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	public function remove_admin_menu() {
		remove_menu_page( 'edit-comments.php' );
	}

	public function redirect_admin_menu() {

		global $pagenow;

		if ( 'edit-comments.php' === $pagenow ) {
			wp_safe_redirect( admin_url() );
			exit();
		}
	}

	public function remove_meta_box() {

		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	public function remove_admin_bar_menu() {

		global $wp_admin_bar;

		$wp_admin_bar->remove_menu( 'comments' );
	}

	public function disable_post_pre_comment() {

		wp_die( 'No comments' );
	}
}

$ljxdm_disable_comments = new LJXDM_Disable_Comments();
