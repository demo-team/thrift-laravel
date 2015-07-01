<?php
/**
 * Created by PhpStorm.
 * User: nilyang
 * Date: 15/6/29
 * Time: 19:44
 */
namespace DemoServices\Handlers;

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

        $info = ['email'=>$email,'name'=>'张三','userId'=>222];
        return new AccountInfo($info);
    }
}
