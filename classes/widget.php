<?php

/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 23/05/14
 * Time: 10:50 AM
 *
 * Fields:
 * Avatar
 * Team member description
 * First name
 * Last name
 * Job title
 * Business name
 * Location
 *
 */

class Team_Member_Widget extends WP_Widget {

    private $widget_id;
    private $client_name;
    private $version;
    private $is_widget;

    public function __construct() {
        $prefix = ZDTMW_TEXT_DOMAIN;
        $this -> widget_id = $prefix;
        $this -> version = ZDTMW_VERSION;
        $this -> is_widget = true;

        $client_name = get_option( '_' . $prefix . '_client_name');

        $widget_name = 'Team Member';
        $widget_desc = 'Zing Design\'s team member widget';

        $upload_dir = wp_upload_dir();
        $this->uploads_dir_url = $upload_dir['baseurl'];

        parent::__construct(
            $this->widget_id . '_team_member_widget', // Base ID
            $widget_name, // Name
            array( 'description' => __( $widget_desc, $this -> widget_id ), ), //Widget Ops
            array( 'width' => 420 ) //Control Ops
        );
    }

    public function form( $instance ) {

        $html = '';
        $option_prefix = '_' . $this->widget_id . '_';

//        $admin_options = get_option($option_prefix . 'admin_options');

        $options = array();

//        $options = array(
//            array(
//                'label' => 'Avatar',
//                'type' => 'image'
//            ),
//            array(
//                'label' => 'team_member',
//                'type' => 'textarea'
//            ),
//            array(
//                'label' => 'First name'
//            ),
//            array(
//                'label' => 'Last name'
//            ),
//            array(
//                'label' => 'Job title'
//            ),
//            array(
//                'label' => 'Business name'
//            ),
//            array(
//                'label' => 'Location'
//            ),
//        );

        if( get_option($option_prefix . 'show_avatar' )) {
            $options[] = array(
                'label' => 'Avatar',
                'type' => 'image');
        }
        if( get_option($option_prefix . 'show_full_name') ) {
            $options[] = array(
                'label' => 'Full name'
            );
        }
        if( get_option($option_prefix . 'show_description') ) {
            $options[] = array(
                'label' => 'Description',
                'type' => 'textarea');
        }
//        if( get_option($option_prefix . 'show_last_name') ) {
//            $options[] = array( 'label' => 'Last name' );
//        }
        if( get_option($option_prefix . 'show_location') ) {
            $options[] = array( 'label' => 'Location' );
        }
        if( get_option($option_prefix . 'show_job_title') ) {
            $options[] = array( 'label' => 'Job title' );
        }
        if( get_option($option_prefix . 'show_business_name') ) {
            $options[] = array( 'label' => 'Business name' );
        }


        foreach( $options as $option ) {

            $option_slug = $this->slugify($option['label'], "_");

            $option['value'] = isset($instance[$option_slug]) ? $instance[$option_slug] : '';

            $html .= $this->form_field( $option );
        }

        echo $html;
    }

    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
        $instance = array();

        //        echo "<pre>";
        //        print_r($new_instance);
        //        echo "</pre>";

        //        $instance['avatar'] = $new_instance['avatar'];

        $instance['title'] = $new_instance['first_name'] . ' ' . $new_instance['last_name'];

        foreach( $new_instance as $key => $val ) {
            $instance[$key] = strip_tags( $val );
        }

        return $instance;
    }

    public function widget( $args, $instance ) {
        // outputs the content of the widget

        $full_name = $avatar = $team_member = $job_title = $business_name = $location = $description = "";

        $wid = $this->widget_id;

        extract( $instance );
        extract( $args );

//        $full_name = $first_name . ' ' . $last_name;
        $image_thumbnail_data = wp_get_attachment_image_src( $avatar, ZD_Utilities::zdtw_get_option('team_member_image_size_in_widget'));
        $image_thumbnail_src = $image_thumbnail_data[0];
        $image_thumbnail_width = $image_thumbnail_data[1];
        $image_thumbnail_height = $image_thumbnail_data[2];
        
        $image_full_data = wp_get_attachment_image_src( $avatar, 'full' );
        $image_full_src = $image_full_data[0];
        $image_full_width = $image_full_data[1];
        $image_full_height = $image_full_data[2];

        $modal_id = $this->slugify( $full_name );


        $html = '';

        $before = $before_widget;
        $after = $after_widget;

//        $show_multiple = get_option( '_' . $wid . '_show_multiple_team_members_in_side_bar') ? ' show-multiple' : '';
        $html .= $before;
        $html .= "<div class=\"{$wid}-team_member\">\n";
        //        $html .= "<h1>team_member</h1>\n";

        if( $image_thumbnail_data ) {
            $html .= "<div class=\"{$wid}-avatar-image\">\n";
            $html .= "<a href=\"#\" data-reveal-id=\"{$modal_id}\">\n";
            $html .= "<img src=\"{$image_thumbnail_src}\" alt=\"{$full_name}'s avatar.\" width=\"{$image_thumbnail_width}\" height=\"{$image_thumbnail_height}\" />\n";
            $html .= "</a>\n";
            $html .= "</div><!--avatar-image-->\n";
        }

        $custom_team_member_class = ZD_Utilities::zdtw_get_option('team_member_class_name');
//        $show_hr = ZD_Utilities::zdtw_get_option('show_horizontal_rule') ? '<hr/>' : '';

//        $html .= $show_hr;

        $html .= "<div class=\"team-member-meta\" data-equalizer>\n";

        $html .= "<p data-equalizer-watch>";

        if( $full_name ) {
            $html .= $full_name;
        }
        if( $job_title ) {
            $html .= " <br /> {$job_title}";
        }
        if( $business_name ) {
            $html .= ", {$business_name}";
        }
        $html .= "</p>\n";

        if($location) {
            $html .= "<span class=\"location\">{$location}</span>\n";
        }

        $html .= "</div><!--team-member-meta-->\n";
        $html .= "</div><!--{$wid}-team-member-->\n";

        // Modal window:

        $html .= "<div id=\"{$modal_id}\" class=\"reveal-modal\" data-reveal>\n";

        $html .= "<article>\n";

        if( $image_full_data ) {
            $html .= "<div class=\"{$wid}-avatar-image\">\n";
            $html .= "<img src=\"{$image_full_src}\" alt=\"{$full_name}'s avatar.\" width=\"{$image_full_width}\" height=\"{$image_full_height}\" />\n";
            $html .= "</div><!--avatar-image-->\n";
        }

        $html .= "<h2>{$full_name}</h2>\n";

        $html .= "<div class=\"team-member-description {$custom_team_member_class}\">\n";
        $html .= wpautop( $description );
        $html .= "</div><!--/team_member-description-->\n";

        $html .= "</article>\n";

        $html .= "<a class=\"close-reveal-modal\">&#215;</a>\n";
        $html .= "</div><!--/reveal-modal-->\n";
        $html .= $after;
        echo $html;

    }

    function form_field( $args ) {
        $prefix = $this->widget_id;

        $input = $type = $id = $name = $value = $before = $after = "";

        $placeholder = false;

        $defaults = array(
            'type' => 'text'
        );

        $args = wp_parse_args( $args, $defaults );

        extract( $args );

        if( ! $args['label'] ) {
            return "<span class=\"error\">Error: label key is required</span>";
        }

        $label = $args['label'];

        // If id/name is undefined, generate name from label
        $_id = ($id === "") ? $this->slugify($label) : $id;
        $_name = ($name === "") ? str_replace("-", "_", $_id) : $name;

        //        if( ! $placeholder ) {
        //            $placeholder = $label;
        //        }

        if( $this->is_widget ) {
            $_id = $this->get_field_id($_id);
            $_name = $this->get_field_name($_name);
        }


        $is_text_field = in_array( $type, array( "text", "email", "password", "hidden" ) );

        if( $is_text_field || "textarea" === $type) {
            $wrapper = 'p';
            $wrapper_class = 'zd-input-group';
        }

        if( isset( $wrapper ) ) {

            $after = '</' . $wrapper . '>' . "\n";

            if( isset( $wrapper_class ) ) {
                $wrapper .= ' class="' . $wrapper_class . '"';
            }

            $before = '<' . $wrapper . '>' . "\n";
        }

        $input .= $before;

        $input .= "<label for=\"{$_id}\">{$label}</label>\n";

        //        var_dump( $type );

        if( $is_text_field ) {
            $input .= "<input type=\"{$type}\" id=\"{$_id}\" name=\"{$_name}\" value=\"{$value}\" placeholder=\"{$placeholder}\" />\n";
        }
        else if( "textarea" === $type ) {
            $input .= "<textarea id=\"{$_id}\" name=\"{$_name}\" placeholder=\"{$placeholder}\">{$value}</textarea>\n";
        }
        else if( "image" === $type ) {
            $random = mt_rand(100, 10000);

            $button_text = empty($value) ? __("Insert image", $this->widget_id) : __("Update image", $this->widget_id);

            $image_src = empty($value) ? array('') : wp_get_attachment_image_src($value);

            $hide_remove_button = empty($value) ? ' hidden' : '';

            $src = $image_src[0];
            $short_src = str_replace( $this->uploads_dir_url, '', $src );

            $bg = ( strlen($src) > 0 ) ? ' style="background: url(' . $src . ') no-repeat; width: auto; height: 100px; background-size:contain;"' : "";

            $input .= "<div class=\"image-group\">\n";

            $input .= "<input type=\"text\" id=\"zd-image-src-{$random}\" name=\"{$_name}[src]\" value=\"{$short_src}\" />\n";
            $input .= "<input type=\"hidden\" id=\"zd-image-id-{$random}\" name=\"{$_name}\" value=\"{$value}\" />\n";

            $input .= "<button class=\"{$prefix}-insert-image-button button button-primary\">{$button_text}</button>\n";

            $input .= "<button class=\"{$prefix}-remove-image-button button button-secondary{$hide_remove_button}\">Remove image</button>\n";

            $input .= "<p class=\"image-preview-label\"><strong>".__("Image Preview", $this->widget_id)."</strong></p>\n";

            $input .= "<div class=\"zd-image-preview\" id=\"zd-image-preview-{$random}\"{$bg}></div><!--image-preview-->\n";

            $input .= "</div><!--image-group-->\n";
        }

        $input .= $after;

        return $input;

    }

    function slugify( $str, $spacer="-" ){
        $str = str_replace(' ', $spacer, trim( strtolower( $str ) ) );

        return preg_replace("/[^A-Za-z0-9$spacer ]/", '', $str);
    }
}