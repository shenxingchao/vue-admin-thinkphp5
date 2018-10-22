<template>
  <div class="app-container permission_role_add">
    <el-row type="flex" justify="center">
      <el-col :xs="24" :md="12">
        <el-form ref="ruleForm" :rules="rules" :model="ruleForm" label-position="right" label-width="150px">
          <el-form-item label="角色名称" prop="role_name">
            <el-input v-model="ruleForm.role_name" placeholder="角色名称"/>
          </el-form-item>
          <el-form-item label="菜单分配" prop="permissionMenuIds">
            <el-tree
              ref="tree"
              :props="defaultProps"
              :data="permissionMenuNodes"
              node-key="id"
              show-checkbox
            />
          </el-form-item>
          <el-form-item label="权限分配" prop="permissionSrcIds">
            <el-tree
              ref="treeSrc"
              :props="defaultProps"
              :data="permissionSrcNodes"
              node-key="id"
              show-checkbox
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm('ruleForm')">确定</el-button>
            <el-button @click="resetForm('ruleForm')">重置</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import { permissionRoleAdd, permissionMenuNodes, permissionSrcNodes } from '@/api/permission/permission_role'
export default {
  name: 'PermissionRoleAdd',
  data() {
    return {
      ruleForm: {
        role_name: '',
        permissionMenuIds: [],
        permissionSrcIds: []
      },
      defaultProps: {
        children: 'children',
        label: 'label'
      },
      permissionMenuNodes: [],
      permissionSrcNodes: [],
      rules: {
        role_name: [
          { required: true, message: '请输入角色名称', trigger: 'blur' },
          { min: 2, max: 6, message: '长度在 2 到 6 个字符', trigger: 'blur' }
        ]
      }
    }
  },
  mounted() {
    this.getPermissionMenuNodes()
    this.getPermissionSrcNodes()
  },
  methods: {
    getPermissionMenuNodes() {
      permissionMenuNodes().then(res => {
        this.permissionMenuNodes = res.data
      }).catch(() => {
      })
    },
    getPermissionSrcNodes() {
      permissionSrcNodes().then(res => {
        this.permissionSrcNodes = res.data
      }).catch(() => {
      })
    },
    submitForm(formName) {
      const _this = this
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.ruleForm.permissionMenuIds = [].concat(this.$refs.tree.getCheckedKeys(), this.$refs.tree.getHalfCheckedKeys())
          this.ruleForm.permissionSrcIds = [].concat(this.$refs.treeSrc.getCheckedKeys(), this.$refs.treeSrc.getHalfCheckedKeys())
          this.ruleForm.tempMenuIds = this.$refs.tree.getCheckedKeys()
          this.ruleForm.tempSrcIds = this.$refs.treeSrc.getCheckedKeys()
          permissionRoleAdd(this.ruleForm).then(res => {
            this.$message({
              message: '添加成功',
              type: 'success',
              onClose: function() {
                _this.$router.push('/permission/permission_role/permission_role_lst')
              }
            })
          }).catch(() => {
          })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
    }
  }
}
</script>
