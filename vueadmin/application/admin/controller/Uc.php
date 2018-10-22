<?php

/**
 * 用户登录后接口
 * Created by PhpStorm.
 * User: 86431
 * Date: 2018/6/11 0011
 * Time: 13:13
 */
namespace  app\admin\controller;
use app\admin\common\BaseOauthController;
use think\Exception;
use think\Request;
use think\Db;

class Uc extends BaseOauthController {

    /**
     * 获取用户信息
     */
    public function info(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $user_info = self::getDetailUserInfo($this->token);
            $data = $user_info;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 根据token返回用户信息包含角色信息
     * @param $token
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getDetailUserInfo($token){
        $user_info = Db::name('admin')->where(['token'=>$token])->find();
        $user_info['roles'] = explode(',',$user_info['role_ids']);
        $user_info['avatar'] = 'http://q.qlogo.cn/qqapp/101368739/3A4B6FAA03A288F17DC52E32BCED2EEA/100';
        return $user_info;
    }

    /**
     * 获取用户可访问的路由
     */
    public function getPermissionRouter(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['roles'])){
                $code = 1;
                throw new Exception('参数错误');
            }else{
                $role_info = Db::name('permission_role')->where(['id'=>['in',$param['roles']]])->select();
                $permission_menu_str = '';
                foreach ($role_info as $key=>$value){
                    if($value['permission_menu_ids']!=''){
                        $permission_menu_str.= ",".$value['permission_menu_ids'];
                    }
                }
                $permission_menu_ids = array_filter(array_unique(explode(',',$permission_menu_str)));
                $permissionSrc = Db::name('permission_menu')->where(['id'=>['in',$permission_menu_ids]])->select();
                $permissionSrc = $this->getTreeArr($permissionSrc);
                //组装返回权限的数据
                $data = $this->execute($permissionSrc);
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 退出登录
     */
    public function logout(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            //更新token为空
            Db::name('admin')->where(array('token'=>$this->token))->update(array('token'=>'','expire_time'=>0));
            $data = null;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 获取无限极分类数组（直接是树形结构的数组） 直接循环输出时用 这里暂时无用
     * @param $data
     * @return array
     */
    private function getTreeArr($data){
        //构造数据
        $items = array();
        //以分类的id为索引
        foreach ($data as $key=>$value){
            $items[$value['id']] = $value;
        }
        //第二部 遍历数据 生成树状结构
        $tree = array();
        foreach($items as $key => $value){
            if($value['parent_id']!==0){//不是顶级分类
                //把当前循环的value放入父节点下面
                $items[$value['parent_id']]['son'][] = &$items[$key];
                //引用传值  当items更改时，tree里面的items也会更改
            }else{
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }


    /**
     * 递归返回可使用格式的权限数组
     * @param $data
     * @return array
     */
    private function execute($data){
        $res = array();
        foreach ($data as $key=>$value){
            $arr = [
                'path'=>$value['path'],
                'component'=>$value['component'],
                'name'=>$value['name'],
                'redirect'=>$value['redirect'],
                'meta'=>[
                    'title'=>$value['title'],
                    'icon'=>$value['icon']
                ],
                'hidden'=>$value['hidden']===1?false:true,//1是显示，2是隐藏
            ];
            if(!empty($value['son'])){
                $arr['children'] = $this->execute($value['son']);
            }
            $res[$key] = $arr;
        }
        return $res;
    }
}