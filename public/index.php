<?php

define('WEB_DIR', realpath('..'));

define('DS', DIRECTORY_SEPARATOR);

spl_autoload_register(
    function ($class_name) {
        $location_path = WEB_DIR . DS . strtr($class_name, '\\', DS) . '.php';
        if (is_file($location_path)) {
            require $location_path;
        } else {
            throw new Exception("Can't Find Class '{$class_name}'", 1);
        }
    }
);

$di = new system\Di;

$di->share('config', function () {
    $config = new system\lib\Config(WEB_DIR . DS . 'public' . DS . 'conf.ini');
    return $config;
});

$di->share('uri', function () {
    $config = new system\lib\Uri;
    return $config;
});

$di->share('router', function () {
    $router = new system\lib\Router;
    return $router;
});

$di->share('req', function () {
    $req = new system\http\Request;
    return $req;
});

$di->share('res', function () {
    $res = new system\http\Response;
    return $res;
});

echo $di->run();
