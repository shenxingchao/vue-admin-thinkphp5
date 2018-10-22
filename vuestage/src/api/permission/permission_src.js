import request from '@/utils/request'

export function permissionControllerOptions() {
  return request({
    url: '/permission/permissionControllerOptions',
    method: 'get'
  })
}

export function permissionActionOptions(params) {
  return request({
    url: '/permission/permissionActionOptions',
    method: 'get',
    params: params
  })
}

export function permissionSrcOptions() {
  return request({
    url: '/permission/permissionSrcOptions',
    method: 'get'
  })
}

export function permissionSrcAdd(ruleForm) {
  return request({
    url: '/permission/permissionSrcAdd',
    method: 'post',
    data: ruleForm
  })
}

export function permissionSrcEdit(ruleForm) {
  return request({
    url: '/permission/permissionSrcEdit',
    method: 'post',
    data: ruleForm
  })
}

export function permissionSrcLst() {
  return request({
    url: '/permission/permissionSrcLst',
    method: 'get'
  })
}

export function permissionSrcDetail(params) {
  return request({
    url: '/permission/permissionSrcDetail',
    method: 'get',
    params: params
  })
}

export function permissionSrcDelete(params) {
  return request({
    url: '/permission/permissionSrcDelete',
    method: 'get',
    params: params
  })
}
