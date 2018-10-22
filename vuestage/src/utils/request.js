import axios from 'axios'
import { Message, MessageBox } from 'element-ui'
import store from '../store'
import { getToken } from '@/utils/auth'

// 创建axios实例
const service = axios.create({
  baseURL: process.env.BASE_API, // api 的 base_url
  timeout: 5000 // 请求超时时间
})

// request拦截器
service.interceptors.request.use(
  config => {
    if (store.getters.token) {
      config.headers['X-Token'] = 't' + getToken() // 让每个请求携带自定义token 请根据实际情况自行修改
    }
    return config
  },
  error => {
    // Do something with request error
    console.log(error) // for debug
    Promise.reject(error)
  }
)

// response 拦截器
service.interceptors.response.use(
  response => {
    /**
     * 刷新token
     */
    if (response.headers.token) {
      store.dispatch('RefreshToken', response.headers.token).then(() => {
      }).catch(() => {
      })
    }
    /**
     * code为非0是抛错 可结合自己业务进行修改
     */
    const res = response.data
    const headers = response.headers

    if (typeof (headers.code) !== 'undefined') {
      if (headers.code === '50016') {
        Message({
          message: ' 当前账号不开放此权限',
          type: 'error',
          duration: 2 * 1000
        })
      }
      // 50008:非法的token; 50014:Token 过期了;
      if (headers.code === '50008' || headers.code === '50014') {
        MessageBox.confirm(
          'token不存在或已过期您已被挤下线',
          '确定登出',
          {
            confirmButtonText: '重新登录',
            cancelButtonText: '取消',
            type: 'warning'
          }
        ).then(() => {
          store.dispatch('FedLogOut').then(() => {
            location.reload() // 为了重新实例化vue-router对象 避免bug
          })
        })
      }
      return Promise.reject(headers.code)
    }

    if (res.code !== 0) {
      Message({
        message: res.msg,
        type: 'error',
        duration: 2 * 1000
      })
      return Promise.reject(res.code)
      // return Promise.reject('error')
    } else {
      return response.data
    }
  },
  error => {
    // console.log('err' + error) // for debug
    Message({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
