<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://empassion.com.au
 * @since      1.0.0
 *
 * @package    Emp_Payment_Calculation
 * @subpackage Emp_Payment_Calculation/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Emp_Payment_Calculation
 * @subpackage Emp_Payment_Calculation/admin
 * @author     Empassion Dev <web@empassion.com.au>
 */
class Emp_Payment_Calculation_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
//
//		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_ajax_scripts' ) );
//
//		add_action("wp_ajax_get_wc_order_data", array($this, "get_wc_order_data" ) );
//		add_action("wp_ajax_nopriv_get_wc_order_data", array($this, "get_wc_order_data" ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Emp_Payment_Calculation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Emp_Payment_Calculation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/emp-payment-calculation-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'bootstrap-css-v4', plugin_dir_url( __FILE__ ) . 'assets/bootstrap/css/bootstrap.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'datepicker-css', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css', array(), $this->version, 'all' );


	}

	/**
	 *Register Ajax Script in Admin area
	 *
	 * @since 1.0.0
	 */
	public function enqueue_ajax_scripts(){

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'empdev-angular-js', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.min.js' );

		wp_enqueue_script( 'empdev-moment-js', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js' );

		wp_enqueue_script( 'empdev-datepicker-js', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js' );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/emp-payment-calculation-admin.js', array( 'jquery', 'empdev-angular-js', 'empdev-moment-js', 'empdev-datepicker-js' ), '1.1.8', false );

		//		$dataToBePassed = array(
//			'home'            => $this->plugin_name,
//			'pleaseWaitLabel' => __( 'Please wait...', 'default' )
//		);

//
//		$get_order_summary = array( '');
//		$dataToBePassed = array();
//
//		foreach ( $orders as $key => $value ){
//
//		}

		//$orders_summary = $this->get_wc_order_data();


		wp_localize_script( $this->plugin_name, 'empdevajax', array( 'ajaxurl' => admin_url('admin-ajax.php' ) ) );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Emp_Payment_Calculation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Emp_Payment_Calculation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_enqueue_script( 'bootstrap-js-v4', plugin_dir_url( __FILE__ ) . 'assets/bootstrap/js/bootstrap.js', array( 'jquery' ), $this->version, false );


	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */

	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		add_options_page( 'Payment Calculation', 'Payment Calculation', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */

	public function add_action_links( $links ) {
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_setup_page() {
		//$orders = $this->get_wc_order_data();
		include_once( 'partials/emp-payment-calculation-admin-display.php' );
	}

	public function get_wc_order_data(){
		global $post, $product;
		// Get latest 3 orders.
		$billing_city = $_GET['city'];
		$return_response = array();

	if ( wp_verify_nonce( $_REQUEST['nonce'], "empdev_payment_calculation_nonce")) {

		$return_response = array("error" => "Unverified Nonce");
		$return_response = json_encode( $return_response );
		echo $return_response;

		wp_die();
	}

		$args = array(
//			'status' => 'completed',
//			'limit'=> 10,
//			'billing_city' => (isset($billing_city)) ? $billing_city : 'QLD',
			'order' => 'ASC',
			'limit'=> -1,
			'date_paid' => '2018-03-14...2018-04-14',
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


	/**
	 *  Save the plugin options
	 *
	 *
	 * @since    1.0.0
	 */
	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name );
	}

}
