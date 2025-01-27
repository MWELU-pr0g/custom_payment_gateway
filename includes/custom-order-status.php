<?php
add_action('init', 'register_successful_order_status');
function register_successful_order_status() {
    register_post_status('wc-successful', array(
        'label'                     => 'Successful',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Successful <span class="count">(%s)</span>', 'Successful <span class="count">(%s)</span>')
    ));
}

add_filter('wc_order_statuses', 'add_successful_to_order_statuses');
function add_successful_to_order_statuses($order_statuses) {
    $order_statuses['wc-successful'] = 'Successful';
    return $order_statuses;
}

add_filter( 'bulk_actions-edit-shop_order', 'rudr_register_bulk_action' );
// HPOS orders
add_filter( 'bulk_actions-woocommerce_page_wc-orders', 'rudr_register_bulk_action' );

function rudr_register_bulk_action( $bulk_actions ) {

	$bulk_actions[ 'mark_successful' ] = 'Change status to successful'; // <option value="mark_awaiting_shipping">Change status to awaiting shipping</option>
	return $bulk_actions;

}

add_action( 'handle_bulk_actions-edit-shop_order', 'rudr_bulk_process_custom_status', 20, 3 );
add_action( 'handle_bulk_actions-woocommerce_page_wc-orders', 'rudr_bulk_process_custom_status', 20, 3 );

function rudr_bulk_process_custom_status( $redirect, $doaction, $object_ids ) {

	if( 'mark_successful' === $doaction ) {

		// change status of every selected order
		foreach ( $object_ids as $order_id ) {
			$order = wc_get_order( $order_id );
			$order->update_status( 'wc-successful' );
		}

		// do not forget to add query args to URL because we will show notices later
		$redirect = add_query_arg(
			array(
				'bulk_action' => 'marked_successful',
				'changed' => count( $object_ids ),
			),
			$redirect
		);

	}

	return $redirect;

}

?>