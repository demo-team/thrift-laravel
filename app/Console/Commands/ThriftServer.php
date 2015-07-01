<?php
/**
 * Created by PhpStorm.
 * User: nilyang
 * Date: 15/6/29
 * Time: 17:53
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Juxiang\Handlers\AccountHandler;
use Thrift\Transport\TPhpStream;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;


class ThriftServer extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'thrift:demo:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Thrift Server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "hello".PHP_EOL;

        $handler = new AccountHandler();
        $processor = new \Demo\AccountProcessor($handler);

        $host = '0.0.0.0';
        $port = 8091;
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
    }

}
