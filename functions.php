add_action('woocommerce_before_add_to_cart_button', 'custom_ticket_options');

function custom_ticket_options() {
    global $product;

    if (!$product) {
        echo '<script type="text/javascript">console.log("Product is not set");</script>';
        return;
    }

    if (is_product()) {
        $product_type = $product->get_type();
        // Log the product type
        echo '<script type="text/javascript">console.log("Product type: ' . $product_type . '");</script>';
        
        if ($product_type == 'lottery') {
            ?>
            <style>
                .quantity { display: none !important; }
            </style>
            <div class="custom-ticket-options">
                <label for="ticket_quantity">Επιλέξτε αριθμό λαχνών:</label>
                <select id="ticket_quantity" name="ticket_quantity">
                    <option value="1" data-price="2.99">1 Λάχνο - €2.99</option>
                    <option value="5" data-price="9.99">5 Λαχνούς - €9.99</option>
                    <option value="20" data-price="24.99">20 Λαχνούς - €24.99</option>
                    <option value="50" data-price="49.99">50 Λαχνούς - €49.99</option>
                </select>
            </div>
            <input type="hidden" id="custom_price" name="custom_price" value="">
            <input type="hidden" id="custom_quantity" name="custom_quantity" value="">
            <script type="text/javascript">
                console.log('Inside lottery product block');
                jQuery(document).ready(function($) {
                    console.log('Inside jQuery document ready');
                    var $ticketQuantity = $('#ticket_quantity');
                    console.log('Ticket quantity element found:', $ticketQuantity.length > 0);
                    if ($ticketQuantity.length > 0) {
                        $ticketQuantity.change(function() {
                            console.log('Inside ticket quantity change function');
                            var selectedOption = $(this).find('option:selected');
                            var price = selectedOption.data('price');
                            var quantity = selectedOption.val();
                            console.log('Selected option quantity:', quantity);
                            console.log('Selected option price:', price);
                            $('#custom_price').val(price); // Hidden input for the custom price
                            $('#custom_quantity').val(quantity); // Hidden input for the custom quantity
                        }).change(); // Trigger change to set the initial value
                    } else {
                        console.log('Error: Ticket quantity element not found');
                    }
                });
            </script>
            <?php
        }
    }
}

add_filter('woocommerce_add_cart_item_data', 'add_custom_price_to_cart_item', 10, 2);

function add_custom_price_to_cart_item($cart_item_data, $product_id) {
    if (isset($_POST['custom_price']) && isset($_POST['custom_quantity'])) {
        $cart_item_data['custom_price'] = $_POST['custom_price'];
        $cart_item_data['custom_quantity'] = $_POST['custom_quantity'];
        // Override the quantity
        $cart_item_data['quantity'] = $_POST['custom_quantity'];
        WC()->session->set('custom_quantity', $_POST['custom_quantity']);
    }
    return $cart_item_data;
}

add_action('woocommerce_before_calculate_totals', 'update_custom_price', 10, 1);

function update_custom_price($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        if (isset($cart_item['custom_price'])) {
            $custom_price = $cart_item['custom_price'];
            $custom_quantity = $cart_item['custom_quantity'];
            // Log the custom price and quantity
            echo '<script type="text/javascript">console.log("Updating cart item:", "Price: ' . $custom_price . '", "Quantity: ' . $custom_quantity . '");</script>';
            // Set the price directly and prevent it from being multiplied by the quantity
            $cart_item['data']->set_price($custom_price / $custom_quantity);

            // Update the quantity
            WC()->cart->set_quantity($cart_item_key, $custom_quantity, false);
        }
    }
}

add_action('woocommerce_checkout_create_order_line_item', 'add_custom_data_to_order_items', 10, 4);

function add_custom_data_to_order_items($item, $cart_item_key, $values, $order) {
    if (isset($values['custom_price'])) {
        $item->add_meta_data('Custom Price', $values['custom_price']);
    }
    if (isset($values['custom_quantity'])) {
        $item->add_meta_data('Custom Quantity', $values['custom_quantity']);
    }
}


