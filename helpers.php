<?php

/*
* Load helper function into the ENV variable
*/
function loadEnv($path = '.env') {
    if (!file_exists($path)) {
        throw new Exception("The .env file is missing.");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}


/**
 * curlRequest 
 * @params: 
 * url - string,
 * data - array,
 * key - account key
*/

function curlRequest($url, $data = [], $auth_key, $method = 'POST') {
    $ch = curl_init();

    switch ($method) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case 'GET':
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            break;
        case 'PUT':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        default:
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        "Authorization: Bearer $auth_key"
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === FALSE) {
        die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
    }

    return json_decode($response, true);
}

/**
 * Create customer
 * 
 */
function createCustomer($first_name, $last_name, $phone, $email, $country = 'NG') {
    $base_url = $_ENV['PAYSCRIBE_BASE_URL'];
    $auth_key = $_ENV['PAYSCRIBE_PK_KEY'];
    $url = $base_url . 'customers/create';

    // do some validation here...

    $data = array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'phone' => $phone,
        'email' => $email,
        'country' => $country
    );

    return curlRequest($url, $data, $auth_key);
}


/**
 * Create virtual account
 */
function createVirtualAccount($account_type, $currency, $customer_id, $bank) {
    $base_url = $_ENV['PAYSCRIBE_BASE_URL'];
    $auth_key = $_ENV['PAYSCRIBE_PK_KEY'];
    $url = $base_url . 'collections/virtual-accounts/create';

    // do some validation here...

    $data = array(
        'account_type' => $account_type,
        'currency' => $currency,
        'customer_id' => $customer_id,
        'bank' => $bank
    );

    return curlRequest($url, $data, $auth_key);
}


/**
 * Get customer details
 */
function getCustomerDetails($customer_id) {
    $base_url = $_ENV['PAYSCRIBE_BASE_URL'];
    $auth_key = $_ENV['PAYSCRIBE_PK_KEY'];
    $url = $base_url . "customers/$customer_id/details";

    return curlRequest($url, null, $auth_key, "GET");
}

/**
 * Deactivate virtual account
 */

function deactivateAccount( $account ) {
    $base_url = $_ENV['PAYSCRIBE_BASE_URL'];
    $auth_key = $_ENV['PAYSCRIBE_PK_KEY'];
    $url = $base_url . "collections/virtual-accounts/deactivate";

    $data = [
        'account' => $account
    ];

    return curlRequest($url, $data, $auth_key);
}

/**
 * Activate virtual account
 */
function activateAccount($customer_id) {
    $base_url = $_ENV['PAYSCRIBE_BASE_URL'];
    $auth_key = $_ENV['PAYSCRIBE_PK_KEY'];
    $url = $base_url . "collections/virtual-accounts/activate";

    $data = [
        'account' => $account
    ];

    return curlRequest($url, $data, $auth_key);
}