import router from './router'// 导入路由
import store from './store'// 导入状态管理
import NProgress from 'nprogress' // Progress 进度条
import 'nprogress/nprogress.css'// Progress 进度条样式
import { Message } from 'element-ui' // 导入消息提示框
import { getToken } from '@/utils/auth' // 验权

const whiteList = ['/login'] // 不重定向白名单 后面使用配置文件

/**
 * 路由前置钩子
 */
router.beforeEach((to, from, next) => {
  NProgress.start()// 进度条显示开始
  if (getToken()) { // 已经登录返回了token
    if (to.path === '/login') { // 如果已经登录再进登录页没有注销就直接返回首页
      next({ path: '/' }) // 路由切换到首页
      NProgress.done() // 进度条显示完成
    } else {
      if (store.getters.roles.length === 0) { // 获取状态里的角色状态 如果没有角色
        store.dispatch('GetInfo').then(res => { // 拉取用户信息
          const roles = res.data.roles
          store.dispatch('GenerateRoutes', { roles }).then(() => { // 生成可访问的路由表
            router.options.routes = store.getters.addRouters // 新增的路由添加到路由配置 不然菜单不生效
            router.addRoutes(store.getters.addRouters) // 动态添加可访问路由表
            next({ ...to, replace: true }) // hack方法 确保addRoutes已完成
          })
        }).catch((err) => {
          store.dispatch('FedLogOut').then(() => { // 注销方法 需重写
            Message.error(err || '验证失败，请重新登录')
            next({ path: '/' })// 路由切换到首页
          })
        })
      } else {
        next()
      }
    }
  } else {
    if (whiteList.indexOf(to.path) !== -1) { // 如果路由在白名单，则不需要验证
      next()
    } else {
      next(`/login?redirect=${to.path}`) // 需要登录的页面没登录直接访问，全部重定向到登录页
      NProgress.done() // 进度条显示完成
    }
  }
})

/**
 * 路由后置钩子
 */
router.afterEach(() => {
  NProgress.done() // 结束Progress
})
