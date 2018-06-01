<?php
add_filter( 'woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text' );
function custom_add_to_cart_text() {
  return __( 'Enroll', 'nationwide-educators' );
}

add_filter( 'woocommerce_pay_order_button_text', 'custom_order_button_text' );
function custom_order_button_text() {
  return __ ( 'SOME ORDER BUTTON', 'nationwide-educators' );
}

function woocommerce_button_proceed_to_checkout() {
  $checkout_url = WC()->cart->get_checkout_url(); ?>
  <a href="<?php echo esc_url( wc_get_checkout_url() );?>" class="checkout-button button alt wc-forward">
  <?php esc_html_e( 'Purchase Courses', 'woocommerce' ); ?>
  </a>
  <?php
 }