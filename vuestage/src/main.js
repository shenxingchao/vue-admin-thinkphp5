import Vue from 'vue'// 导入vue
import 'normalize.css/normalize.css' // css样式重置
import ElementUI from 'element-ui'// 导入饿了么UI
import 'element-ui/lib/theme-chalk/index.css'// 导入饿了么UICSS
import locale from 'element-ui/lib/locale/lang/zh-CN' // 饿了么UI语言文件中文包
import '@/styles/index.scss' // 全局CSS
import App from './App'// 入口VUE
import router from './router'// 导入路由
import store from './store'// 导入状态管理
import '@/icons' // incon图标库 iconfont svg文件自己下载
import '@/permission' // 导入权限控制

Vue.use(ElementUI, { locale }) // 注册饿了么UI
Vue.config.productionTip = false // 取消VUE生产环境提示

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
