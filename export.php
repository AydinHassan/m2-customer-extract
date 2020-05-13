<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Ifsnop\Mysqldump as IMysqldump;

$settings = [
    'compress' => IMysqldump\Mysqldump::NONE,
    'skip-triggers' => true,
    'no-create-info' => true,
    'include-tables' => [
        'customer_entity',
        'customer_entity_datetime',
        'customer_entity_decimal',
        'customer_entity_int',
        'customer_entity_text',
        'customer_entity_varchar',
        'customer_address_entity',
        'customer_address_entity_datetime',
        'customer_address_entity_decimal',
        'customer_address_entity_int',
        'customer_address_entity_text',
        'customer_address_entity_varchar',
    ]
];

if (!isset($argv[1])) {
    die("Please provide customer email address as first argument\n");
}

$email = $argv[1];

$dsn = 'mysql:host=0.0.0.0;dbname=docker';
$user = 'docker';
$password = 'docker';
$pdoSettings = [
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

$db = @new PDO($dsn, $user, $password, $pdoSettings);

$customerId = $db->query("SELECT entity_id FROM customer_entity WHERE email = '{$email}'")->fetchColumn();

if (!$customerId) {
    die("Could not find customer with email: {$email}\n");
}

$dump = new IMysqldump\Mysqldump($dsn, $user, $password, $settings, $pdoSettings);
$dump->setTableWheres([
    'customer_entity' => "email = '{$email}'",
    'customer_entity_datetime' => "entity_id = {$customerId}",
    'customer_entity_decimal' => "entity_id = {$customerId}",
    'customer_entity_int' => "entity_id = {$customerId}",
    'customer_entity_text' => "entity_id = {$customerId}",
    'customer_entity_varchar' => "entity_id = {$customerId}",
    'customer_address_entity' => "parent_id = {$customerId}",
    'customer_address_entity_datetime' => "entity_id IN (SELECT entity_id FROM customer_address_entity WHERE parent_id = {$customerId})",
    'customer_address_entity_decimal' => "entity_id IN (SELECT entity_id FROM customer_address_entity WHERE parent_id = {$customerId})",
    'customer_address_entity_int' => "entity_id IN (SELECT entity_id FROM customer_address_entity WHERE parent_id = {$customerId})",
    'customer_address_entity_text' => "entity_id IN (SELECT entity_id FROM customer_address_entity WHERE parent_id = {$customerId})",
    'customer_address_entity_varchar' => "entity_id IN (SELECT entity_id FROM customer_address_entity WHERE parent_id = {$customerId})",
    'magento_customerbalance' => "customer_id = {$customerId}",
    'magento_customerbalance_history' => "magento_customerbalance_history IN (SELECT balance_id FROM magento_customerbalance = {$customerId})",
]);

$dump->start('customer.sql');
