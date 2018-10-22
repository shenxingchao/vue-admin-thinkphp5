import request from '@/utils/request'

export function permissionMenuNodes() {
  return request({
    url: '/permission/permissionMenuNodes',
    method: 'get'
  })
}

export function permissionSrcNodes() {
  return request({
    url: '/permission/permissionSrcNodes',
    method: 'get'
  })
}

export function permissionRoleAdd(ruleForm) {
  return request({
    url: 'permission/permissionRoleAdd',
    method: 'post',
    data: ruleForm
  })
}

export function permissionRoleLst(params) {
  return request({
    url: '/permission/permissionRoleLst',
    method: 'get',
    params: params
  })
}

export function permissionRoleDelete(params) {
  return request({
    url: '/permission/permissionRoleDelete',
    method: 'get',
    params: params
  })
}

export function permissionRoleDetail(params) {
  return request({
    url: '/permission/permissionRoleDetail',
    method: 'get',
    params: params
  })
}

export function permissionRoleEdit(ruleForm) {
  return request({
    url: 'permission/permissionRoleEdit',
    method: 'post',
    data: ruleForm
  })
}
