
<?php

function redirect_to_checkout_page() {
    return get_permalink(wc_get_page_id('checkout'));
}
add_action( 'woocommerce_add_to_cart_redirect', 'redirect_to_checkout_page' );

function redirect_to_checkout_page_on_error( $passed_validation, $product_id ) {
    global $woocommerce;

    $product_cart_id = $woocommerce->cart->generate_cart_id( $product_id );
    $in_cart = $woocommerce->cart->find_product_in_cart( $product_cart_id );

    if ( $in_cart ) {
        wp_redirect( get_permalink( wc_get_page_id( 'checkout' ) ) );
        return false;
    }

    return $passed_validation;
}
add_filter( 'woocommerce_add_to_cart_validation', 'redirect_to_checkout_page_on_error' );

// Can be called from the front end with a key called "key" on the POST
// body to remove an item from the user's cart.
function remove_item_from_cart() {
    global $woocommerce;
    $woocommerce->cart->remove_cart_item($_POST['key']);
    wp_die();
}
add_action( 'wp_ajax_remove_item_from_cart', 'remove_item_from_cart' );
add_action( 'wp_ajax_nopriv_remove_item_from_cart', 'remove_item_from_cart' );

// Auto Complete all WooCommerce orders.
function custom_woocommerce_auto_complete_order( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}
add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     unset($fields['order']['order_comments']);
     unset($fields['billing']['billing_company']);
     return $fields;
}

// Don't show related products on single product pages
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

// This changes the add to cart button to enroll
add_filter('woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text');
function custom_add_to_cart_text()
{
    return __('Enroll', 'nationwide-educators');
}

function woocommerce_button_proceed_to_checkout()
{
    $checkout_url = WC()->cart->get_checkout_url();?>
  <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-button button alt wc-forward">
  <?php esc_html_e('Purchase Enrollment', 'woocommerce');?>
  </a>
  <?php
}

/**
 * This removes the ability to register in the same course multiple times along with purchasing amounts
 */
function wc_remove_all_quantity_fields($return, $product)
{
    return true;
}
add_filter('woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2);

// This changes the Continue Shopping Text
add_filter('wc_add_to_cart_message_html', 'my_changed_wc_add_to_cart_message_html', 10, 2);
function my_changed_wc_add_to_cart_message_html($message, $products)
{
    if (strpos($message, 'Continue shopping') !== false) {
        $message = str_replace("Continue shopping", "Enroll in other courses", $message);
    }
    return $message;

}
?>
