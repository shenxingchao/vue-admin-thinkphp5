<template>
  <ul>
    <li v-if="title" class="ui-title">{{ title }}</li>
    <li v-for="(item,index) in data" :key="index">
      <a v-if="item.href" :href="item.href" target="_blank">
        {{ item[row] }}
      </a>
      <template v-else>
        {{ item[row] }}
      </template>
    </li>
  </ul>
</template>
<script>
export default{
  props: {
    data: {
      type: Array,
      default: null
    },
    row: {
      type: String,
      default: ''
    },
    title: {
      type: String,
      default: ''
    }
  }
}
</script>
<style scoped>
  ul{
    overflow: hidden;
    list-style: none;
    width: 100%;
    padding: 0;
    background: #ffffff;
  }
  ul li{
    padding: 16px 14px;
    border: 1px solid #ececec;
    border-bottom: none;
  }
  ul li:last-child{
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    border-bottom: 1px solid #ececec;
  }
  ul li:first-child{
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
  }
  .ui-title{
    background: #409EFF;
    color: #ffffff;
    border: 1px solid #409EFF;
  }

</style>
