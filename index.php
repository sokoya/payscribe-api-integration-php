<?php

require_once 'helpers.php';

// Load environment variables
loadEnv();

// Example to create a customer, if customer not already existing
$customer_response = createCustomer('John', 'Doe', '2347038067493', 'john.doe@example.com', 'NG');

if( $customer_response['status'] ){

    if( $customer_id = $customer_response['message']['details']['customer_id'] ){
        
        // Example to create a virtual account
        $virtual_account_response = createVirtualAccount('static', 'NGN', $customer_id, ['9psb']);

        echo "Customer ID: " . $customer_id . "<br>";
        echo "Virtual Account Response: <br>";

        // Save customer_id and account.
        print_r($virtual_account_response);
    }
}else{
    // print_r( $customer_response );
    echo "Error creating customer";
}

?>
