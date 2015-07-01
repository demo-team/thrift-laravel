<?php namespace App\Http\Controllers;


use Illuminate\Http\Response;
use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TBinaryProtocol;
use Demo\AccountClient;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		static $request_count=0;

		try {
			$host = '192.168.1.109';
			$port = 8091;
			$email = 'nil.yang@qq.com';
			$info = ['email' => $email, 'name' => 'xiao san', 'userId' => 222];
			print "connect $host:$port...".$request_count++."\n\n";
			$socket = new TSocket($host, $port);

			$transport = new TBufferedTransport($socket, 1024, 1024);
			$protocol = new TBinaryProtocol($transport);
			$client = new AccountClient($protocol);

			$transport->open();

			$newAccount = new \Demo\AccountInfo();
			$ret = $client->setUserInfo($newAccount);
//			var_dump($ret);

			$accountInfo = $client->getUserInfoByEmail($email);

//			var_dump($accountInfo);

			$transport->close();

			return  response()->json($accountInfo);

		} catch (TException $e) {
			print 'TException: ' . $e->getMessage() . "\n";
		}

		//return view('welcome');
	}

}
