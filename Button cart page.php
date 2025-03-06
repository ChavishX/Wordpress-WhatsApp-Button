<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001 );
function theme_enqueue_styles() {
	if (function_exists('etheme_child_styles')){
		etheme_child_styles();
	}
}

// Add WhatsApp button to the cart page
add_action('woocommerce_proceed_to_checkout', 'add_whatsapp_button_to_cart');

function add_whatsapp_button_to_cart() {
    // WhatsApp number in international format
    $whatsapp_number = '+94777887881';

    // Get all cart items
    $cart_items = WC()->cart->get_cart();
    $message = "Hi, I would like to order the following items:\n\n";

    foreach ($cart_items as $cart_item) {
        $product = $cart_item['data'];
        $product_name = $product->get_name();
        $quantity = $cart_item['quantity'];

        // Check for variations
        $product_variation = '';
        if ($product->is_type('variation')) {
            $variation_data = $product->get_attributes();
            foreach ($variation_data as $attribute_name => $attribute_value) {
                // Remove "Pa_" part and format the attribute name
                $formatted_attribute_name = ucfirst(str_replace('pa_', '', $attribute_name));
                $product_variation .= $formatted_attribute_name . ': ' . $attribute_value . '; ';
            }
            // Remove the trailing "; "
            $product_variation = rtrim($product_variation, '; ');
        }

        // Add product details to the message
        $message .= "Product Name: $product_name\n";
        if (!empty($product_variation)) {
            $message .= "Variant: $product_variation\n";
        }
        $message .= "Quantity: $quantity\n\n";
    }
	
    // Get the order total amount without HTML and decode the currency symbol
    $order_total = html_entity_decode(strip_tags(wc_price(WC()->cart->total)));
    $message .= "Total Order Amount: $order_total\n";

    $encoded_message = urlencode($message);

    // Output the WhatsApp button with responsive styling
    echo '<style>
        .whatsapp-button-cart {
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            padding: 10px 20px; 
            background-color: #25D366; 
            color: white; 
            text-decoration: none; 
            border-radius: 0px; 
            font-size: 14px; 
            font-weight: bold;
            width: 100%;
			margin-bottom: 10px;
        }
        
        .whatsapp-button-cart img {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }

        /* Mobile View */
        @media (max-width: 768px) {
            .whatsapp-button-cart {
                margin-bottom: 10px;
                display: flex;
                width: 100%;
                padding: 7px;
                font-size: 14px;
                font-weight: bold;
                text-align: center;
            }
        }
    </style>';

    echo '<div style="margin-top: 10px;">
        <a href="https://wa.me/' . $whatsapp_number . '?text=' . $encoded_message . '" target="_blank" class="whatsapp-button-cart">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
            <span class="whatsapp-text">Order on WhatsApp</span>
        </a>
    </div>';

    
}
