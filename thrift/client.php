<?php
/**
 * Created by PhpStorm.
 * User: nilyang
 * Date: 15/6/18
 * Time: 18:22
 */
error_reporting(E_ALL);

require_once __DIR__ . '/src/0.9.2/Thrift/ClassLoader/ThriftClassLoader.php';


use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(__DIR__.'/gen-php');


$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ . '/src/0.9.2');
$loader->registerDefinition('Demo', $GEN_DIR);
$loader->register();

if (php_sapi_name() == 'cli') {
    ini_set('display_errors', 'stderr');
}

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

use Demo\AccountClient;


try{
    if (array_search('--http', $argv)) {
        $host = 'localhost';
        $port = 9090;
        $socket = new THttpClient('localhost', 8080, '/php/PhpServer.php');
    } else {
        $host = '192.168.1.109';
        $port = 9090;
        print "connect $host:$port...\n\n";
        $socket = new TSocket($host, $port);
    }

    $transport = new TBufferedTransport($socket, 1024,1024);
    $protocol = new TBinaryProtocol($transport);
    $client = new AccountClient($protocol);

    $transport->open();

    $newAccount = new \Demo\AccountInfo($info = ['email'=>$email,'name'=>'xiao san','userId'=>222]);
    $ret = $client->setUserInfo($newAccount);
    var_dump($ret);

    $accountInfo = $client->getUserInfoByEmail('nil.yang@qq.com');

    print_r($accountInfo);

    $transport->close();


}catch (TException $e){
    print 'TException: '.$e->getMessage()."\n";
}