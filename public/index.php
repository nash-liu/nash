<?php

define('WEB_DIR', realpath('..'));

define('DS', DIRECTORY_SEPARATOR);

spl_autoload_register(
    function ($class_name) {
        $location_path = WEB_DIR . DS . strtr($class_name, array('/' => DS, '\\' => DS)) . '.php';
        if (is_file($location_path)) {
            require $location_path;
        } else {
            throw new system\lib\Error("找不到指定的类定义'{$class_name}'");
        }
    }
);


try {

    $di = new system\Di;

    $di->share('config', function () {
        $config = new system\lib\Config(WEB_DIR . DS . 'public' . DS . 'conf.ini');
        return $config;
    });

    $di->share('uri', function () {
        $uri = new system\lib\Uri;
        return $uri;
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

    $di->set('view', function () {
        $view = new system\mvc\View;
        return $view;
    });

    $di->share('db', function () {
        $db = new system\lib\Database;
        return $db;
    });

    $di->run();

} catch (system\lib\Error $e) {
    echo $e->error_res();
}
