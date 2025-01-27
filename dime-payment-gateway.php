<?php

/**
 * Pay Gateway
 *
 * @author            Mwelu Mutinda
 * @copyright         2025 mwelu
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Dime Payment Gateway
 * Description:       This is a simple payment gateway called Pay gateway.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      6.2
 * Author:            Mwelu Mutinda
 * Text Domain:       dime-payment-gateway
 * License:           GPL v2 or later
 */

require_once plugin_dir_path( __FILE__ ) . '/includes/custom-product.php';
require_once plugin_dir_path( __FILE__ ) . '/includes/custom-order-status.php';
require_once plugin_dir_path( __FILE__ ) . '/includes/shoehaven-courses.php';

if (!defined('ABSPATH')) {
    exit; 
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

// Add the custom payment gateway to WooCommerce
add_filter('woocommerce_payment_gateways', 'add_to_woo_dime_payment_gateway');
function add_to_woo_dime_payment_gateway($gateways)
{
    $gateways[] = 'WC_Dime_Payment_Gateway';
    return $gateways;
}


// Initialize the custom payment gateway class
add_action('plugins_loaded', 'init_dime_payment_gateway');
function init_dime_payment_gateway()
{
    if (!class_exists('WC_Payment_Gateway')) {
        
        return;
    }

    class WC_Dime_Payment_Gateway extends WC_Payment_Gateway
    {
        public function __construct()
        {
            $this->id = 'dime_payment_gateway';
            $this->method_title = __('Dime payment Gateway', 'dime-payment-gateway');
            $this->method_description = __('This Dime payment gateway.', 'dime-payment-gateway');
            $this->has_fields = false;
			$this->icon = 'https://img.icons8.com/fluency-systems-filled/50/pay.png';

            // Load the settings
            $this->init_form_fields();
            $this->init_settings();

            // Define user settings
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');

            add_action('init', [$this, 'register_successful_order_status']);
            add_filter( 'woocommerce_payment_complete_order_status', array( $this, 'change_payment_complete_order_status' ), 10, 3 );
            
            // Save admin options
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        }

        // Define admin form fields
        public function init_form_fields()
        {
            $this->form_fields = [
                'enabled' => [
                    'title' => __('Enable/Disable', 'dime-payment-gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enable Dime Payment Gateway', 'dime-payment-gateway'),
                    'default' => 'yes'
                ],
                'title' => [
                    'title' => __(' Dime payment gateway', 'dime-payment-gateway'),
                    'type' => 'text',
                    'description' => __('This controls the title displayed to the user during checkout.', 'dime-payment-gateway'),
                   'default' => __('Dime Payment Gateway', 'dime-payment-gateway'),
					'desc_tip' => true
                ],
                'description' => [
                    'title' => __('Description', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('This controls the description displayed to the user during checkout.', 'dime_payment_gateway'),
                    'default' => __('Make your order using "Dime payment gateway".', 'dime_payment_gateway'),
                ]
            ];
        }

        public function change_payment_complete_order_status( $status, $order_id = 0, $order = false ) {
		if ( $order && 'dime-payment-gateway' === $order->get_payment_method() ) {
			$status = 'Successful';
		}
		return $status;
	}

        //processing payment function
        public function process_payment($order_id)
        {
            $order = wc_get_order($order_id);
			
            // Simulate success or failure
            if (rand(0, 1)) {
                // Payment success
                
				
                error_log('Order Status: ' . $order->get_status());
                $order->payment_complete();
                $order->update_status( 'successful',  __( 'Dime payment 2', 'woocommerce') );
				
                $order->add_order_note(__('Payment is successfully processed by Dime payment.', 'woocommerce'));
                wc_add_notice(__('Payment is successfully processed with Dime payment Gateway.', 'dime_payment_gateway'), 'success'); 
				
             
				// 	$order->reduce_order_stock();
				WC()->cart->empty_cart();
                return [
                    'result' => 'success',
                    'redirect' => $this->get_return_url($order),
                ];
            } else {
                    
                // Payment failure
                $order->update_status('failed', __('Payment failed via payment Gateway.', 'woocommerce'));
                wc_add_notice(__('Sorry,payment failed . Please try again.', 'dime_payment_gateway'), 'error');
                return [
                    'result' => 'failure',
                ];
            }
        }
        
    }
}



?>