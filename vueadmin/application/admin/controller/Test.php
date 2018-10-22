<?php

/**
 * 接口编写规范
 * Created by PhpStorm.
 * User: 86431
 * Date: 2018/6/11 0011
 * Time: 13:13
 */
namespace  app\admin\controller;
use think\Exception;
use think\Request;

class Test extends \app\admin\common\BaseController {

    public function index(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['token'])){
                $code = 1;
                throw new Exception('token不能为空');
            }else{
                $data = array('name'=>3333);
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }
}