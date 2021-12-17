<?php
/**
 * @author  Okhtay Sattari <okhsat@gmail.com> <www.okhtay.name>
 * @package Basic Shopping
 */

ob_get_clean();
ob_start();

try {
    session_start();
    error_reporting(E_ALL);
    date_default_timezone_set('Etc/GMT-3');
    
    $url_protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $url          = $url_protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url_base     = $url_protocol . '://' . $_SERVER['HTTP_HOST'];
    $url_parts    = parse_url($url);
    $url_path     = $url_parts['path'];
    $url_path     = trim($url_path, '/ ');
    
    define('BASE_URL',    $url_base);
    define('PUBLIC_PATH', __DIR__);
    define('BASE_PATH',   __DIR__);
    define('APP_PATH',    BASE_PATH . '/app');
    define('URL_PATH',    $url_path);
    
    include_once APP_PATH.'/services.php';
    include_once APP_PATH.'/controller.php';
    
    $controller = new Controller();
    $action     = substr(URL_PATH, 0, 7) == 'action/'          ? explode('/', substr(URL_PATH, 7))[0] : 'index';
    $action     = method_exists($controller, $action.'Action') ? $action                              : 'index';
        
    $controller->{$action.'Action'}();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    //echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
?>