<?php

/**
 * Token
 * Created by PhpStorm.
 * User: 86431
 * Date: 2018/6/11 0011
 * Time: 13:13
 */
namespace  app\admin\controller;
use app\admin\common\BaseController;
use think\Db;

class Token extends BaseController {

    /**
     * 生成用户登录令牌
     * @param $user_id
     * @return bool|string
     */
    public static function refreshUserToken($user_id){
        if(!$user_id)
            return false;
        //生成token
        $rand_num = rand(10,99999);//随机数
        $time = time();//时间戳
        $expire_time = $time+3600*24;//过期时间为1天
        $token = md5($user_id.$rand_num.$time);
        //更新当前用户token 和 有效期
        $update = array(
            'token'=>$token,
            'expire_time'=>$expire_time,
        );
        $update_res = Db::name('admin')->where(array('id'=>$user_id))->update($update);
        if(!$update_res)
            return false;
        else
            return $token;
    }
}

