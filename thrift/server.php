<?php
/**
 * Created by PhpStorm.
 * User: nilyang
 * Date: 15/6/18
 * Time: 18:07
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


use Demo\AccountIf;
use Demo\AccountInfo;

class AccountHandler implements AccountIf
{

    /**
     * @param \Demo\AccountInfo $accountInfo
     * @return int
     * @throws \Demo\InvalideOperation
     */
    public function setUserInfo(\Demo\AccountInfo $accountInfo)
    {
        echo "log: call ".  __METHOD__ ,"\n\n";

        print_r($accountInfo);

        return 222;
    }

    /**
     * @param string $email
     * @return \Demo\AccountInfo
     * @throws \Demo\InvalideOperation
     */
    public function getUserInfoByEmail($email)
    {
        echo "log: call ".  __METHOD__ ,"\n\n";

        $info = ['email'=>$email,'name'=>'xiao san','userId'=>222];
        return new AccountInfo($info);
    }
}


echo "\r\n";

use Thrift\Transport\TPhpStream;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;

$handler = new AccountHandler();
$processor = new \Demo\AccountProcessor($handler);

//$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
//$protocol = new TBinaryProtocol($transport, true, true);

//$transport->open();
//
//$processor->process($protocol, $protocol);
//
//$transport->close();

$host = '192.168.1.109';
$port = 9090;
$transport = new \Thrift\Server\TServerSocket($host,$port);
$inputTransportFactory = new \Thrift\Factory\TTransportFactory();
$outputTransportFactory = new \Thrift\Factory\TTransportFactory();
$inputProtocolFactory = new \Thrift\Factory\TBinaryProtocolFactory(true, true);
$outputProtocolFactory = new \Thrift\Factory\TBinaryProtocolFactory(true, true);

$server = new \Thrift\Server\TSimpleServer(
    $processor,
    $transport,
    $inputTransportFactory,$outputTransportFactory,
    $inputProtocolFactory, $outputProtocolFactory
);

echo "server start listen $host:$port ... \n";
$server->serve();

