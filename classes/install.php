<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 16/06/14
 * Time: 1:05 PM
 */

class ZDTeamMembersInstall {
    function __construct() {
        $this -> widget_id = ZDTMW_TEXT_DOMAIN;
        $this -> version = ZDTMW_VERSION;

        load_plugin_textdomain($this->widget_id, false, basename( dirname( __FILE__ ) ) . '/languages' );

        add_action('admin_enqueue_scripts', array($this, 'load_admin_assets') );
        add_action('wp_enqueue_scripts', array($this, 'load_client_assets') );
    }

    function load_admin_assets() {
        $wid = $this->widget_id;

        wp_enqueue_style( $wid . '-admin-stylesheet', plugins_url( '/css/'.$wid.'-admin.css', dirname(__FILE__)) );

        wp_enqueue_media();
        wp_enqueue_script( $wid . '-admin-script', plugins_url( 'js/'.$wid.'-admin.js', dirname(__FILE__)), array(), $this->version, true);
    }

    function load_client_assets() {
        $wid = $this->widget_id;

        wp_enqueue_style( $wid . '-client-stylesheet', plugins_url( '/css/'.$wid.'-client.css', dirname(__FILE__)) );

        wp_enqueue_script( $wid . '-client-script', plugins_url( 'js/'.$wid.'-client.js', dirname(__FILE__)), array(), $this->version, true);
    }
}
new ZDTeamMembersInstall;