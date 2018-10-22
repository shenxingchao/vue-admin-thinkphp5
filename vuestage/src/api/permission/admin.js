import request from '@/utils/request'

export function adminRoles() {
  return request({
    url: '/permission/adminRoles',
    method: 'get'
  })
}

export function adminAdd(ruleForm) {
  return request({
    url: '/permission/adminAdd',
    method: 'post',
    data: ruleForm
  })
}

export function adminEdit(ruleForm) {
  return request({
    url: '/permission/adminEdit',
    method: 'post',
    data: ruleForm
  })
}

export function adminLst(params) {
  return request({
    url: '/permission/adminLst',
    method: 'get',
    params: params
  })
}

export function adminDetail(params) {
  return request({
    url: '/permission/adminDetail',
    method: 'get',
    params: params
  })
}

export function adminDelete(params) {
  return request({
    url: '/permission/adminDelete',
    method: 'get',
    params: params
  })
}
