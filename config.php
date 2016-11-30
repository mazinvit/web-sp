<?php
    define('DB_HOST', '127.0.0.1');
    define('DB_DATABASE', 'konference');
    define('DB_USER', 'root');
    define('DB_PASS', 'heslo');
    define('DB_CHARSET', 'utf8');

    require_once ROOT.'twig-master/lib/Twig/Autoloader.php';
    require_once ROOT.'models/Model.php';
    require_once ROOT.'models/UserModel.php';