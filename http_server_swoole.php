<?php

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
    private $serverPort = 9501;
    public function __construct()
    {
        $this->newSwooleHttpServer();
        $this->onWorkerStart();
        $this->onRequest();
        $this->onStart();
    }

    public function newSwooleHttpServer()
    {
        if (!self::$serverInstance || !self::$serverInstance instanceof swoole_http_server) {
            self::$serverInstance = new swoole_http_server($this->serverHost, $this->serverPort);
        }

        return self::$serverInstance;
    }

    public function onWorkerStart()
    {
        require __DIR__.'/bootstrap/autoload.php';
        $this->application = require_once __DIR__.'/bootstrap/app.php';
        $this->kernel = $this->application->make(Illuminate\Contracts\Http\Kernel::class);
    }

    public function onRequest()
    {
        $http = self::$serverInstance;
        $kernel = $this->kernel;

        $http->on('request', function ($request, $response) use($kernel) {
            $_SERVER = array_change_key_case($request->server);
            $l_request= new Symfony\Component\HttpFoundation\Request(
                isset($request->get)?$request->get:[],
                isset($request->post)? $request->post:[], [],
                isset($request->cookie)?$request->cookie:[],
                isset($request->files)? $request->files:[],
                $_SERVER,
                $request->rawContent()
            );

            if (0 === strpos($l_request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
                && in_array(strtoupper($l_request->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))
            ) {
                parse_str($l_request->getContent(), $data);
                $l_request->request = new  Symfony\Component\HttpFoundation\ParameterBag($data);
            }
            Illuminate\Http\Request::enableHttpMethodParameterOverride();
            $l_request=Illuminate\Http\Request::createFromBase( $l_request);


            $l_response = $kernel->handle( $l_request );

            ob_start();

            $l_response->send();
            $kernel->terminate($l_request, $l_response);

            $result = ob_get_clean();
            $response->end($result);
        });
    }

    public function onStart()
    {
        $http = self::$serverInstance;
        $http->start();
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new HttpServer;
        }
        return self::$instance;
    }

}

HttpServer::getInstance();
