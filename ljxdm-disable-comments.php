<?php
/**
 * Plugin Name: LJXDM Disable Comments
 * Plugin URI: https://github.com/LJXDM/ljxdm-disable-comments
 * Description: Disable all comments and trackback features.
 * Version: 0.1
 * Author: ljxdm
 * Author URI: https://lojinx.digital
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') or die('No.');

$ljxdm_disable_comments = new ljxdmDisableComments;

class ljxdmDisableComments {


	public function __construct() {		
		add_action('admin_init', array($this, 'ljxdm_disable_comments_post_types'));
		add_action('admin_init', array($this, 'ljxdm_disable_comments_metabox'));
		add_action('admin_menu', array($this, 'ljxdm_disable_comments_admin_menu'));
		add_action('admin_init', array($this, 'ljxdm_disable_comments_admin_menu_redirect'));
		add_action('init', array($this, 'ljxdm_disable_comments_admin_bar'));
		add_filter('comments_open', array($this, 'ljxdm_disable_comments_status'), 20, 2);
		add_filter('pings_open', array($this, 'ljxdm_disable_comments_status'), 20, 2);
		add_filter('comments_array', array($this, 'ljxdm_disable_and_hide_comments'), 10, 2);
		}

	// disable post comments & trackbacks in post types.
	public static function ljxdm_disable_comments_post_types() {
		$post_types = get_post_types();

		foreach($post_types as $post_type) {
			if(post_type_supports($post_type, 'comments')) {
				remove_post_type_support($post_type, 'comments');
				remove_post_type_support($post_type, 'trackbacks');
			}
		}
	}

	// remove comments metabox
	public static function ljxdm_disable_comments_metabox() {
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	}

	// remove from admin menu
	public static function ljxdm_disable_comments_admin_menu() {
		remove_menu_page('edit-comments.php');
	}

	// redirect admin link, just in case
	public static function ljxdm_disable_comments_admin_menu_redirect() {
		global $pagenow;
		if ('edit-comments.php' === $pagenow) {
			wp_redirect( admin_url());
			exit;
		}
	}

	// remove from admin bar.
	public static function ljxdm_disable_comments_admin_bar() {
		if (is_admin_bar_showing()) {
			remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
		}
	}
	// Close comments on the front-end.
	public static function ljxdm_disable_comments_status() {
		return false;
	}

	// hide existing comments.
	public static function ljxdm_disable_and_hide_comments($comments) {
		$comments = array();
		return $comments;
	}
}