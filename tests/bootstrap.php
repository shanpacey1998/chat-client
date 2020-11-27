<?php

use Symfony\Component\Dotenv\Dotenv;

<<<<<<< HEAD
require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->loadEnv(dirname(__DIR__).'/.env.test');
=======
require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->loadEnv(dirname(__DIR__) . '/.env.test');
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
}
