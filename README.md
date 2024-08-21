# Payscribe API Integration PHP

Welcome to the Payscribe API Integration project! This project demonstrates how to interact with the Payscribe API for creating virtual accounts, managing customers, and handling webhooks. 

## Table of Contents

- [Features](#features)
- [Setup](#setup)
- [Usage](#usage)
- [Webhook Handling](#webhook-handling)
- [License](#license)

## Features

- **Create Customers**: Easily create new customers in the Payscribe system.
- **Create Virtual Accounts**: Generate virtual accounts for transactions.
- **Manage Accounts**: Activate or deactivate virtual accounts.
- **Retrieve Customer Details**: Fetch details of a specific customer.
- **Webhook Handling**: Handle incoming webhook notifications for payment events.

## Setup

### Prerequisites

- PHP 7.4 or higher
- Composer (optional, for managing dependencies)

### Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/your-username/payscribe-api-integration.git
   cd payscribe-api-integration

### Create .env file  
PAYSCRIBE_BASE_URL=https://sandbox.payscribe.ng/api/v1/
PAYSCRIBE_PK_KEY=YOUR_AUTHORIZATION_BEARER_TOKEN
PAYSCRIBE_SK_KEY=YOUR_AUTHORIZATION_BEARER_TOKEN

Replace YOUR_AUTHORIZATION_BEARER_TOKEN with your actual Payscribe authorization key.

### Configure Your Server
Make sure your PHP server is configured to serve files from the project directory. You can use a local server like PHP’s built-in server for testing:
php -S localhost:8000


### Creating a Customer
To create a customer, include the helper.php file and use the createCustomer function in index.php:
```php
$customer_response = createCustomer('John', 'Doe', '2347038067493', 'john.doe@example.com', 'NG');
```

### Creating a Virtual Account
To create a virtual account, use the createVirtualAccount function:
```php
$virtual_account_response = createVirtualAccount('static', 'NGN', $customer_id, ['9psb']);
```

### Getting Customer Details
Retrieve customer details using the getCustomerDetails function:
```php
$customer_details = getCustomerDetails($customer_id);
```
### Activating and Deactivating Accounts
To activate or deactivate an account, use the activateAccount and deactivateAccount functions:
```php
activateAccount($account_id);
deactivateAccount($account_id);
```

### Webhook Handling
To handle incoming webhook notifications:

### Configure Webhook URL

Set up your webhook URL with Payscribe to point to webhook.php.

### Validate Incoming Requests

The script will check the IP address of incoming requests against a list of allowed IPs Please check the Payscribe API doc for server IP

### License
This project is licensed under the MIT License. See the LICENSE file for details.

Feel free to contribute to this project by submitting issues or pull requests. For any questions, reach out to hello@payscribe.ng

Happy coding!
