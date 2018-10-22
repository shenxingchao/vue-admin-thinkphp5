'use strict'
const merge = require('webpack-merge')
const prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  //BASE_API: '"http://www.o8o8o8.com/vue/vueadmin/index.php/admin"',
  BASE_API: '"http://10.3.9.51/index.php/admin"',
})
