<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$envFile = __DIR__ . '/.env.bootstrap.php';
if (file_exists($envFile)) {
    require_once $envFile;
}

$requiredConstans = [
    'PHPUNIT_PDO_DATABASE' => 'fias',
    'PHPUNIT_PDO_DSN' => 'mysql:host=localhost;dbname=fias;charset=UTF8',
    'PHPUNIT_PDO_USER' => 'travis',
    'PHPUNIT_PDO_PASSWORD' => '',
    'PHPUNIT_PDO_ATTRIBUTES' => [
        PDO::MYSQL_ATTR_LOCAL_INFILE => true,
    ],
];
foreach ($requiredConstans as $constantName => $constantDefaultValue) {
    if (!defined($constantName)) {
        define($constantName, $constantDefaultValue);
    }
}

$pdo = new PDO(PHPUNIT_PDO_DSN, PHPUNIT_PDO_USER, PHPUNIT_PDO_PASSWORD);
$pdo->exec('DROP DATABASE IF EXISTS ' . PHPUNIT_PDO_DATABASE);
$pdo->exec('CREATE DATABASE ' . PHPUNIT_PDO_DATABASE . ' CHARACTER SET utf8 COLLATE utf8_unicode_ci');
