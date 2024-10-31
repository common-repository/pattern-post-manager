<?php
/*
Plugin Name: pattern post manager
Plugin URI: https://ja.wordpress.org/plugins/pattern-post-manager/
Description: pattern-post-manager is a plugin to make Writing more efficient. Registering a pattern saves you the trouble of entering the same wording.
Version: 1.1.0
Author:IT-SOLEX
Author URI: https://it-solex.jp/
Text Domain: pattern-post-manager
Domain Path: /languages
License: GPL2
*/

/*
Copyright 2018 IT-SOLEX (email : contact@it-solex.jp)

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

class WP_its_Pattern_Post_Manager {

	function __construct() {

		add_action( 'plugins_loaded', array( $this , 'its_ppm_load_textdomain' ) );

		add_action( 'init' , array( $this , 'its_ppm_register_post' ) );
		add_action( 'admin_head' , array( $this , 'custom_mce_buttons_value' ) );
		add_filter( 'mce_buttons' , array( $this , 'custom_mce_buttons' ) );
		add_filter( 'mce_external_plugins' , array( $this , 'custom_mce_external_plugins' ) );

	}

	public function its_ppm_load_textdomain() {
		load_plugin_textdomain( 'pattern-post-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	// Register Custom Posts
	function its_ppm_register_post() {

		register_post_type(
			'its_ppm_item',
			array(
				'labels' => array(
								'name' => __('phrase pattern' , 'pattern-post-manager'),
								'all_items'=> __('phrase pattern list' , 'pattern-post-manager'),
								'add_new' => __('add phrase pattern' , 'pattern-post-manager'),
								'add_new_item' => __('add phrase pattern' , 'pattern-post-manager'),
								'edit_item' => __('edit phrase pattern' , 'pattern-post-manager'),
								'new_item' => __('new phrase pattern' , 'pattern-post-manager'),
								'view_item' => __('show phrase pattern' , 'pattern-post-manager'),
								'search_items' => __('search phrase pattern' , 'pattern-post-manager'),
								'not_found' =>  __('not found phrase pattern' , 'pattern-post-manager'),
								'not_found_in_trash' => __('not found phrase pattern in dustbin' , 'pattern-post-manager'),
								'parent_item_colon' => ''
							),
				'public' => false,
				'exclude_from_search' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 5,
				'hierarchical' => false,
				'has_archive' => false,
				'supports' => array(
								'title',
								'editor',
								'page-attributes'
							),
				'rewrite' => array('slug'=>'its_ppm_item', 'with_front' => true ),
			)
		);

	}

	// Add Custom Buttons
	function custom_mce_buttons( $buttons ) {
		$buttons[] = 'ppm-button';
		return $buttons;
	}
 
	// Load Custom Buttons Scripts
	function custom_mce_external_plugins( $plugin_array ) {
		$plugin_array['custom_button_script'] = plugins_url('/js/editor-button.js',__FILE__);
		return $plugin_array;
	}

	// Load Pattern Data
	function custom_mce_buttons_value(){
		echo '<script type="text/javascript">';

		$contents = array();

		$args = array(
			'post_type' => 'its_ppm_item',
			'order' => 'ASC',
			'orderby'=>'menu_order'
		);

		$my_posts = get_posts( $args );
		foreach ($my_posts as $my_post ) {
			setup_postdata($my_post);

			$cont = array(
				'text'  => get_the_title($my_post->ID),
				'value' => preg_replace("/\n/","<br />",get_the_content($my_post->ID))
			);
		
			$contents[] = $cont;

		}
		wp_reset_postdata();

		echo 'its_ppm_item_values = ' . json_encode($contents) .';';
		echo 'its_ppm_button_caption = "' . __('select phrase pattern','pattern-post-manager') .'";';

		echo '</script>';

	}
}

new WP_its_Pattern_Post_Manager();
?>