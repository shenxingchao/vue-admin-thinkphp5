<template>
  <div class="app-container permission_menu_edit">
    <el-row type="flex" justify="center">
      <el-col :xs="24" :md="12">
        <el-form ref="ruleForm" :rules="rules" :model="ruleForm" label-position="right" label-width="150px">
          <el-form-item label="菜单名称" prop="title">
            <el-input v-model="ruleForm.title" placeholder="菜单名称"/>
          </el-form-item>
          <el-form-item label="路由地址" prop="path">
            <el-input v-model="ruleForm.path" placeholder="路由地址"/>
          </el-form-item>
          <el-form-item label="映射组件名称" prop="component">
            <el-input v-model="ruleForm.component" placeholder="映射组件名称"/>
          </el-form-item>
          <el-form-item label="路由名称" prop="name">
            <el-input v-model="ruleForm.name" placeholder="路由名称"/>
          </el-form-item>
          <el-form-item label="路由重定向地址" prop="redirect">
            <el-input v-model="ruleForm.redirect" placeholder="路由重定向地址(没有则不填)"/>
          </el-form-item>
          <el-form-item label="图标" prop="icon">
            <el-input v-model="ruleForm.icon" placeholder="图标(没有则不填)"/>
          </el-form-item>
          <el-form-item label="上级路由" prop="parent_id">
            <el-select v-model="ruleForm.parent_id" placeholder="请选择">
              <el-option
                v-for="item in options"
                :key="item.id"
                :label="item.title"
                :value="item.id"/>
            </el-select>
          </el-form-item>
          <el-form-item label="是否显示" prop="hidden">
            <el-radio v-model="ruleForm.hidden" :label="parseInt(1)">显示</el-radio>
            <el-radio v-model="ruleForm.hidden" :label="parseInt(2)">隐藏</el-radio>
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
import { permissionMenuEdit, permissionMenuDetail, permissionMenuOptions } from '@/api/permission/permission_menu'
export default {
  name: 'PermissionMenuEdit',
  data() {
    return {
      ruleForm: {
        title: '',
        path: '',
        component: '',
        name: '',
        redirect: '',
        icon: '',
        parent_id: '',
        hidden: 1,
        id: this.$route.query.id
      },
      options: [
        {
          title: '请选择',
          id: ''
        },
        {
          title: '顶级路由',
          id: 0
        }
      ],
      rules: {
        title: [
          { required: true, message: '请输入菜单名称', trigger: 'blur' },
          { min: 2, max: 6, message: '长度在 2 到 6 个字符', trigger: 'blur' }
        ],
        path: [
          { required: true, message: '请输入路由地址', trigger: 'blur' }
        ],
        component: [
          { required: true, message: '请输入映射组件名称', trigger: 'blur' }
        ],
        name: [
          { required: true, message: '请输入路由名称', trigger: 'blur' }
        ],
        parent_id: [
          { required: true, message: '请选择上级路由', trigger: 'change' }
        ]
      }
    }
  },
  mounted() {
    this.getOptions()
    this.getPermissionMenuDetail()
  },
  methods: {
    getOptions() {
      permissionMenuOptions().then(res => {
        this.options = this.options.concat(res.data)
      }).catch(() => {

      })
    },
    getPermissionMenuDetail() {
      permissionMenuDetail({ id: this.ruleForm.id }).then(res => {
        this.ruleForm = res.data
      }).catch(() => {
      })
    },
    submitForm(formName) {
      const _this = this
      this.$refs[formName].validate((valid) => {
        if (valid) {
          permissionMenuEdit(this.ruleForm).then(res => {
            this.$message({
              message: '保存成功',
              type: 'success',
              onClose: function() {
                _this.$router.push('/permission/permission_menu/permission_menu_lst')
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
