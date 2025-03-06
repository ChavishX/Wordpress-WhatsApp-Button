<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001 );
function theme_enqueue_styles() {
	if (function_exists('etheme_child_styles')){
		etheme_child_styles();
	}
}

// Add custom message to cart and checkout pages after order total
function add_delivery_charge_notice_cart() {
    echo '<tr class="order-total"><th></th><td><p style="color: red; font-weight:bold;">Delivery Fee will be added</p></td></tr>';
}

function add_delivery_charge_notice_checkout() {
    echo '<tr class="order-total"><th></th><td><p style="color: red; font-weight:bold;">Delivery Fee will be added</p></td></tr>';
}

// Hook the function to display the message on the cart page after order total
add_action('woocommerce_cart_totals_after_order_total', 'add_delivery_charge_notice_cart');

// Hook the function to display the message on the checkout page after order total
add_action('woocommerce_review_order_after_order_total', 'add_delivery_charge_notice_checkout');

function enqueue_woocommerce_ajax_scripts() {
    if (function_exists('is_woocommerce')) {
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-cart-fragments');
        wp_enqueue_script('woocommerce');
    }
}
add_action('wp_enqueue_scripts', 'enqueue_woocommerce_ajax_scripts', 20);



//Remove the Checkout Button from Mini Cart
add_action('wp_footer', function() {
    ?>
    <style>
        .button.btn-checkout.wc-forward {
            display: none !important;
        }
    </style>
    <?php
});

// Remove shipping address and shipping label from the cart page
add_filter('woocommerce_cart_totals_before_order_total', 'remove_shipping_section_from_cart');
function remove_shipping_section_from_cart() {
    if (is_cart()) {
        // Remove the shipping method
        remove_action('woocommerce_cart_shipping_method', 'woocommerce_cart_shipping_method', 10);
        // Remove the shipping address
        remove_action('woocommerce_cart_shipping_method_label', 'woocommerce_cart_shipping_method_label', 10);
    }
}

// Hide the shipping address section
add_filter('woocommerce_cart_shipping_method_full_label', '__return_empty_string');
add_action('woocommerce_cart_totals_after_order_total', 'hide_shipping_section', 10);
function hide_shipping_section() {
    if (is_cart()) {
        echo '<style>.woocommerce-shipping-totals { display: none; }</style>';
    }
}

// Style the View Cart button
add_action('wp_footer', function() {
    ?>
    <style>
        /* Adjust the selector if needed based on the actual class name */
        .buttons.mini-cart-buttons {
            background-color: red; /* Set the background color to red */
            color: white; /* Set the text color to white */
            border: 2px solid black; /* Optional: keep the red border */
        }

        /* Optional: Change the hover state */
        .buttons.mini-cart-buttons:hover {
            background-color: darkred; /* Change to a darker shade on hover */
        }
    </style>
    <?php
});


//Remove Proceed to Checkout Button in Cart Page
function remove_proceed_to_checkout_button() {
    remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
}
add_action('wp', 'remove_proceed_to_checkout_button');


