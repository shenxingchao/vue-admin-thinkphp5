<template>
  <div class="app-container">
    <el-row>
      <el-col :xs="24" :sm="12" :md="6">
        <DataList :data="List" title="知识点" row="name"/>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import DataList from '@/components/DataList'
export default {
  name: 'Dashboard',
  components: { DataList },
  data() {
    return {
      List: [
        { name: '1.vue.js', href: 'https://cn.vuejs.org/' },
        { name: '2.vue-admin-template', href: 'https://github.com/PanJiaChen/vue-admin-template' },
        { name: '3.vue-router', href: 'https://router.vuejs.org/zh/' },
        { name: '4.vuex', href: 'https://vuex.vuejs.org/zh/guide/' },
        { name: '5.element-ui', href: 'http://element.eleme.io/#/zh-CN' },
        { name: '6.thinkphp5', href: 'http://www.thinkphp.cn/' },
        { name: '7.github', href: 'https://github.com/shenxingchao/vue-admin-thinkphp' }
      ]
    }
  }
}
</script>
