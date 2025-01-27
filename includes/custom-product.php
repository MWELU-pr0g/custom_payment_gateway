<?php
// Add custom text field to WooCommerce products
add_action('woocommerce_product_options_general_product_data', 'add_custom_product_field');
function add_custom_product_field()
{
    woocommerce_wp_text_input([
        'id' => '_custom_text_field',
        'label' => __('Custom Text Field', 'woocommerce'),
        'description' => __('Enter a custom text value for this product.', 'woocommerce'),
        'desc_tip' => true,
    ]);
}

// Save the custom text field value
add_action('woocommerce_process_product_meta', 'save_custom_product_field');
function save_custom_product_field($post_id)
{
    $custom_field_value = isset($_POST['_custom_text_field']) ? sanitize_text_field($_POST['_custom_text_field']) : '';
    update_post_meta($post_id, '_custom_text_field', $custom_field_value);
}

// Display the custom text field value on the product page
add_action('woocommerce_single_product_summary', 'display_custom_product_field', 25);
function display_custom_product_field()
{
    global $product;
    $custom_field_value = get_post_meta($product->get_id(), '_custom_text_field', true);

    if (!empty($custom_field_value)) {
        echo '<p class="custom-text-field">' . esc_html($custom_field_value) . '</p>';
    }
}
?>