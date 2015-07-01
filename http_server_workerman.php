<?php
require __DIR__.'/bootstrap/autoload.php';
use Workerman\Worker;
use Workerman\Protocols\Http;

class HttpServer
{
    public static $instance;
    public static $serverInstance = null;
    public $http;
    public static $get;
    public static $post;
    public static $header;
    public static $server;
    private $application = null;
    private $kernel;
    private $serverHost = '127.0.0.1';
    private $serverPort = 9090;
    private $workerNum = 4;
    public function __construct()
    {
        $this->newHttpServer();
        $this->onWorkerStart();
        $this->onRequest();
        $this->onStart();
    }

    public function newHttpServer()
    {
        if (!self::$serverInstance || !self::$serverInstance instanceof Worker) {
//            self::$serverInstance = new swoole_http_server($this->serverHost, $this->serverPort);
            self::$serverInstance = new Worker('http://' . $this->serverHost . ':' . $this->serverPort);
        }

        return self::$serverInstance;
    }

    public function onWorkerStart()
    {
        $this->application = require_once __DIR__.'/bootstrap/app.php';
        $this->kernel = $this->application->make(Illuminate\Contracts\Http\Kernel::class);

        self::$serverInstance->count = $this->workerNum;
    }

    public function onRequest()
    {
        $http = self::$serverInstance;
        $kernel = $this->kernel;

        $http->onMessage = function($connection, $data) use ($kernel) {
//        $http->on('request', function ($request, $response) use($kernel) {
//            if ($request->server['request_uri'] !== '/favicon.ico') {

            $l_request= new Symfony\Component\HttpFoundation\Request(
//                    isset($request->get) ? $request->get:[],
                $data['get'],
                $data['post'],
                [],
                $data['cookie'],
                $data['files'],
                $data['server']
            );

            if (0 === strpos($l_request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
                && in_array(strtoupper($l_request->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))
            ) {
                parse_str($l_request->getContent(), $data);
                $l_request->request = new Symfony\Component\HttpFoundation\ParameterBag($data);
            }
            Illuminate\Http\Request::enableHttpMethodParameterOverride();
            $l_request=Illuminate\Http\Request::createFromBase($l_request);


            $l_response = $kernel->handle($l_request);

            $result = $l_response->getContent();

            foreach ($l_response->headers->allPreserveCase() as $name => $values) {
                Http::header($name . ':' . implode(';', $values));
            }


//            $l_response->send();
            foreach ($l_response->headers->getCookies() as $cookie) {
                Http::setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(),
                    $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
            }

            $kernel->terminate($l_request, $l_response);
            $connection->send($result);


//            }

        };
    }

    public function onStart()
    {
        $http = self::$serverInstance;
        $http->runAll();
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new HttpServer;
        }
        return self::$instance;
    }

}

HttpServer::getInstance();
