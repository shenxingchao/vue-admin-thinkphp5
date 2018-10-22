import request from '@/utils/request'

export function login(username, password) {
  return request({
    url: '/user/login',
    method: 'post',
    data: {
      username,
      password
    }
  })
}

export function getInfo() {
  return request({
    url: '/uc/info',
    method: 'get'
  })
}

export function logout() {
  return request({
    url: '/uc/logout',
    method: 'post'
  })
}

export function getPermissionRouter(params) {
  return request({
    url: '/uc/getPermissionRouter',
    method: 'post',
    params: params
  })
}
