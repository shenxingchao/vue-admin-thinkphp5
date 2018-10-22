<?php

/**
 * API 访问基类
 * Created by PhpStorm.
 * User: 86431
 * Date: 2018/6/11 0011
 * Time: 13:11
 */
namespace app\admin\common;
use think\Controller;
class BaseController extends Controller{
    public function _initialize(){
        //构造方法 设置允许跨域请求
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Max-Age: 3600');
        if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
            exit;
        }
        parent::_initialize();
    }
}