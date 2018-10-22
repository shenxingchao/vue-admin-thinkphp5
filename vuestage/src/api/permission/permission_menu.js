import request from '@/utils/request'

export function permissionMenuAdd(ruleForm) {
  return request({
    url: '/permission/permissionMenuAdd',
    method: 'post',
    data: ruleForm
  })
}

export function permissionMenuOptions() {
  return request({
    url: '/permission/permissionMenuOptions',
    method: 'get'
  })
}

export function permissionMenuLst() {
  return request({
    url: '/permission/permissionMenuLst',
    method: 'get'
  })
}

export function permissionMenuDetail(params) {
  return request({
    url: '/permission/permissionMenuDetail',
    method: 'get',
    params: params
  })
}

export function permissionMenuEdit(ruleForm) {
  return request({
    url: '/permission/permissionMenuEdit',
    method: 'post',
    data: ruleForm
  })
}

export function permissionMenuDelete(params) {
  return request({
    url: '/permission/permissionMenuDelete',
    method: 'get',
    params: params
  })
}
