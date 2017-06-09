<?php
/*
Plugin Name: Promotator
Description: This plugin will help you to make mass mailings to a specific user group with a specific template including unique posts!
Version: 1.0
Author: GeroNikolov
Author URI: http://geronikolov.com
License: GPLv2
*/

class PROMOTATOR {
    function __construct() {
        // Add menu page
        add_action( "admin_menu", array( $this, "prom_dashboard_controller" ) );

        // Register AJAX call for the prom_send_mailing method
		add_action( 'wp_ajax_prom_send_mailing', array( $this, 'prom_send_mailing' ) );
		add_action( 'wp_ajax_nopriv_prom_send_mailing', array( $this, 'prom_send_mailing' ) );

        //Add scripts and styles for the Back-end part
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_JS' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_CSS' ) );
    }

    function __destruct() {}

    function prom_dashboard_controller() {
        add_menu_page( "Promotator", "Promotator", "administrator", "promotator", array( $this, "prom_dashboard_builder" ), "dashicons-format-status", NULL );
    }

    function prom_dashboard_builder() {
        require_once plugin_dir_path( __FILE__ ) ."pages/dashboard.php";
    }

	function add_admin_JS( $hook ) {
		wp_enqueue_script( 'ful-admin-js', plugins_url( '/assets/admin.js' , __FILE__ ), array('jquery'), '1.0', true );
	}

	function add_admin_CSS( $hook ) {
		wp_enqueue_style( 'ful-admin-css', plugins_url( '/assets/admin.css', __FILE__ ), array(), '1.0', 'screen' );
	}

    function prom_send_mailing() {
        $receivers_ = isset( $_POST[ "receivers" ] ) && !empty( $_POST[ "receivers" ] ) ? sanitize_text_field( $_POST[ "receivers" ] ) : "";
        $template_ = isset( $_POST[ "template" ] ) && !empty( $_POST[ "template" ] ) ? sanitize_text_field( $_POST[ "template" ] ) : "";
        $posts_ = isset( $_POST[ "posts" ] ) && !empty( $_POST[ "posts" ] ) ? $_POST[ "posts" ] : "";
        $subject_ = isset( $_POST[ "subject" ] ) && !empty( $_POST[ "subject" ] ) ? $_POST[ "subject" ] : "";

        if ( !empty( $receivers_ ) && !empty( $template_ ) && !empty( $posts_ ) && !empty( $subject_ ) ) {
            // Get the receivers
            $args = array(
                "role" => $receivers_,
                "orderby" => "ID",
                "order" => "DESC",
                "number" => -1
            );
            $users_ = get_users( $args );

            // Get the email template and configure it to work with the dynamicly selected posts
            $template_ = file_get_contents( plugin_dir_path( __FILE__ ) . "mailings/". $template_ );

            var_dump( $template_ );
        }

        die( "" );
    }
}

$promotator_ = new PROMOTATOR;
?>
