<?php
 
/*
 Plugin Name: Quform WooCommerce Integration
 Description: Select a product inside a form.
 Author: Designium.ca
 Author URI: http://www.designium.ca/
 Version: 1.0
 */
 
 
 
 
 
 
// The form will appear in: /my account/order list

add_action('woocommerce_my_account_my_orders_column_order-actions', function ($order) {
    $actions = wc_get_account_orders_actions( $order );

    if ( ! empty( $actions ) ) {
        foreach ( $actions as $key => $action ) {
            echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
        }
    }

    echo '<a class="button form">' . do_shortcode('[quform_popup id="1" values="order_id=' . $order->get_order_number() . '"]Formulaire inscription[/quform_popup]') . '</a>';
});
