<?php
/**
 * Plugin Name: Promoted_Pages
 * Plugin URI: https://www.facebook.com/artur.stefanski.9/
 * Description: This plugin is used to serve the promoted pages.
 * Version: 1.0.1
 * Author: Artur Stefański
 * Author URI: https://www.facebook.com/artur.stefanski.9/
 */

new Stefanski_Promoted_Pages; //comment this line if you want to disable the plugin

class Stefanski_Promoted_Pages {

	const CUSTOM_POST_TYPE = "strona";
	const PROMOTED_FIELD = "promowane";

	//I have an old database version where the 'promowane' field is select type.
	// If you have a true/false field, set the value to true or 1
	const PROMOTED_VALUE = "Promowane";
	//I have an old database version where the 'promowane' field is select type.
	// If you have a true/false field, set the value to false or 0
	const PROMOTED_VALUE_FALSE = "Nie promowane";
	const POMOTION_TIME = "+1 months";


	public function __construct() {


		// A cron job that updates the status of promoted pages.
		// Comment if you use an external cron utility.
//		do_action('init', array($this, 'expire_posts_cron'));

		// Action to be used e.g. by an external cron utility
		add_action('expire_posts', array($this, 'expire_posts_function'));

		// Change date when the promotion status has changed
		add_action( 'update_postmeta',  array($this,'change_page_date'), 30, 4);
//		add_action( 'cron_delete_post_expired_date', 'change_page_date' );
		// actions for instant testing
			// same as in cron only instantly
//			add_action('wp', array($this, 'expire_posts_function'));
			// If less than a month from the date of publication, it sets it to "promoted" otherwise to "not promoted"
//			add_action('wp', array($this, 'set_all_promoted_and_not_promoted'));
			// set all pages as not promoted
//			add_action('wp', array($this, 'set_all_pages_as_not_promoted'));
	}

	function expire_posts_cron() {
		if ( !wp_next_scheduled( 'expire_posts_function' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'expire_posts_function');
		}
	}

	function change_page_date($meta_id, $post_id, $meta_key, $meta_value)
	{
		if (get_post_type(get_post($post_id)) == Stefanski_Promoted_Pages::CUSTOM_POST_TYPE && $meta_key == Stefanski_Promoted_Pages::PROMOTED_FIELD) {
			$old_value = get_post_meta($post_id, $meta_key, true);
			$new_value = $meta_value;
			if ($old_value !== $new_value && $new_value == Stefanski_Promoted_Pages::PROMOTED_VALUE) {
				$time = current_time('mysql');
				wp_update_post(
					array(
						'ID' => $post_id,
						'post_date' => $time,
						'post_date_gmt' => get_gmt_from_date( $time ),
					)
				);
			}
		}
	}

	function expire_posts_function() {
		$posts = $this->get_promoted_pages();
		foreach ( $posts as $post ) {
			$today = date( 'Ymd' );
			$post_date   = get_the_date( 'Ymd', $post->ID );
			$expire_date = date( 'Ymd', strtotime( self::POMOTION_TIME, strtotime( $post_date ) ) );
			if ( $expire_date < $today) {
				update_post_meta( $post->ID, self::PROMOTED_FIELD, self::PROMOTED_VALUE_FALSE );
				do_action('search_filter_update_post_cache', $post->ID);
			}
		}
	}

	function set_all_promoted_and_not_promoted() {
		$today = date( 'Ymd' );
		$posts = $this->get_pages();
		foreach ( $posts as $post ) {
			$post_date   = get_the_date( 'Ymd', $post->ID );
			$expire_date = date( 'Ymd', strtotime( self::POMOTION_TIME, strtotime( $post_date ) ) );
			if ( $expire_date < $today) {
				update_post_meta( $post->ID, self::PROMOTED_FIELD, self::PROMOTED_VALUE_FALSE );
				do_action('search_filter_update_post_cache', $post->ID);
			}else{
				update_post_meta( $post->ID, self::PROMOTED_FIELD, self::PROMOTED_VALUE );
				do_action('search_filter_update_post_cache', $post->ID);
			}
		}
	}

	function set_all_pages_as_not_promoted() {
		$posts = $this->get_pages();
		foreach ( $posts as $post ) {
			update_post_meta( $post->ID, self::PROMOTED_FIELD, self::PROMOTED_VALUE_FALSE );
			do_action('search_filter_update_post_cache', $post->ID);
		}
	}
	
	private function get_pages() {
		$args  = array(
			'post_type'      => array( 'strona' ),
			'posts_per_page' => - 1,
		);
		return get_posts( $args );
	}
	
	private function get_promoted_pages(){
		$args  = array(
			'post_type'      => array( 'strona' ),
			'posts_per_page' => - 1,
			'meta_key'		=> 'promowane',
			'meta_value'	=> 'Promowane'
		);
		return get_posts( $args );
	}   



}