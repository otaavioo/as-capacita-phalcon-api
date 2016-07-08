<?php
header("Access-Control-Allow-Origin: *");

use Phalcon\Config\Adapter\Ini as IniConfig;
use Phalcon\DI\FactoryDefault  as DefaultDI;
use Phalcon\Loader;

/**
 * By default, namespaces are assumed to be the same as the path.
 * This function allows us to assign namespaces to alternative folders.
 * It also puts the classes into the PSR-0 autoLoader.
 */

$loader = new Loader();
$loader->registerNamespaces(require 'namespaces.php')->register();

$composerAutoload = '../vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require $composerAutoload;

    $environmentVarsFile = __DIR__ . '/../app/configs/.env';
    $environmentVarsDir  = __DIR__ . '/../app/configs';

    if (file_exists($environmentVarsFile)) {
        $dotenv = new Dotenv\Dotenv($environmentVarsDir);
        $dotenv->load();
    }
}

require_once('bootstrap.php');

use AgenciaSys\Error\Handler as ErrorHandler;
use AgenciaSys\Exception\Handler as ExceptionHandler;
use Phalcon\Db\Adapter\Pdo\Mysql;

ErrorHandler::set();
ExceptionHandler::set();

/**
 * The DI is our direct injector.  It will store pointers to all of our services
 * and we will insert it into all of our controllers.
 * @var DefaultDI
 */
$di = new DefaultDI();

/**
 * Return array of the Collections, which define a group of routes, from
 * routes/collections.  These will be mounted into the app itself later.
 */
$di->set(
    'collections',
    function () {
        return include('./routes/routeLoader.php');
    }
);

/**
 * db.
 */
$di->set(
    'db',
    new Mysql(
        [
            'host'     => 'localhost',
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
            'dbname'   => getenv('DB_SCHEMA'),
            'charset'  => 'utf8',
        ]
    )
);

/**
 * If our request contains a body, it has to be valid JSON.  This parses the
 * body into a standard Object and makes that vailable from the DI.  If this service
 * is called from a function, and the request body is nto valid JSON or is empty,
 * the program will throw an Exception.
 */
$di->setShared(
    'requestBody',
    function () {
        $in = file_get_contents('php://input');
        $in = json_decode($in, false);

        // JSON body could not be parsed, throw exception
        if ($in === null) {
            throw new HTTPException(
                'There was a problem understanding the data sent to the server by the application.',
                409,
                [
                    'dev'          => 'The JSON body sent to the server was unable to be parsed.',
                    'internalCode' => 'REQ1000',
                    'more'         => ''
                ]
            );
        }

        return $in;
    }
);

/**
 * Out application is a Micro application, so we mush explicitly define all the routes.
 * For APIs, this is ideal.  This is as opposed to the more robust MVC Application
 * @var $app
 */
$app = new Phalcon\Mvc\Micro();
$app->setDI($di);

/**
 * Before every request, make sure user is authenticated.
 * Returning true in this function resumes normal routing.
 * Returning false stops any route from executing.
 */
$app->before(
    function () use ($app, $di) {
        $app->setDI($di);
    }
);

/**
 * Mount all of the collections, which makes the routes active.
 */
foreach ($di->get('collections') as $collection) {
    $app->mount($collection);
}

/**
 * The base route return the list of defined routes for the application.
 * This is not strictly REST compliant, but it helps to base API documentation off of.
 * By calling this, you can quickly see a list of all routes and their methods.
 */
$app->get(
    '/',
    function () use ($app) {
        $routes = $app->getRouter()->getRoutes();
        $routeDefinitions = [
            'GET'     => [],
            'POST'    => [],
            'PUT'     => [],
            'PATCH'   => [],
            'DELETE'  => [],
            'HEAD'    => [],
            'OPTIONS' => [],
        ];

        foreach ($routes as $route) {
            $method = $route->getHttpMethods();
            $routeDefinitions[$method][] = $route->getPattern();
        }

        return $routeDefinitions;
    }
);

/**
 * After a route is run, usually when its Controller returns a final value,
 * the application runs the following function which actually sends the response to the client.
 *
 * The default behavior is to send the Controller's returned value to the client as JSON.
 * However, by parsing the request querystring's 'type' paramter, it is easy to install
 * different response type handlers.  Below is an alternate csv handler.
 */
$app->after(
    function () use ($app) {
        // OPTIONS have no body, send the headers, exit
        if ($app->request->getMethod() == 'OPTIONS') {
            $app->response->setStatusCode('200', 'OK');
            $app->response->send();
            return;
        }

        // Respond by default as JSON
        if (!$app->request->get('type') || $app->request->get('type') == 'json') {
            // Results returned from the route's controller.  All Controllers should return an array
            $records = $app->getReturnedValue();
            $response = new \App\Responses\JSONResponse();

            $response->useEnvelope(true) //this is default behavior
                ->convertSnakeCase(false); //this is also default behavior

            if (isset($records['errorCode'])) {
                $response->send($records, true);
            } else {
                $response->send($records);
            }

            return;
        }

        if ($app->request->get('type') == 'csv') {
            $records  = $app->getReturnedValue();
            $response = new \App\Responses\CSVResponse();
            $response->useHeaderRow(true)->send($records);

            return;
        }

        throw new \App\Exceptions\HTTPException(
            'Could not return results in specified format',
            403
        );
    }
);

/**
 * The notFound service is the default handler function that runs when no route was matched.
 * We set a 404 here unless there's a suppress error codes.
 */
$app->notFound(
    function () use ($app) {
        throw new \App\Exceptions\HTTPException(
            'Not Found ('.$_SERVER['REQUEST_URI'].')',
            404
        );
    }
);

$app->handle();
