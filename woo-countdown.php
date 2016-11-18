<?php
/*
Plugin Name: Woo Countdown
Version: 1.0.4
Description: A simple product countdown plugin
Author: Joel Lisenby
Author URI: https://www.joellisenby.com/
*/

namespace woocountdown;
use \DateTime;
use \DateTimeZone;

if ( ! defined( 'WPINC' ) ) {
    die;
}

class WooCountdown {
	
	public function __construct() {
		
		add_action( 'woocommerce_product_options_advanced', array( $this, 'product_countdown_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'product_countdown_fields_save' ), 100, 2 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'woocommerce_single_product_summary' ), 20 );
		add_action( 'woocommerce_product_query', array( $this, 'woocommerce_product_query' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		
	}
	
	function woocommerce_product_query( $q, $product ) {
		
		$meta_query = $q->get( 'meta_query' );
		
		$meta_query[] = array(
			'relation' => 'OR',
			array(
				'relation' => 'AND',
				array(
					'key' => '_countdown_start',
					'value' => '',
					'compare' => '='
				),
				array(
					'key' => '_countdown_end',
					'value' => '',
					'compare' => '='
				)
			),
			array(
				'relation' => 'AND',
				array(
					'key' => '_countdown_start',
					'value' => date('Y-m-d'),
					'compare' => '<=',
					'type' => 'DATE'
				),
				array(
					'key' => '_countdown_end',
					'value' => date('Y-m-d'),
					'compare' => '>=',
					'type' => 'DATE'
				)
			)
		);
		
		$q->set( 'meta_query', $meta_query );
		
	}
	
	function woocommerce_single_product_summary() {
		global $post;
		
		$countdown_start = get_post_meta( $post->ID, '_countdown_start', true );
		$countdown_end = get_post_meta( $post->ID, '_countdown_end', true );
		
		$woosale_start = new DateTime( $countdown_start .' 00:00:00 '. get_option('gmt_offset') );
		$woosale_end = new DateTime( $countdown_end .' 23:59:59 '. get_option('gmt_offset') );
		$now = new DateTime('NOW');
		
		$woosale_start->setTimezone(new DateTimeZone(get_option('timezone_string')));
		$woosale_end->setTimezone(new DateTimeZone(get_option('timezone_string')));
		$now->setTimezone(new DateTimeZone(get_option('timezone_string')));
		
		if( $now > $woosale_start && !empty( $countdown_end ) ) {
			$month = $woosale_start->format("n") - 1;
			$start = $woosale_start->format("'Y',") ."'". $month ."',". $woosale_start->format("'j','H','i','s'");
			$end = $woosale_end->format("'Y',") ."'". $month ."',". $woosale_end->format("'j','H','i','s'");
			echo '<div class="counter" id="jblcountdown"></div><script>var woocountdown = new jblcountdown( \'jblcountdown\', new Date('. $start .'), new Date('. $end .') );</script><p class="timeleft"><strong>TIME LEFT TO BUY THIS TEE!</strong></p>';
			
			if($now > $woosale_end) {
				echo '<style>.variations_form, p.timeleft { display: none !important; }</style>';
			}
		}
	}
	
	public function wp_enqueue_scripts() {
		$plugin = get_plugin_data( __FILE__, false );
		
		wp_enqueue_script( 'jblcountdown', plugins_url( '/jblcountdown.js', __FILE__ ), false, $plugin['Version'] );
		wp_enqueue_style( 'jblcountdown', plugins_url( '/jblcountdown.css', __FILE__ ), false, $plugin['Version'] );
	}

	public function product_countdown_fields() {
		
		global $woocommerce, $post;
		
		wp_enqueue_script( 'jquery-ui-datepicker' );

		echo '<div class="options_group">';
		
		woocommerce_wp_text_input(
			array(
				'id' => '_countdown_start',
				'label' => __('Countdown Start Date'),
				'required' => false,
				'class' => 'form-field datepicker'
			)
		);
		
		woocommerce_wp_text_input(
			array(
				'id' => '_countdown_end',
				'label' => __('Countdown End Date'),
				'required' => false,
				'class' => 'form-field datepicker'
			)
		);
		
		echo '</div>';
		echo '<script>jQuery(document).ready(function( $ ) { $( ".datepicker" ).datepicker({dateFormat:"yy-mm-dd"}); });</script>';
		
	}

	public function product_countdown_fields_save( $post_id, $post ) {
		
		if( isset( $_POST['_countdown_start'] ) ) {
			update_post_meta( $post_id, '_countdown_start', wc_clean( $_POST['_countdown_start'] ) );
		}
		
		if( isset( $_POST['_countdown_end'] ) ) {
			update_post_meta( $post_id, '_countdown_end', wc_clean( $_POST['_countdown_end'] ) );
		}
		
	}

}

new WooCountdown();

?>
