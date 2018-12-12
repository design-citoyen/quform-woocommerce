<?php
 
/*
 * Plugin Name: Quform WooCommerce Integration
 * Description: Select a product inside a form.
 * Version: 1.0
 */
 
// Form Sync

function my_quform_woocomm_products($form)
{
    $dropdown = $form->getElement('iphorm_1_1');

    if ( ! $dropdown instanceof iPhorm_Element_Select) {
        return;
    }

    $options = array();
    $query = new WP_Query(array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'camp-meeting',
            )
        )
    ));

    while ($query->have_posts()) {
        $query->the_post();
        $options[] = array(
            'label' => get_the_title(),
            'value' => get_the_ID()
        );
    }

    wp_reset_postdata();

    $dropdown->setOptions($options);
}
add_action('iphorm_pre_display_2', 'my_quform_woocomm_products');


// Payment code

function my_quform_create_order($form)
{
	if ( ! function_exists('wc_create_order')) {
		return;
	}

	$address = array(
		'first_name' => $form->getValue('iphorm_1_3'),
		'last_name' => $form->getValue('iphorm_1_3'),
		'address_1' => $form->getValue('iphorm_1_4'),
		'address_2' => $form->getValue('iphorm_1_5'),
		'city' => $form->getValue('iphorm_1_6'),
		'state' => $form->getValue('iphorm_1_7'),
		'postcode' => $form->getValue('iphorm_1_8'),
		'country' => $form->getValue('iphorm_1_9'),
		'company' => '',
		'phone' => '',
		'email' => $form->getValue('iphorm_1_11')
	);

	$order = wc_create_order();
	$order->add_product(get_product($form->getValue('iphorm_1_1')), 1);
	$order->set_address($address, 'billing');
	$order->set_address($address, 'shipping');
	$order->calculate_totals();
}
add_action('iphorm_post_process_2', 'my_quform_create_order');
