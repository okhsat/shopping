<?php
/**
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */

return [
    'debug'       => false,
    'application' => [
        'libraryDir' => APP_PATH . '/lib/',
        'modelsDir'  => APP_PATH . '/models/',
        'viewsDir'   => APP_PATH . '/views/'
    ],
    'database'    => [
        'adapter'  => 'Mysql',
        'host'     => '', //'127.0.0.1',
        'username' => '',
        'password' => '',
        'dbname'   => 'shopping',
        'charset'  =>'utf8',
        'options'  => [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            //\PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_STRINGIFY_FETCHES => false,
            //\PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_DEFAULT_FETCH_MODE  =>  \PDO::FETCH_ASSOC,
        ]
    ]
];
