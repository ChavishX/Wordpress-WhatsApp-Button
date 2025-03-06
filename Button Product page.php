<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 1001 );
function theme_enqueue_styles() {
	if (function_exists('etheme_child_styles')){
		etheme_child_styles();
	}
}

// Add WhatsApp button to product pages
add_action('woocommerce_after_shop_loop_item', 'add_whatsapp_button_to_products', 15);

function add_whatsapp_button_to_products() {
    global $product;

    // WhatsApp number
    $whatsapp_number = '+94777887881';

    // Get product name
    $product_name = $product->get_name();

    // Get product variant details (if applicable)
    $product_variant = get_product_variant_by_id($product->get_id());

    // Encode message for WhatsApp
    $message = urlencode("Hi, I’m interested in this product:\n\nProduct Name: $product_name\n$product_variant");

    // Output the WhatsApp button with responsive styling
    echo '<style>
        .whatsapp-button {
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            padding: 10px 20px; 
            background-color: #25D366; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            font-size: 14px; 
            font-weight: bold;
        }
        
        .whatsapp-button img {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }

        /* Mobile View */
        @media (max-width: 768px) {
            .whatsapp-button {
				margin:auto;
                display: flex;
                width: 96%;
                padding: 5px;
                font-size: 11px;
                font-weight: bold;
                text-align: center;
            }
        }
    </style>';

    echo '<div style="margin-top: 10px;">
        <a href="https://wa.me/' . $whatsapp_number . '?text=' . $message . '" target="_blank" class="whatsapp-button">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
            <span class="whatsapp-text">Get more information</span>
        </a>
    </div>';


}

// Function to get product variant details by product ID
function get_product_variant_by_id($product_id) {
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return '';
    }

    // Check if it's a variation product
    if ($product->is_type('variation')) {
        $attributes = $product->get_attributes();
        $variant_details = '';

        foreach ($attributes as $key => $value) {
            $formatted_key = ucwords(str_replace('pa_', '', $key)); // Remove "pa_" prefix
            $variant_details .= "$formatted_key: $value, ";
        }

        return rtrim($variant_details, ', ');
    }

    return ''; // Return empty if not a variation
}