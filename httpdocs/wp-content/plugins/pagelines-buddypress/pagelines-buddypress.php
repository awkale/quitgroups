<?php
/*

Plugin Name: BuddyPress for PageLines
Plugin URI: http://www.pagelines.com/
Description: Adds support for BuddyPress inside PageLines Framework. Requires that you've installed and activated BuddyPress and BuddyPress Template Pack plugins; using a child theme is recommended as well.
Version: 0.9.1
Author: PageLines
Author URI: http://www.pagelines.com
PageLines: true
PLVersion: 2.1

*/


/* TODO
 * disable reponsive 
 * 
 */

class PageLinesBuddyPress {
	
	function __construct() {

		$this->base_url = sprintf( '%s/%s', WP_PLUGIN_URL,  basename(dirname( __FILE__ )));
		
		$this->base_dir = sprintf( '%s/%s', WP_PLUGIN_DIR,  basename(dirname( __FILE__ )));
		
		$this->base_file = sprintf( '%s/%s/%s', WP_PLUGIN_DIR,  basename(dirname( __FILE__ )), basename( __FILE__ ));
		
		$this->plugin_hooks();
		
		// register plugin hooks...
		add_action( 'init', array( &$this, 'init' ) );
		
		$this->buddypress_layout();
	}
	
	/**
	 *	Plugin hooks
	 */
	function plugin_hooks() {
		
		register_activation_hook( $this->base_file, array( &$this, 'plbp_activate' ) );
		register_deactivation_hook( $this->base_file, array( &$this, 'plbp_deactivate' ) );
	}
	
	
	function plbp_activate() {
		
		toggle_integration( 'pagelines-integration-buddypress', 'PageLines Buddypress', true);	
	
	}
	
	function plbp_deactivate() {
		
		toggle_integration( 'pagelines-integration-buddypress', 'PageLines Buddypress', false);

	}
	
	
	/**
	 *	Init
	 */
	function init() {
		
		if ( version_compare( CORE_VERSION, '2.1.99' ) < 0 )
			$this->legacy = true;
		else
			$this->legacy = false;
	
		global $bp;
		
		if( is_admin() ) {
			
			if ( ! $this->legacy )
				$this->plbp_deactivate();			
			return;
		}

		add_action( 'wp_print_styles', array( &$this, 'head_css' ), 99 );
		add_filter( 'pagelines_lesscode', array( &$this, 'bp_less' ), 10, 1 );
	}


	/**
	 *	Add tabs to Special Meta
	 */
	function bp_meta( $d ) {

		global $metapanel_options;

		$meta = array(
		
		'buddypress' => array(
			'metapanel' => $metapanel_options->posts_metapanel( 'buddypress', 'buddypress' ),
			'icon'		=> $this->base_url.'/icon.png'
		),
		);

		$d = array_merge($d, $meta);

		return $d;
	}
	
	/**
	 *	Include less file
	 */
	function bp_less( $less ) {
		
		
		$less .= pl_file_get_contents( sprintf( '%s/color.less', $this->base_dir ) );
		
		return $less;
	}
	
	/**
	 *	Run at wp_print_styles hook
	 */
	function head_css() {
		
		// Only add special class on BP pages
		// Set layout mode to fullwidth
			global $bp;
			if ( is_object( $bp ) && isset($bp->current_component) && $bp->current_component != ''){
				global $pagelines_layout;
				if ( $this->legacy )
					$pagelines_layout->build_layout('fullwidth');
				pagelines_add_bodyclass( 'buddypress-page' );
		
			}
		// Add css on all pages, because of admin bar
			$style = sprintf( '%s/%s', $this->base_url, 'style.css' );
			wp_register_style( 'plbp-style', $style );
			wp_enqueue_style( 'plbp-style' );		

	}	
	
	function buddypress_layout(){
		
		add_filter('pl_content_width', array(&$this, 'set_content_width'));
		
	}
	
	function set_content_width( $array ){
		
		if ( $this->legacy )
			$array[] = '.buddypress-page #content';
		
		return $array;		
	}

} // End of class


/**
 *	Initiate class
 */
new PageLinesBuddyPress;