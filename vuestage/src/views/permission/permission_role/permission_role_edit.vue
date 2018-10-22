<template>
  <div class="app-container permission_role_edit">
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
              :default-expanded-keys="expandedKeys"
              node-key="id"
              show-checkbox
            />
          </el-form-item>
          <el-form-item label="权限分配" prop="permissionSrcIds">
            <el-tree
              ref="treeSrc"
              :props="defaultProps"
              :data="permissionSrcNodes"
              :default-expanded-keys="expandedSrcKeys"
              node-key="id"
              show-checkbox
            />
          </el-form-item>
          <el-form-item>
            <el-button type="success" @click="submitForm('ruleForm')">保存</el-button>
            <el-button @click="resetForm('ruleForm')">重置</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import { permissionRoleDetail, permissionRoleEdit, permissionMenuNodes, permissionSrcNodes } from '@/api/permission/permission_role'
export default {
  name: 'PermissionRoleEdit',
  data() {
    return {
      ruleForm: {
        role_name: '',
        permissionMenuIds: [],
        permissionSrcIds: [],
        id: this.$route.query.id
      },
      defaultProps: {
        children: 'children',
        label: 'label'
      },
      permissionMenuNodes: [],
      permissionSrcNodes: [],
      expandedKeys: [],
      expandedSrcKeys: [],
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
        this.getPermissionRoleDetail()
      }).catch(() => {
      })
    },
    getPermissionSrcNodes() {
      permissionSrcNodes().then(res => {
        this.permissionSrcNodes = res.data
      }).catch(() => {
      })
    },
    getPermissionRoleDetail() {
      permissionRoleDetail({ id: this.ruleForm.id }).then(res => {
        this.ruleForm = res.data
        this.$refs.tree.setCheckedKeys(this.ruleForm.tempMenuIds)
        this.$refs.treeSrc.setCheckedKeys(this.ruleForm.tempSrcIds)
        this.expandedKeys = this.ruleForm.tempMenuIds
        this.expandedSrcKeys = this.ruleForm.tempSrcIds
      }).catch(() => {})
    },
    submitForm(formName) {
      const _this = this
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.ruleForm.permissionMenuIds = [].concat(this.$refs.tree.getCheckedKeys(), this.$refs.tree.getHalfCheckedKeys())
          this.ruleForm.permissionSrcIds = [].concat(this.$refs.treeSrc.getCheckedKeys(), this.$refs.treeSrc.getHalfCheckedKeys())
          this.ruleForm.tempMenuIds = this.$refs.tree.getCheckedKeys()
          this.ruleForm.tempSrcIds = this.$refs.treeSrc.getCheckedKeys()
          permissionRoleEdit(this.ruleForm).then(res => {
            this.$message({
              message: '保存成功',
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
