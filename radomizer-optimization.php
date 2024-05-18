/*this is in pn_functions at abput line 140*/

function function wc_lottery_generate_random_ticket_numbers( $product_id, $qty, $reserve = true, $order_id = null ) {
    $available_tickets = wc_lotery_get_available_ticket( $product_id );

    if ( empty( $available_tickets ) || count( $available_tickets ) < $qty ) {
        return false;
    }

    // Shuffle the available tickets and pick the first $qty elements
    shuffle($available_tickets);
    $random_tickets = array_slice($available_tickets, 0, $qty);

    $random_tickets = apply_filters( 'wc_lottery_generate_random_ticket_numbers', $random_tickets, $product_id, $qty );

    if ( ! empty( $random_tickets ) && $reserve === true ) {
        if ( WC()->session ) {
            $session_key = WC()->session->get_customer_id();
        } else {
            $session_key = get_current_user_id();
        }
        wc_lottery_reserve_ticket($product_id, $random_tickets, $session_key, $order_id);
    }

    if ( empty( $random_tickets ) ) {
        return false;
    }
    
    return apply_filters( 'wc_lottery_generate_random_ticket_numbers_return', $random_tickets, $product_id, $qty );
}
( $product_id, $qty, $reserve = true, $order_id = null ) {
    $available_tickets = wc_lotery_get_available_ticket( $product_id );

    if ( empty( $available_tickets ) || count( $available_tickets ) < $qty ) {
        return false;
    }

    // Shuffle the available tickets and pick the first $qty elements
    shuffle($available_tickets);
    $random_tickets = array_slice($available_tickets, 0, $qty);

    $random_tickets = apply_filters( 'wc_lottery_generate_random_ticket_numbers', $random_tickets, $product_id, $qty );

    if ( ! empty( $random_tickets ) && $reserve === true ) {
        if ( WC()->session ) {
            $session_key = WC()->session->get_customer_id();
        } else {
            $session_key = get_current_user_id();
        }
        wc_lottery_reserve_ticket($product_id, $random_tickets, $session_key, $order_id);
    }

    if ( empty( $random_tickets ) ) {
        return false;
    }
    
    return apply_filters( 'wc_lottery_generate_random_ticket_numbers_return', $random_tickets, $product_id, $qty );
}
