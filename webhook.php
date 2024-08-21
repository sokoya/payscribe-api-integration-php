<?php

// Load the helper functions
require_once 'helpers.php';

// Load environment variables
loadEnv();

// Capture the incoming payload
$payload = file_get_contents('php://input');

// Log the payload for debugging (optional)
file_put_contents('webhook.log', $payload . PHP_EOL, FILE_APPEND);

// check that it is coming from Payscribe
$allowed_ips = [ '162.254.34.78', ];

$remote_ip = $_SERVER['REMOTE_ADDR'];

if ( !in_array( $remote_ip, $allowed_ips ) ) {
    http_response_code(403);
    die('Forbidden: Invalid IP address');
}

// Decode the JSON payload
$data = json_decode($payload, true);

if ( isset($data['event_type']) ) {

    switch ($data['event_type']) {

        case 'accounts.payment.status':

            // extract all the incoming data
            ['trans_id' => $trans_id, 'amount' => $amount, 'fee' => $fee, 'transaction' => $transaction, 'customer' => $customer, 'transaction_hash' => $transaction_hash ] = $payload;

            ["session_id" => $session_id, "bank_name" => $bank_name, "bank_code" => $bank_code, "sender_account" => $sender_account, "sender_name" => $sender_name, "amount" => $amount ] = $transaction;

            ["id" => $customer_id, "name" => $name, "number" => $number, "bank" => $bank, "account_id" => $account_id,  "account_type" => $account_type ] = $customer;
            
            // verify the hash

            $secret_key = $_ENV['PAYSCRIBE_SK_KEY']; // Your secret jey
            $sender_account = (string) $sender_account;
            $number = (string) $number;
            $bank_code = (string) $bank_code;
            $amount = (string)number_format($amount, 2, ".", ""); // must be in 2DP eg 100 = 100.00

            // combine string
            $combination = $secret_key . $sender_account  . $number . $bank_code. $amount . $trans_id ;

            // hash the combination
            $my_hash = strtoupper(hash('sha512', $combination));

            if( $my_hash !== $transaction_hash ){
                http_response_code(403);
                echo json_encode(['status' => 'error', "message" => "Transaction hash does not match."]);
                exit;
            }

            /**
             * Check that all important data are sent and not empty
             * Check that the session_id ( unique ) is not already found in your DB, also the trans_id
             */

            // Process the payment (e.g., update database, send notification, etc.)
            // verify that the payment has not been recorded before.
            // Your code here
            break;
        default:
            // Handle unknown events
            break;
    }
}

// Respond to the webhook (optional)
http_response_code(200);
echo json_encode(['status' => 'success']);

?>
