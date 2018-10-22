<?php

/**
 * API 用户权限访问基类
 * Created by PhpStorm.
 * User: 86431
 * Date: 2018/6/11 0011
 * Time: 13:11
 */
namespace app\admin\common;
use app\admin\controller\Token;
use app\admin\controller\Uc;
use think\Controller;
use think\Db;
use think\Request;

class BaseOauthController extends Controller {
    public $token = '';
    public function _initialize(){
        //构造方法 设置允许跨域请求
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,  X-Token");
        header("Access-Control-Expose-Headers: Token,Code");
        header('Access-Control-Max-Age: 3600');
        if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
            exit;
        }
        //构造方法 设置允许跨域请求
        $this->checkToken();
        $this->privilegeCheck();
        parent::_initialize();
    }

    /**
     * 权限验证
     */
    protected function privilegeCheck(){
        $request = Request::instance();
        $controller = $request->controller();
        $action = $request->action();
        $request_url = strtolower($controller . "/" . $action);
        //获取权限资源表中所有的权限 判断当前请求是否需要权限验证
        $permission_src = Db::name('permission_src')->where(['permission_code'=>['neq','']])->select();
        $permission_arr = [];
        foreach ($permission_src as $key=>$value){
            $temp = unserialize($value['permission_code']);
            foreach ($temp as $k=>$v){
                $permission_arr[] = strtolower($v['controller_name']."/".$v['action_name']);
            }
        }
        if(!in_array($request_url,$permission_arr)){
            //不需要权限判断则直接跳过
            return;
        }
        //通过登录token获取当前角色可访问的url
        $user_info = Uc::getDetailUserInfo($this->token);
        $role_info = Db::name('permission_role')->where(['id'=>['in',$user_info['roles']]])->select();
        $permission_src_str = '';
        foreach ($role_info as $key=>$value){
            if($value['permission_menu_ids']!=''){
                $permission_src_str.= ",".$value['permission_src_ids'];
            }
        }
        $permission_src_ids = array_values(array_filter(array_unique(explode(',',$permission_src_str))));
        $permission_src = Db::name('permission_src')->where(['id'=>['in',$permission_src_ids],'permission_code'=>['neq','']])->select();
        $permission_arr = [];
        foreach ($permission_src as $key=>$value){
            $temp = unserialize($value['permission_code']);
            foreach ($temp as $k=>$v){
                $permission_arr[] = strtolower($v['controller_name']."/".$v['action_name']);
            }
        }
        if(!in_array($request_url,$permission_arr)){
            //不存在权限，则提示
            header('Code:50016');//token不存在 登录
            exit;
        }
    }

    /**
     * 每次请求验证token
     */
    protected function checkToken(){
        $headers=getallheaders();
        if(!isset($headers['X-Token'])){
            header('Code:50008');//token不存在 登录
            exit;
        }else{
            //验证token
            $user = Db::name('admin')
                ->where(array('token'=>substr($headers['X-Token'],1)))
                ->field('expire_time,id,token')
                ->find();
            if(!$user){
                header('Code:50008');//token不存在 登录
                exit;
            }else if(time()-$user['expire_time'] >10){
                //token过期 刷新token 返回新token值
                $token = Token::refreshUserToken($user['id']);
                if(!$token)
                    header('Code:50014');//刷新token失败返回token过期状态码
                header('Token:'.$token);
            }
            $this->token = isset($token)?$token:$user['token'];
        }
    }
}