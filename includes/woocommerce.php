
<?php
// This changes the add to cart button to enroll
add_filter('woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text');
function custom_add_to_cart_text()
{
    return __('Enroll', 'nationwide-educators');
}

add_filter('woocommerce_pay_order_button_text', 'custom_order_button_text');
function custom_order_button_text()
{
    return __('SOME ORDER BUTTON', 'nationwide-educators');
}

function woocommerce_button_proceed_to_checkout()
{
    $checkout_url = WC()->cart->get_checkout_url();?>
  <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-button button alt wc-forward">
  <?php esc_html_e('Purchase Courses', 'woocommerce');?>
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
