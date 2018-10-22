<template>
  <div class="app-container admin_lst">
    <div class="opt_head"/>
    <el-row type="flex" class="row-bg" justify="center">
      <el-col>
        <el-button type="primary" icon="el-icon-plus" @click.native="$router.push('/permission/admin/admin_add')">添加</el-button>
      </el-col>
    </el-row>
    <el-form :inline="true" class="demo-form-inline block">
      <el-form-item>
        <el-input v-model="params.username" placeholder="账号名"/>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" icon="el-icon-search" @click.native="onSubmit">查询</el-button>
      </el-form-item>
    </el-form>
    <el-table
      :data="List"
      border>
      <el-table-column
        prop="username"
        label="账号名"/>
      <el-table-column
        prop="roles"
        sortable
        label="角色列表"/>
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
    <div class="block">
      <el-pagination
        :current-page="params.page"
        :total="params.total"
        :page-sizes="[10, 20, 50, 100]"
        :page-size="params.pageSize"
        background
        layout="total, sizes, prev, pager, next, jumper"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"/>
    </div>
  </div>
</template>
<script>
import { adminLst, adminDelete } from '@/api/permission/admin'
export default {
  name: 'AdminLst',
  data() {
    return {
      List: [],
      params: {
        page: 1,
        total: 0,
        pageSize: 10,
        username: ''
      }
    }
  },
  mounted() {
    this.getAdminLst()
  },
  methods: {
    getAdminLst() {
      adminLst(this.params).then(res => {
        this.List = res.data.data
        this.params.total = res.data.total
      }).catch(() => {})
    },
    handleEdit(index, row) {
      this.$router.push({
        path: '/permission/admin/admin_edit',
        query: {
          id: row.id
        }
      })
    },
    handleDelete(index, row) {
      adminDelete({ id: row.id }).then(res => {
        this.List.splice(index, 1)
        this.$message({
          message: '删除成功',
          type: 'success'
        })
      }).catch(() => {
      })
    },
    handleSizeChange(val) {
      this.params.pageSize = val
      this.getAdminLst()
    },
    handleCurrentChange(val) {
      this.params.page = val
      this.getAdminLst()
    },
    onSubmit() {
      this.params.page = 1
      this.params.pageSize = 10
      this.getAdminLst()
    }
  }
}
</script>
<style scoped>
  .admin_lst .block{
     margin-top: 20px;
  }
</style>
