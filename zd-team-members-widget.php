<?php

/*
Plugin Name: Zing Design Team Member Widget
Plugin URI: http://www.zingdesign.com
Description: This plugin allows Administrators to add team members, staff, partners to a section of the site using widgets
Author: Samuel Holt
Version: 1.0
Author URI: http://www.zingdesign.com
*/

define( 'ZDTMW_TEXT_DOMAIN', 'zdtmw' );
define( 'ZDTMW_VERSION', '0.1.0' );

require_once( plugin_dir_path( __FILE__ ) . 'classes/install.php');
require_once( plugin_dir_path( __FILE__ ) . 'classes/utilities.php');
require_once( plugin_dir_path( __FILE__ ) . 'classes/widget.php');
require_once( plugin_dir_path( __FILE__ ) . 'classes/settings.php');

//Add action hooks
add_action( 'widgets_init', 'register_team_member_widget' );

//add_action( 'init', 'zdtw_init' );

function register_team_member_widget() {
    register_widget( 'Team_Member_Widget' );
}