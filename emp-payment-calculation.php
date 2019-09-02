<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://empassion.com.au
 * @since             1.0.0
 * @package           Emp_Payment_Calculation
 *
 * @wordpress-plugin
 * Plugin Name:       Empassion Payment Calculation
 * Plugin URI:        https://empassion.com.au
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Empassion Dev
 * Author URI:        https://empassion.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       emp-payment-calculation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EMP_PAYMENT_CALCULATION_VERSION', '4.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-emp-payment-calculation-activator.php
 */
function activate_emp_payment_calculation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-emp-payment-calculation-activator.php';
	Emp_Payment_Calculation_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-emp-payment-calculation-deactivator.php
 */
function deactivate_emp_payment_calculation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-emp-payment-calculation-deactivator.php';
	Emp_Payment_Calculation_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_emp_payment_calculation' );
register_deactivation_hook( __FILE__, 'deactivate_emp_payment_calculation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-emp-payment-calculation.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_emp_payment_calculation() {

	$plugin = new Emp_Payment_Calculation();
	$plugin->run();

}
run_emp_payment_calculation();

//add_action( 'admin_enqueue_scripts', 'add_backend_ajax_javascript_file' );
function add_backend_ajax_javascript_file(){
//	wp_enqueue_script( 'empdev-angular-js', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.min.js' );
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'empdev-moment-js', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js' );

	wp_enqueue_script( 'empdev-datepicker-js', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js' );

	wp_enqueue_script( 'emp-payment-calculation', plugin_dir_url( __FILE__ ) . 'js/emp-payment-calculation-admin.js', array( 'jquery', 'empdev-moment-js', 'empdev-datepicker-js' ), '2.1.1', false );


	wp_localize_script( 'emp-payment-calculation', 'empdevajax',  array( 'ajaxurl' => admin_url( 'admin-ajax.php'  ) ) );

}



//add_action("wp_ajax_get_wc_order_data", "get_wc_order_data_handler" );
//add_action("wp_ajax_nopriv_get_wc_order_data", "get_wc_order_data_handler" );

function get_wc_order_data_handler(){
	global $post, $product;
	// Get latest 3 orders.
	$billing_city = $_GET['city'];

//	if ( wp_verify_nonce( $_REQUEST['nonce'], "empdev_payment_calculation_nonce")) {
//		exit("Unverified Nonce");
//	}

	$args = array(
//			'status' => 'completed',
//			'limit'=> 10,
//			'billing_city' => (isset($billing_city)) ? $billing_city : 'QLD',
		'date_paid' => '2018-05-14',
	);

	$orders = wc_get_orders( $args );
	//$orders_by_id = wc_get_order( 37102 );

	$orders_summary = array();

	foreach ( $orders as $order ) {

		$orders_summary[] = array(
			'order_id'        => $order->get_id(),
			'payment_method'  => $order->get_payment_method(),
			'date_paid'       => wc_format_datetime( $order->get_date_paid() ),
			'billing_address' => $order->get_address( 'billing' ),
			'state'           => $order->get_address( 'billing' )['state'],
			'discount_total' => $order->get_total_discount(),
			'sub_total' => $order->get_subtotal(),
			'total'     => $order->get_total(),
			'currency'  => $order->get_currency(),
			'value_html'      => array(
				'discount_total_html' => wc_price( $order->get_total_discount(), array( 'currency' => $order->get_currency() ) ),
				'sub_total_html'      => wc_price( $order->get_subtotal(), array( 'currency' => $order->get_currency() ) ),
				'total_html'          => wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) ),
			),

		);
	}

	$orders_summary = json_encode( $orders_summary );
	header('Content-Type: application/json');
	echo $orders_summary;

	wp_die();

	// Check if action was fired via Ajax call. If yes, JS code will be triggered, else the user is redirected to the post page
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	}
	else {
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}


}


