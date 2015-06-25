<?php
/**
 * Created by PhpStorm.
 * User: nilyang
 * Date: 15/6/23
 * Time: 16:36
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Requests_Session;

class CookieTest extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cookietest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试ios cookie 登录';

    public function handle()
    {
        $this->mockSessionLogin();
    }

    public function mockSessionLogin()
    {
        // Now let's make a request!
        #$url_pre = 'http://api.watchhhh.com';
        $url_pre = 'http://api.ptage.com:8090';
        $cookie_file = 'cookie.log';
        $cookie_str = '';
        if (file_exists($cookie_file)){
            $cookie_str = file_get_contents($cookie_file);
        }

        $cookie_header = array();

        #1.get token

        $url = $url_pre . '/juxiang_poc/user/token';
        $session = new Requests_Session($url);
        $session->headers['Accept'] = 'application/json';
        $session->useragent = 'Awesomesauce';

        // Now let's make a request!
        $result_token = $session->get($url);
        $token_data = json_decode($result_token->body, true);
        print_r($result_token->body);
        print_r($token_data);
        echo PHP_EOL;
        $token = ($token_data['data']);


        #2.post login
        $this->info("login");
        $url = $url_pre . '/juxiang_poc/user/login';
        $form_data = [
            'token' => $token,
            'access_token' => 'access_token',
            'refresh_token' => 'refresh_token',
            'expires_in' => 14400,
            'openid' => 'openid1003',
            'extraid' => 'extraid1003',
            'vendor' => 1,
            'nickname' => '李四4-22',
            'avatar' => 'http://gitlab.watchhhh.com:8089//uploads/user/avatar/3/tt.jpeg'
        ];
        $result_login = $session->post($url, [], $form_data);
        $login_result = json_decode($result_login->body, true);
        print_r($login_result);

        #3.show me info
        $this->info('show me');
        $url = $url_pre.'/juxiang_poc/user/me';
        $result_me = $session->get($url);
        print_r($result_me->body);
        echo PHP_EOL;

        $me_data = json_decode($result_me->body,true);
        print_r($me_data);


        #3.add to fav
        $this->info("fav info");
        $url = $url_pre.'/juxiang_poc/fav/add?favtype=2&id=340';
        $result_fav = $session->get($url);
        print_r($result_fav->body);
        echo PHP_EOL;

        $fav_data = json_decode($result_fav->body,true);
        print_r($fav_data);

    }
    /**
     * 使用pecl_http扩展 模拟http请求登录
     */
    public function mockHttpRequest()
    {
        #1.get token

        /**
         * @var \HttpQueryString $params
         */
        $url_pre = 'http://api.ptage.com:8090';
//        $url_pre = 'http://api.watchhhh.com';

        $this->info("1.get token ");

        $url = $url_pre.'/juxiang_poc/user/token';
        $result = $this->sendRequest($url);
        $token = '';
        if (is_array($result) && isset($result['data'])){
            $token = $result['data'];
        }
        if (empty($token)){
            $this->error('get token failed');
        }

        $this->info(PHP_EOL.'获得 token :'.$token.PHP_EOL);
        #2.login

        $this->info("2.do logged in");
        $url = $url_pre.'/juxiang_poc/user/login';
        $query_data = [];

        $form_data = [
            'token'=>$token,
            'access_token' => 'access_token',
            'refresh_token' => 'refresh_token',
            'expires_in' => 14400,
            'openid' => 'openid1003',
            'extraid' => 'extraid1003',
            'vendor' => 1,
            'nickname' => '李四4-22',
            'avatar' => 'http://gitlab.watchhhh.com:8089//uploads/user/avatar/3/tt.jpeg'
        ];
        $result =$this->sendRequest($url, $query_data, true);
        print_r($result);

        $this->info("3.get user info");
        $url = $url_pre.'/juxiang_poc/user/me';
        $result =$this->sendRequest($url, [], [], true);
        print_r($result);
    }
    public function sendRequest($url, array $query_data=[],$cookie=true)
    {
        /**
         * @var \HttpQueryString $params
         */

        $queryData = new \http\QueryString($query_data);
        /**
         * @var \HttpRequest $http
         */
        $request = new \http\Client\Request("GET", $url);
        if (!empty($queryData)){
            $request->getBody()->append($queryData);
        }

        $request->setContentType("application/x-www-form-urlencoded");

        $client = new \http\Client;

        $cookie_file = 'cookie.log';
        if ($cookie){
            $cookie_str = file_exists($cookie_file)?file_get_contents($cookie_file):'';
            $cookie = new Cookie($cookie_str);
            $client_cookie = $cookie->getCookie('frontend');
            if ($client_cookie != 'deleted') {
                $client->addCookies(['frontend' => $client_cookie]);
            }
        }
        $client->enqueue($request);
        $client->send();

        /** @var \HttpResponse $response */
        $response = $client->getResponse($request);
        printf("Sent:\n%s\n\n", $response->getParentMessage());
        printf("%s returned '%s'\n%s\n",
            $response->getTransferInfo("effective_url"),
            $response->getInfo(),
            $response->getBody()
        );
        file_put_contents($cookie_file,'');
        foreach ($response->getCookies() as $cookie) {
            /* @var $cookie http\Cookie */
            foreach ($cookie->getCookies() as $name => $value) {
                $cookie = new \http\Cookie();
                $cookie->addCookie($name,$value);
                file_put_contents($cookie_file, $cookie->toString(),FILE_APPEND);
            }
        }

        print_r($response->getHeaders());
        return json_decode($response->getBody(),true);
    }
}