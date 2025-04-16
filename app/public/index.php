<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

//try {
//    $pdo = new PDO('mysql:host=database;port=3306;dbname=lastmily', 'root', 'root');
//    echo "Connection OK\n";
//} catch (PDOException $e) {
//    echo "Connection failed: " . $e->getMessage() . "\n";
//}
//
//die();
return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
