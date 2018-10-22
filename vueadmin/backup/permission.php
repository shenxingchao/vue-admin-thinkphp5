<?php
/**
 * Created by PhpStorm.
 * User: 86431
 * Date: 2018-10-19
 * Time: 9:26
 */
//模拟数据
$data = [
    [
        'path'=>'/',
        'component'=>'Layout',
        'redirect'=>'/dashboard',
        'name'=>'index',
        'meta'=>[
            'title'=> '主页',
            'icon'=> 'dashboard',
        ],
        'children'=>[
            [
                'path'=>'dashboard',
                'component'=>'dashboard',
                'name'=>'dashboard',
                'meta'=>[
                    'title'=> '控制台',
                    'icon'=> 'dashboard',
                ]
            ]
        ],
    ],
    [
        'path'=>'/permission',
        'component'=>'Layout',
        'name'=>'permission',
        'redirect'=>'noredirect',
        'meta'=>[
            'title'=>'权限管理',
            'icon'=> 'dashboard',
        ],
        'children'=>[
            [
                'path'=>'permission_group',
                'component'=>'permission_group',
                'name'=>'permission_group',
                'redirect'=>'/permission/permission_group/permission_group_lst',
                'meta'=>[
                    'title'=> '权限分组',
                    'icon'=> 'dashboard',
                ],
                'children'=>[
                    [
                        'path'=>'permission_group_lst',
                        'component'=>'permission_group_lst',
                        'name'=>'permission_group_lst',
                        'meta'=>[
                            'title'=> '权限分组列表',
                            'icon'=> 'dashboard',
                        ],
                        //'hidden'=>true,
                    ],
                    [
                        'path'=>'permission_group_add',
                        'component'=>'permission_group_add',
                        'name'=>'permission_group_add',
                        'meta'=>[
                            'title'=> '添加权限分组',
                            'icon'=> 'dashboard',
                        ],
                        'hidden'=>true,
                    ],
                    [
                        'path'=>'permission_group_edit',
                        'component'=>'permission_group_edit',
                        'name'=>'permission_group_edit',
                        'meta'=>[
                            'title'=> '编辑权限分组',
                            'icon'=> 'dashboard',
                        ],
                        'hidden'=>true,
                    ],
                ],
            ],
            [
                'path'=>'permission_src',
                'component'=>'permission_src',
                'name'=>'permission_src',
                'redirect'=>'/permission/permission_src/permission_src_lst',
                'meta'=>[
                    'title'=> '权限资源',
                    'icon'=> 'dashboard',
                ],
                'children'=>[
                    [
                        'path'=>'permission_src_lst',
                        'component'=>'permission_src_lst',
                        'name'=>'permission_src_lst',
                        'meta'=>[
                            'title'=> '权限资源列表',
                            'icon'=> 'dashboard',
                        ]
                    ],
                    [
                        'path'=>'permission_src_add',
                        'component'=>'permission_src_add',
                        'name'=>'permission_src_add',
                        'meta'=>[
                            'title'=> '添加权限资源',
                            'icon'=> 'dashboard',
                        ],
                        'hidden'=>true,
                    ],
                    [
                        'path'=>'permission_src_edit',
                        'component'=>'permission_src_edit',
                        'name'=>'permission_src_edit',
                        'meta'=>[
                            'title'=> '编辑权限资源',
                            'icon'=> 'dashboard',
                        ],
                        'hidden'=>true,
                    ]
                ],
            ],
            [
                'path'=>'permission_role',
                'component'=>'permission_role',
                'name'=>'permission_role',
                'redirect'=>'/permission/permission_role/permission_role_lst',
                'meta'=>[
                    'title'=> '角色管理',
                    'icon'=> 'dashboard',
                ],
                'children'=>[
                    [
                        'path'=>'permission_role_lst',
                        'component'=>'permission_role_lst',
                        'name'=>'permission_role_lst',
                        'meta'=>[
                            'title'=> '角色列表',
                            'icon'=> 'dashboard',
                        ]
                    ],
                ]
            ]
        ],
    ]
];