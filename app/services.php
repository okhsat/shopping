<?php
/**
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */

$config = include_once APP_PATH.'/config.php';
$routes = include_once APP_PATH.'/routes.php';

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$db = new \PDO(strtolower($config['database']['adapter'])
     .':dbname='.$config['database']['dbname']
     .';host='.$config['database']['host'], 
     $config['database']['username'], $config['database']['password']);

spl_autoload_register(function ($class_name) use($config) {
    $file_name = basename(str_replace('\\', '/', $class_name));
    
    if ( is_file($config['application']['libraryDir'] . $file_name . '.php') ) {
        include_once $config['application']['libraryDir'] . $file_name . '.php';
        
    } else if ( is_file($config['application']['modelsDir'] . $file_name . '.php') ) {
        include_once $config['application']['modelsDir'] . $file_name . '.php';
        
    } else {
        throw new Exception('Unable to load '.$class_name);
    }
});
    

