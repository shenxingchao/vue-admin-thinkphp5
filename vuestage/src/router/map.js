/**
 * 组件映射文件
 */
import Layout from '../views/layout/Layout'
export const map = {
  Layout: Layout, // 基础布局
  dashboard: () => import('@/views/public/Dashboard'), // 控制面板
  /* 权限管理 */
  // 权限资源
  permission_src: () => import('@/views/permission/permission_src/permission_src.vue'),
  permission_src_lst: () => import('@/views/permission/permission_src/permission_src_lst.vue'), // 权限资源列表
  permission_src_add: () => import('@/views/permission/permission_src/permission_src_add.vue'), // 添加权限资源
  permission_src_edit: () => import('@/views/permission/permission_src/permission_src_edit.vue'), // 编辑权限资源
  // 菜单资源
  permission_menu: () => import('@/views/permission/permission_menu/permission_menu.vue'),
  permission_menu_lst: () => import('@/views/permission/permission_menu/permission_menu_lst.vue'), // 菜单资源列表
  permission_menu_add: () => import('@/views/permission/permission_menu/permission_menu_add.vue'), // 添加菜单资源
  permission_menu_edit: () => import('@/views/permission/permission_menu/permission_menu_edit.vue'), // 编辑菜单资源
  // 角色管理
  permission_role: () => import('@/views/permission/permission_role/permission_role.vue'),
  permission_role_lst: () => import('@/views/permission/permission_role/permission_role_lst.vue'), // 角色列表
  permission_role_add: () => import('@/views/permission/permission_role/permission_role_add.vue'), // 添加角色
  permission_role_edit: () => import('@/views/permission/permission_role/permission_role_edit.vue'), // 编辑角色
  // 管理员
  admin: () => import('@/views/permission/admin/admin.vue'),
  admin_lst: () => import('@/views/permission/admin/admin_lst.vue'), // 管理员列表
  admin_add: () => import('@/views/permission/admin/admin_add.vue'), // 添加管理员
  admin_edit: () => import('@/views/permission/admin/admin_edit.vue') // 编辑管理员
}
export default map
