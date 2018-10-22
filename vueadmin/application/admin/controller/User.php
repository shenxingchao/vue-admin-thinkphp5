<?php

/**
 * 用户登录
 * Created by PhpStorm.
 * User: 86431
 * Date: 2018/6/11 0011
 * Time: 13:13
 */
namespace  app\admin\controller;
use app\admin\common\BaseController;
use think\Db;
use think\Exception;
use think\Request;

class User extends BaseController {

    /**
     * 用户登录接口
     */
    public function login(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['username'])||!isset($param['password'])){
                $code = 1;
                throw new Exception('参数错误');
            }else{
                $user = $this->checkLogin($param);
                if($user){
                    //验证成功生成用户token
                    $token = Token::refreshUserToken($user['id']);
                    if(!$token){
                        $code = 5;
                        throw new Exception('登录失败');
                    }
                    $data = array('token'=>$token);//返回数据，可自行添加
                }else{
                    $code = 2;
                    throw new Exception('用户名或密码错误');
                }
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    private function checkLogin($param){
        $param['password'] = md5($param['password']);
        return Db::name('admin')->where($param)->find();
    }
}