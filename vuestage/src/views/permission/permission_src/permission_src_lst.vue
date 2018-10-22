<template>
  <div class="app-container permission_src_lst">
    <div class="opt_head"/>
    <el-row type="flex" class="row-bg" justify="center">
      <el-col>
        <el-button type="primary" icon="el-icon-plus" @click.native="$router.push('/permission/permission_src/permission_src_add')">添加</el-button>
      </el-col>
    </el-row>
    <el-table
      :data="List"
      :row-style="showRow"
      class="block"
      border>
      <el-table-column
        v-for="(column,index) in listKey"
        :key="index"
        :label="column.title"
      >
        <template slot-scope="scope">
          <span v-if="column.showSpace"><span v-for="(levelItem,levelIndex) in scope.row.level*8" :key="levelIndex" >&nbsp;</span></span>
          <span v-if="showToggleIcon(index,scope.row)" @click="toggleShow(scope.$index)">
            <i v-show="scope.row.toggle" class="el-icon-arrow-right" style="cursor:pointer"/>
            <i v-show="!scope.row.toggle" class="el-icon-arrow-down" style="cursor:pointer"/>
          </span>
          <span>{{ scope.row[column.dataIndex] }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button
            size="mini"
            icon="el-icon-edit"
            type="primary"
            @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button
            size="mini"
            icon="el-icon-delete"
            type="danger"
            @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>
<script>
import { permissionSrcLst, permissionSrcDelete } from '@/api/permission/permission_src'

export default {
  name: 'PermissionSrcLst',
  data() {
    return {
      List: [],
      listKey: [
        {
          title: '资源名称',
          dataIndex: 'title',
          showSpace: true
        },
        {
          title: '权限码',
          dataIndex: 'permission_code',
          showSpace: false
        }
      ]
    }
  },
  mounted() {
    this.getPermissionSrcLst()
  },
  methods: {
    /**
     * 获取后台数据
     */
    getPermissionSrcLst() {
      permissionSrcLst().then(res => {
        this.List = res.data
      }).catch(() => {})
    },
    /**
     * 有子类才显示图标
     */
    showToggleIcon(index, row) {
      if (index === 0 && row.hasChild) {
        return true
      }
      return false
    },
    /**
     * element 自带样式回调函数
     */
    showRow({ row, rowIndex }) {
      if (this.List[rowIndex].show) {
        return ''
      } else {
        return 'display:none'
      }
    },
    /**
     * 点击展开收缩
     */
    toggleShow(rowIndex) {
      // 伸缩图标更改
      this.List[rowIndex].toggle = !this.List[rowIndex].toggle
      let reg
      if (this.List[rowIndex].toggle) {
        // 如果当前是收拢动作，那么隐藏他的所有子类
        reg = new RegExp('^' + this.List[rowIndex].parent_path + '_.*' + '$')
        this.List.forEach((value, index) => {
          if (reg.test(value.parent_path)) {
            this.List[index].show = false
            this.List[index].toggle = true
          }
        })
      } else {
        // 展开动作,点击后只改变他的下级子类的显示隐藏，不改变展开状态
        reg = new RegExp('^' + this.List[rowIndex].parent_path + '_\\d+' + '$')
        this.List.forEach((value, index) => {
          if (reg.test(value.parent_path)) {
            this.List[index].show = !this.List[index].show
          }
        })
      }
    },
    /**
     * 编辑操作
     */
    handleEdit(index, row) {
      this.$router.push({
        path: '/permission/permission_src/permission_src_edit',
        query: {
          id: row.id
        }
      })
    },
    /**
     * 删除操作
     */
    handleDelete(index, row) {
      permissionSrcDelete({ id: row.id }).then(res => {
        this.List.splice(index, 1)
        this.$message({
          message: '删除成功',
          type: 'success'
        })
      }).catch(() => {

      })
    }
  }
}
</script>
<style scoped>
  .permission_src_lst .block{
     margin-top: 20px;
  }
</style>
