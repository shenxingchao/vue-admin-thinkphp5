<?php

/**
 * 权限菜单
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

class Permission extends BaseOauthController {

    /**
     * 获取控制器选项配置
     */
    public function permissionControllerOptions(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $controllerPath = APP_PATH.'admin/controller';
            $controller_name = array();
            $dirRes   = opendir($controllerPath);
            while($file = readdir($dirRes)) {
                if(!in_array($file,array('.','..')))
                {
                    $controller_name[] = [
                        'name'=>basename($file,'.php'),
                        'value'=>basename($file,'.php'),
                    ];
                }
            }
            $data = $controller_name;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 获取指定控制器里的方法
     */
    public function permissionActionOptions(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['controller'])){
                $code = 1;
                throw new Exception('参数错误');
            }else{
                $controller_name = $param['controller'];
                $action = get_class_methods("app\admin\controller\\".$controller_name);//获取类名下的所有方法
                $base_action = get_class_methods('app\admin\common\BaseOauthController');
                $action  = array_diff($action,$base_action);//比较剔除继承的方法
                $data = [];
                foreach ($action as $key=>$value){
                    $data[$key] = ['name'=>$value,'value'=>$value];
                }
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 递归获取权限资源，权限码为空的
     */
    public function permissionSrcOptions(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            $where = [];
            $where['permission_code'] = ['eq',''];
            if(isset($param['id']))
                $where['id'] = ['neq',$param['id']];
            $permissionSrc = Db::name('permission_src')->where($where)->select();
            $permissionOptions = $this->getTree($permissionSrc);
            $data = $permissionOptions;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 添加权限资源
     */
    public function permissionSrcAdd(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['title']) ||!isset($param['parent_id'])){
                $code = 1;
                throw new Exception('参数错误');
            }else{
                $is_exist = Db::name("permission_src")->where(['title'=>$param['title'],'parent_id'=>$param['parent_id']])->find();
                if($is_exist){
                    $code = 2;
                    throw new Exception('权限资源已存在');
                }
                $insert = [
                    'title'=>$param['title'],
                    'parent_id'=>$param['parent_id'],
                    'permission_code'=>!empty($param['permissionCode'])?serialize($param['permissionCode']):'',
                ];
                Db::name('permission_src')->insert($insert);
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 权限资源列表
     */
    public function permissionSrcLst(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            $permissionSrc = Db::name('permission_src')->select();
            $srcOptions = $this->getTreeArrOneForSrc($permissionSrc);
            $data = $srcOptions;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 权限资源详情
     */
    public function permissionSrcDetail(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['id'])){
                $code = 1;
                throw new Exception('参数错误');
            }
            else{
                $res = Db::name('permission_src')
                    ->where(['id'=>$param['id']])
                    ->find();
                $res['permissionCode'] = $res['permission_code']==''?[]:unserialize($res['permission_code']);
                unset($res['permission_code']);
                $data = $res;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 编辑权限资源
     */
    public function permissionSrcEdit(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['title']) ||!isset($param['parent_id'])){
                $code = 1;
                throw new Exception('参数错误');
            }else{
                $is_exist = Db::name("permission_src")->where(['title'=>$param['title'],'id'=>array('neq',$param['id']),'parent_id'=>$param['parent_id']])->find();
                if($is_exist){
                    $code = 2;
                    throw new Exception('权限资源已存在');
                }
                $update = [
                    'title'=>$param['title'],
                    'parent_id'=>$param['parent_id'],
                    'permission_code'=>!empty($param['permissionCode'])?serialize($param['permissionCode']):'',
                ];
                $where = ['id'=>$param['id']];
                Db::name('permission_src')->where($where)->update($update);
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }


    /**
     * 删除权限资源
     */
    public function permissionSrcDelete(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['id'])){
                $code = 1;
                throw new Exception('参数错误');
            }
            else{
                $hasChild = Db::name('permission_src')
                    ->where(['parent_id'=>$param['id']])->find();
                if($hasChild){
                    $code = 2;
                    throw new Exception('该资源下还有子类，不能删除');
                }
                Db::name('permission_src')
                    ->where(['id'=>$param['id']])
                    ->delete();
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 添加菜单资源
     */
    public function permissionMenuAdd(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['title'])||!isset($param['path'])||!isset($param['name'])||!isset($param['redirect'])
                ||!isset($param['icon'])||!isset($param['parent_id'])||!isset($param['hidden'])){
                $code = 1;
                throw new Exception('参数错误');
            }else{
                $is_exist = Db::name("permission_menu")->where(['title'=>$param['title']])->find();
                if($is_exist){
                    $code = 2;
                    throw new Exception('菜单名称已存在');
                }
                $insert = [
                    'title'=>$param['title'],
                    'path'=>$param['path'],
                    'component'=>$param['component'],
                    'name'=>$param['name'],
                    'redirect'=>$param['redirect'],
                    'icon'=>$param['icon'],
                    'parent_id'=>$param['parent_id'],
                    'hidden'=>$param['hidden']
                ];
                Db::name('permission_menu')->insert($insert);
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 递归获取所有菜单配置
     */
    public function permissionMenuOptions(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            $where  = [];
            if(isset($param['id']))
                $where['id'] = ['neq',$param['id']];
            $permissionMenu = Db::name('permission_menu')->where($where)->select();
            $menuOptions = $this->getTree($permissionMenu);
            $data = $menuOptions;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 菜单资源列表
     */
    public function permissionMenuLst(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            $permissionMenu = Db::name('permission_menu')->select();
            $menuOptions = $this->getTreeArrOne($permissionMenu);
            $data = $menuOptions;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 菜单资源详情
     */
    public function permissionMenuDetail(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['id'])){
                $code = 1;
                throw new Exception('参数错误');
            }
            else{
                $res = Db::name('permission_menu')
                    ->where(['id'=>$param['id']])
                    ->find();
                $data = $res;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }


    /**
     * 编辑菜单资源
     */
    public function permissionMenuEdit(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['title'])||!isset($param['path'])||!isset($param['name'])||!isset($param['redirect'])
                ||!isset($param['icon'])||!isset($param['parent_id'])||!isset($param['hidden'])){
                $code = 1;
                throw new Exception('参数错误');
            }else{
                $is_exist = Db::name("permission_menu")->where(['title'=>$param['title'],'id'=>array('neq',$param['id'])])->find();
                if($is_exist){
                    $code = 2;
                    throw new Exception('菜单名称已存在');
                }
                $where = ['id'=>$param['id']];
                $update = [
                    'title'=>$param['title'],
                    'path'=>$param['path'],
                    'component'=>$param['component'],
                    'name'=>$param['name'],
                    'redirect'=>$param['redirect'],
                    'icon'=>$param['icon'],
                    'parent_id'=>$param['parent_id'],
                    'hidden'=>$param['hidden']
                ];
                Db::name('permission_menu')->where($where)->update($update);
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 删除菜单资源
     */
    public function permissionMenuDelete(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['id'])){
                $code = 1;
                throw new Exception('参数错误');
            }
            else{
                //查找是否还有子类，角色是否拥有这个菜单资源，提示不能删除
                $hasChild = Db::name('permission_menu')
                    ->where(['parent_id'=>$param['id']])->find();
                if($hasChild){
                    $code = 2;
                    throw new Exception('该菜单下还有子菜单，不能删除');
                }
                $roles = Db::name('permission_role')->select();
                foreach ($roles as $key=>$value){
                    $temp = explode(',',$value['permission_menu_ids']);
                    if(in_array($param['id'],$temp)){
                        $code = 2;
                        throw new Exception('该菜单资源已被使用，不能删除');
                        break;
                    }
                }
                Db::name('permission_menu')
                    ->where(['id'=>$param['id']])
                    ->delete();
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 获取角色菜单节点数组
     */
    public function permissionMenuNodes(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            $permissionMenu = Db::name('permission_menu')->select();
            $permissionMenu = $this->getTreeArr($permissionMenu);
            $menuOptions = $this->executeTreeArr($permissionMenu);
            $data = $menuOptions;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 获取角色权限节点数组
     */
    public function permissionSrcNodes(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            $permissionSrc= Db::name('permission_src')->select();
            $permissionSrc = $this->getTreeArr($permissionSrc);
            $srcOptions = $this->executeTreeArr($permissionSrc);
            $data = $srcOptions;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 添加角色
     */
    public function permissionRoleAdd(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['role_name'])||!isset($param['permissionMenuIds'])||!isset($param['tempMenuIds'])){
                $code = 1;
                throw new Exception("未分配权限或参数不正确");
            }
            else{
                $is_exist = Db::name("permission_role")->where(['role_name'=>$param['role_name']])->find();
                if($is_exist){
                    $code = 2;
                    throw new Exception('角色名称已存在');
                }
                $insert = [
                    'role_name'=>$param['role_name'],
                    'permission_menu_ids'=>implode(',',$param['permissionMenuIds']),
                    'temp_menu_ids'=>implode(',',$param['tempMenuIds']),
                    'permission_src_ids'=>implode(',',$param['permissionSrcIds']),
                    'temp_src_ids'=>implode(',',$param['tempSrcIds'])
                ];
                Db::name('permission_role')->insert($insert);
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 角色列表
     */
    public function permissionRoleLst(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            $where = [];
            if(!empty($param['role_name'])){
                $where['role_name'] = ['like','%'. trim($param['role_name']) .'%'];
            }
            $list = Db::name('permission_role')
                ->where($where)
                ->paginate($param['pageSize'], false, ['query' => $request->param()])
                ->each(function($item,$index){
                    $menus = Db::name('permission_menu')->where(['id'=>['in',$item['permission_menu_ids']]])->column('title');
                    $item['menus'] = implode(' | ',$menus);
                    return $item;
                });
            $data = $list;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 获取角色详情
     */
    public function permissionRoleDetail(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['id'])){
                $code = 1;
                throw new Exception('参数错误');
            }
            else{
                $res = Db::name('permission_role')
                    ->where(['id'=>$param['id']])
                    ->find();
                $data['id'] = $param['id'];
                $data['role_name'] = $res['role_name'];
                $data['permissionMenuIds'] = explode(',',$res['permission_menu_ids']);
                $data['tempMenuIds'] = explode(',',$res['temp_menu_ids']);
                $data['permissionSrcIds'] = explode(',',$res['permission_src_ids']);
                $data['tempSrcIds'] = explode(',',$res['temp_src_ids']);
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 编辑角色
     */
    public function permissionRoleEdit(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['role_name'])||!isset($param['permissionMenuIds'])||!isset($param['tempMenuIds'])){
                $code = 1;
                throw new Exception("未分配权限或参数不正确");
            }
            else{
                $is_exist = Db::name("permission_role")->where(['role_name'=>$param['role_name'],'id'=>array('neq',$param['id'])])->find();
                if($is_exist){
                    $code = 2;
                    throw new Exception('角色名称已存在');
                }
                $where = ['id'=>$param['id']];
                $update= [
                    'role_name'=>$param['role_name'],
                    'permission_menu_ids'=>implode(',',$param['permissionMenuIds']),
                    'temp_menu_ids'=>implode(',',$param['tempMenuIds']),
                    'permission_src_ids'=>implode(',',$param['permissionSrcIds']),
                    'temp_src_ids'=>implode(',',$param['tempSrcIds'])
                ];
                Db::name('permission_role')->where($where)->update($update);
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 删除角色
     */
    public function permissionRoleDelete(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['id'])){
                $code = 1;
                throw new Exception('参数错误');
            }
            else{
                //查找是否有管理员使用这个角色则提示不能删除
                $admins = Db::name('admin')->column('role_ids');

                $is_setting = false;
                foreach ($admins as $key=>$value){
                    if(in_array($param['id'],explode(',',$value))){
                        $is_setting = true;
                        break;
                    }
                }
                if($is_setting){
                    $code = 2;
                    throw new Exception('有管理员设置了这个角色，不能被删除');
                }
                Db::name('permission_role')
                    ->where(['id'=>$param['id']])
                    ->delete();
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 添加管理员页面获取角色列表
     */
    public function adminRoles(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            $permission_role = Db::name('permission_role')->field('id,role_name')->select();
            $data = $permission_role;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 添加管理员
     */
    public function adminAdd(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['username'])||!isset($param['password'])||!isset($param['role_ids'])){
                $code = 1;
                throw new Exception("参数不正确");
            }
            else{
                $is_exist = Db::name("admin")->where(['username'=>$param['username']])->find();
                if($is_exist){
                    $code = 2;
                    throw new Exception('账号已存在');
                }
                $insert = [
                    'username'=>$param['username'],
                    'password'=>md5($param['password']),
                    'role_ids'=>implode(',',$param['role_ids'])
                ];
                Db::name('admin')->insert($insert);
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 管理员列表
     */
    public function adminLst(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            $where = [];
            if(!empty($param['username'])){
                $where['username'] = ['like','%'. trim($param['username']) .'%'];
            }
            $list = Db::name('admin')
                ->where($where)
                ->field('id,role_ids,username')
                ->paginate($param['pageSize'], false, ['query' => $request->param()])
                ->each(function($item,$index){
                    $roles = Db::name('permission_role')->where(['id'=>['in',explode(',',$item['role_ids'])]])->column('role_name');
                    $item['roles'] = implode(' | ',$roles);
                    return $item;
                });
            $data = $list;
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 管理员详情
     */
    public function adminDetail(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['id'])){
                $code = 1;
                throw new Exception('参数错误');
            }
            else{
                $res = Db::name('admin')
                    ->where(['id'=>$param['id']])
                    ->field('id,username,role_ids')
                    ->find();
                $data['id'] = $param['id'];
                $data['username'] = $res['username'];
                $data['password'] = '';
                $data['role_ids'] = explode(',',$res['role_ids']);
                foreach ($data['role_ids'] as $key=>$value){
                    $data['role_ids'][$key] = intval($value);
                }
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 编辑管理员
     */
    public function adminEdit(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['username'])||!isset($param['role_ids'])){
                $code = 1;
                throw new Exception("参数不正确");
            }
            else{
                $is_exist = Db::name("admin")->where(['username'=>$param['username'],'id'=>['neq',$param['id']]])->find();
                if($is_exist){
                    $code = 2;
                    throw new Exception('账号已存在');
                }
                $where = ['id'=>$param['id']];
                $update = [
                    'username'=>$param['username'],
                    'role_ids'=>implode(',',$param['role_ids'])
                ];
                if($param['password']!=''){
                    $update['password'] = md5($param['password']);
                }
                Db::name('admin')->where($where)->update($update);
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 删除管理员
     */
    public function adminDelete(){
        $request = Request::instance();
        $request->filter('trim');
        $code = 0;
        $msg = 'SUCCESS';
        try{
            $param = $request->param();
            if(!isset($param['id'])){
                $code = 1;
                throw new Exception('参数错误');
            }
            else{
                Db::name('admin')
                    ->where(['id'=>$param['id']])
                    ->delete();
                $data = null;
            }
        }catch (Exception $e){
            $data = null;
            $msg = $e->getMessage();
        }
        exit(json_encode(array('code'=>$code,'msg'=>$msg,'data'=>$data)));
    }

    /**
     * 递归实现无限极分类列表
     * @param  arr $data data
     * @param  int $parent_id pid
     * @param  int $level level
     * @return arr             result
     */
    private function getTree($data, $parent_id=0, $level = 0,$parent_path = 'parent_path'){
        static $tree = array();
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                $value['parent_path'] = $parent_path."_".$value['id'];//记录分类路径
                $value['title'] = str_repeat('——',$level * 2) . $value['title'];
                $tree[] = $value;
                unset($data['key']);
                $this->getTree($data, $value['id'], $level + 1,$value['parent_path']);
            }
        }
        return $tree;
    }

    /**
     * 获取无限极分类数组一维
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @param string $parent_path
     * @return array
     */
    private function getTreeArrOne($data, $parent_id=0, $level = 0,$parent_path='p'){
        static $tree = array();
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                foreach ($tree as $k=>$v){
                    if($v['id'] == $parent_id){
                        $tree[$k]['hasChild'] = true;
                        $tree[$k]['toggle'] = true;
                        break;
                    }
                }
                $value['show'] = $level===0?true:false;
                $value['toggle'] = $level===0?true:false;
                $value['hasChild'] = false;
                $value['level'] = $level;
                $value['parent_path'] = $parent_path."_".$value['id'];//记录分类路径
                $tree[] = $value;
                unset($data['key']);
                $this->getTreeArrOne($data, $value['id'], $level + 1,$value['parent_path']);
            }
        }
        return $tree;
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
     * 递归返回可使用格式的权限节点
     * @param $data
     * @return array
     */
    private function executeTreeArr($data){
        $res = array();
        foreach ($data as $key=>$value){
            $arr = [
                'id'=>$value['id'],
                'label'=>$value['title'],
            ];
            if(!empty($value['son'])){
                $arr['children'] = $this->executeTreeArr($value['son']);
            }
            $res[$key] = $arr;
        }
        return $res;
    }

    /**
     * 获取无限极分类数组一维
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @param string $parent_path
     * @return array
     */
    private function getTreeArrOneForSrc($data, $parent_id=0, $level = 0,$parent_path='p'){
        static $tree = array();
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                foreach ($tree as $k=>$v){
                    if($v['id'] == $parent_id){
                        $tree[$k]['hasChild'] = true;
                        $tree[$k]['toggle'] = true;
                        break;
                    }
                }
                $value['show'] = $level===0?true:false;
                $value['toggle'] = $level===0?true:false;
                $value['hasChild'] = false;
                $value['level'] = $level;
                $value['parent_path'] = $parent_path."_".$value['id'];//记录分类路径
                if($value['permission_code']!=''){
                    $temp = unserialize($value['permission_code']);
                    foreach ($temp as $x=>$y){
                        $temp[$x] = $y['controller_name'] . "/" . $y['action_name'];
                    }
                    $value['permission_code'] = implode(' @ ',$temp);
                }
                $tree[] = $value;
                unset($data['key']);
                $this->getTreeArrOneForSrc($data, $value['id'], $level + 1,$value['parent_path']);
            }
        }
        return $tree;
    }
}