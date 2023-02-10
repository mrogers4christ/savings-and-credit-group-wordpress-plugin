<?php
/**
 * Plugin Name: Savings and Credit Group
 * Plugin URI:  https://sharebility.net/savings-and-credit-group-plugin
 * Description: A plugin for managing savings and credit group records.
 * Version:     1.0.0
 * Author:      Sharebility Uganda Limited
 * Author URI:  https://sharebility.net
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: savings-and-credit-group
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Savings_And_Credit_Group {

	/**
	 * Class constructor
	 */
	public function __construct() {
		// Register custom post type for members' savings and loans
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Register the custom post type for members' savings and loans
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Savings and Loans', 'Post Type General Name', 'savings-and-credit-group' ),
			'singular_name'         => _x( 'Savings and Loan', 'Post Type Singular Name', 'savings-and-credit-group' ),
			'menu_name'             => __( 'Savings and Loans', 'savings-and-credit-group' ),
			'name_admin_bar'        => __( 'Savings and Loan', 'savings-and-credit-group' ),
			'archives'              => __( 'Savings and Loans Archives', 'savings-and-credit-group' ),
			'attributes'            => __( 'Savings and Loans Attributes', 'savings-and-credit-group' ),
			'parent_item_colon'     => __( 'Parent Savings and Loan:', 'savings-and-credit-group' ),
			'all_items'             => __( 'All Savings and Loans', 'savings-and-credit-group' ),
			'add_new_item'          => __( 'Add New Savings and Loan', 'savings-and-credit-group' ),
			'add_new'               => __( 'Add New', 'savings-and-credit-group' ),
			'new_item'              => __( 'New Savings and Loan', 'savings-and-credit-group' ),
			'edit_item'             => __( 'Edit Savings and Loan', 'savings-and-credit-group' ),
			'update_item'           => __( 'Update Savings and Loan', 'savings-and-credit-group' ),
			'view_item'             => __( 'View Savings and Loan', 'savings-and-credit-group' ),
			'view_items'            => __( 'View Savings and Loans', 'savings-and-credit-group' ),
			'search_items'          => __( 'Search Savings and Loan', 'savings-and-credit-group' ),
			'not_found'             => __( 'Not found', 'savings-and-credit-group' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'savings-and-credit-group' ),
			'featured_image'        => __( 'Featured Image', 'savings-and-credit-group' ),
			'set_featured_image'    => __( 'Set featured image', 'savings-and-credit-group' ),
			'remove_featured_image' => __( 'Remove featured image', 'savings-and-credit-group' ),
			'use_featured_image'    => __( 'Use as featured image', 'savings-and-credit-group' ),
			'insert_into_item'      => __( 'Insert into savings and loan', 'savings-and-credit-group' ),
			'uploaded_to_this_item' => __( 'Uploaded to this savings and loan', 'savings-and-credit-group' ),
			'items_list'            => __( 'Savings and loans list', 'savings-and-credit-group' ),
			'items_list_navigation' => __( 'Savings and loans list navigation', 'savings-and-credit-group' ),
			'filter_items_list'     => __( 'Filter savings and loans list', 'savings-and-credit-group' ),
		);
		$args = array(
			'label'                 => __( 'Savings and Loan', 'savings-and-credit-group' ),
			'description'           => __( 'Custom post type for savings and loans of members in a savings and credit group', 'savings-and-credit-group' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-chart-line',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'savings-and-loan', $args );
	}

}

new Savings_And_Credit_Group();


class Savings_And_Credit_Group_DB {

	private static $table_name = 'scg_members';

	public static function create_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . self::$table_name;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			member_id varchar(50) NOT NULL,
			member_name varchar(255) NOT NULL,
			savings decimal(10,2) NOT NULL,
			loans decimal(10,2) NOT NULL,
			loan_repayments decimal(10,2) NOT NULL,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}

register_activation_hook( __FILE__, array( 'Savings_And_Credit_Group_DB', 'create_table' ) );

