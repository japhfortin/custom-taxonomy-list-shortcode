<?php 
/*
Plugin Name: Custom Taxonomy List Shortcode
Description: Display list of custom taxonomy.
Plugin URI: http://japhfortin.com/custom-taxonomy-list-shortcode/
Author: Japheth Fortin
Author URI: http://japhfortin.com
Version: 1.0
License: GPL2
Text Domain: Text Domain
Domain Path: Domain Path
*/

/*

    Copyright (C) Year  Author  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
function jf_custom_taxonomy_list( $atts ) {
    $a = shortcode_atts( array(
        'taxonomy' => '',
        'hide_empty' => 'false'
    ), $atts );

    if ($a['taxonomy']) {
    	// get taxonomy from shortcode
    	$custom_taxonomy = $a['taxonomy'];

    	// get hide_empty value
    	if (is_bool($a['hide_empty'])) {
    		$hide_empty = $a['hide_empty'];
	    	if ($hide_empty) {
	    		$hide_empty = 'true';
	    	} else {
	    		$hide_empty =  'false';
	    	}
    	} else {
    		$hide_empty =  'false';
    	}

    	// get list of terms
    	$terms = get_terms( $custom_taxonomy, array(
		    'hide_empty' =>  json_decode($hide_empty),
		) );


		if (!is_wp_error($terms )) {
			$count = count( $terms );
		    $i = 0;
		    $term_list = '<ul class="ctl-list">';
		    foreach ( $terms as $term ) {
		        $i++;
                $term_list .= '<li>';
                // image here
                // check if category and taxonomy image plugin exists
                if (function_exists('aft_options_menu')) {
                    $meta_image = get_wp_term_image($term->term_id);
                    if ($meta_image) {
                        $term_list .= '<div class="ctl-image-container"><img src="'. $meta_image .'"></div>';
                    }
                }
                // end image here
		        $term_list .= '<div class="ctl-title-container"><a href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) ) . '">' . $term->name . '</a></div>';
                $term_list .= '</li>';
		    }
		    $term_list .= '</ul>';
		    return $term_list;
		}
		else {
			return 'Please specify correct custom taxonomy slug.';
		}

    } else {
    	return 'Please specify correct custom taxonomy slug.';
    }
}

add_shortcode( 'custom_taxonomy_list', 'jf_custom_taxonomy_list' );