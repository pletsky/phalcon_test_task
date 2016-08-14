<?php
include ('../functions/debug.php');

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
//use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Db\Adapter\Pdo\Postgresql as DbAdapter;

try {

    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/forms/',
        '../app/models/'
    ))->register();

    // Create a DI
    $di = new FactoryDefault();

    // Setup the database service
    //postgresql
    $di->set('db', function () {
        return new DbAdapter(array(
            "host"     => "localhost",
            "username" => "postgres",
            "password" => "postgres",
            "dbname"   => "test"
        ));
    });
/*
    //mysql
    $di->set('db', function () {
        return new DbAdapter(array(
            "host"     => "localhost:3366",
            "username" => "root",
            "password" => "rooter",
            "dbname"   => "sos_credit_test_task"
        ));
    });
*/
    // Setup the view component
    $di->set('view', function () {
        $view = new View();
        $view->setViewsDir('../app/views/');
        return $view;
    });

    // Setup a base URI
    $di->set('url', function () {
        $url = new UrlProvider();
        $url->setBaseUri('/');
        return $url;
    });

    $application = new Application($di);
    // Handle the request
    $response = $application->handle();
    $response->send();

} catch (\Exception $e) {
     echo "Exception: ", $e->getMessage();
}
