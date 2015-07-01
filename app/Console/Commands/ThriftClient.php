<?php
/**
 * Created by PhpStorm.
 * User: nilyang
 * Date: 15/6/29
 * Time: 19:56
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;

use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TBinaryProtocol;
use Demo\AccountClient;

class ThriftClient extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'thrift:demo:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Thrift Client';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //1万次请求测试
        $i=10000;
        while($i-- >0 ) {
            try {
                $host = '127.0.0.1';
                $port = 8091;
                $email = 'nil.yang@qq.com';
                $info = ['email' => $email, 'name' => '张三', 'userId' => 222];
                print "connect $host:$port...\n\n";
                $socket = new TSocket($host, $port);

                $transport = new TBufferedTransport($socket, 1024, 1024);
                $protocol = new TBinaryProtocol($transport);
                $client = new AccountClient($protocol);

                $transport->open();

                $newAccount = new \Demo\AccountInfo();
                $ret = $client->setUserInfo($newAccount);
                var_dump($ret);

                $accountInfo = $client->getUserInfoByEmail($email);

                print_r($accountInfo);

                $transport->close();


            } catch (TException $e) {
                print 'TException: ' . $e->getMessage() . "\n";
            }
        }
    }
}