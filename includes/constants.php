<?php
date_default_timezone_set("AFRICA/Nairobi");

    //Creating constants
    define('DBTYPE', 'PDO');
    define('HOSTNAME', '127.0.0.1');
    define('DBPORT', '3306');
    define('HOSTUSER', 'root');
    define('HOSTPASS', '');
    define('DBNAME', 'api_d');

    $protocol = isset($_SERVER['HTTPS']) && 
    $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $base_url = $protocol . $_SERVER['HTTP_HOST'] . '/';

    $conf['ver_code_time'] = date("Y-m-d H:i:s", strtotime("+ 24hours"));
    $conf['verification_code'] = rand(100000,999999);
    $conf['site_initials'] = "ICS 2024";
    $conf['site_url'] = "$base_url/". DBNAME;
