// 导入路由常量
import { constantRouterMap } from '../../router'
// 导入要映射的组件
import map from '../../router/map'
// 导入request
import { getPermissionRouter } from '../../api/login'

/**
 * 递归映射数组
 * @param {未映射路由数组} asyncRouterMap
 */
function routerMapComponet(asyncRouterMap) {
  if (typeof (asyncRouterMap) === 'undefined') { return }
  asyncRouterMap.forEach((value, index) => {
    if (typeof (value.component) === 'string') {
      if (typeof (map[value.component]) === 'undefined') {
        asyncRouterMap.splice(index, 1)
      }
      value.component = map[value.component]
      routerMapComponet(value.children)
    }
  })
  return asyncRouterMap
}

// 定义权限常量
const permission = {
  state: {
    routers: constantRouterMap, // store状态 路由常量
    addRouters: [] // 待拼接的路由数组
  },
  mutations: {
    SET_ROUTERS: (state, routers) => { // 设置路由
      state.addRouters = routers
      state.routers = constantRouterMap.concat(routers) // 路由常量 拼接上设置的异步路由
    }
  },
  actions: {// 路由分发动作
    /**
     * 生成异步路由数组
     * @param {*} param0
     * @param {*用户角色数组} data
     */
    GenerateRoutes({ commit }, data) {
      return new Promise(resolve => {
        const { roles } = data

        getPermissionRouter({ roles: roles }).then(res => {
          const asyncRouterMap = res.data.concat(
            [
              {
                path: '/redirect',
                component: 'Layout',
                hidden: true,
                children: [
                  {
                    path: '/redirect/:path*',
                    component: () => import('@/views/redirect/index')
                  }
                ]
              },
              { path: '/404', component: () => import('@/views/public/404'), hidden: true },
              { path: '*', redirect: '/404', hidden: true }
            ]
          )
          // 组建映射
          const asyncRouterMapRes = routerMapComponet(asyncRouterMap)
          // console.log(asyncRouterMapRes)
          commit('SET_ROUTERS', asyncRouterMapRes) // 设置路由
          resolve()// 结束
        })
      })
    }
  }
}
export default permission
