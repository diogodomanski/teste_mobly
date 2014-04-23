<?php

/**
 * Copyright (c) 2013 Will Hattingh (https://github.com/Nitecon
 *
 * For the full copyright and license information, please view
 * the file LICENSE.txt that was distributed with this source code.
 * 
 * @author Will Hattingh <w.hattingh@nitecon.com>
 *
 * 
 */
return array(
    'zf2-db-session'=>array(
        'sessionConfig' => array(
            'cache_expire' => 86400,
            //'cookie_domain' => 'localhost',
            'name' => 'teste_mobly',
            'cookie_lifetime' => 1800,
            'gc_maxlifetime' => 1800,
            'cookie_path' => '/',
            'cookie_secure' => false,
            'remember_me_seconds' => 3600,
            'use_cookies' => true,
        )
    ),
	'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=teste_mobly;host=localhost',
        'username' => 'root',
        'password' => '',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            'buffer_results' => true
        ),
    )
);
