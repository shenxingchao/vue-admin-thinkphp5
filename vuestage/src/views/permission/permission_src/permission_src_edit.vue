<template>
  <div class="app-container permission_src_edit">
    <el-row type="flex" justify="center">
      <el-col :xs="24" :md="12">
        <el-form ref="ruleForm" :rules="rules" :model="ruleForm" label-position="right" label-width="150px">
          <el-form-item label="分组或者操作名称" prop="title">
            <el-input v-model="ruleForm.title" placeholder="分组或者操作名称"/>
          </el-form-item>
          <el-form-item label="上级分组" prop="parent_id">
            <el-select v-model="ruleForm.parent_id" placeholder="请选择">
              <el-option
                v-for="item in options"
                :key="item.id"
                :label="item.title"
                :value="item.id"/>
            </el-select>
          </el-form-item>
        </el-form>
        <el-form ref="permissionForm" :rules="permissionrules" :model="permissionForm" label-position="right" label-width="150px">
          <el-form-item label="控制器" prop="controller">
            <el-select v-model="permissionForm.controller" placeholder="请选择" @change="getPermissionActionOptions">
              <el-option
                v-for="item in controllerOptions"
                :key="item.name"
                :label="item.name"
                :value="item.value"/>
            </el-select>
          </el-form-item>
          <el-form-item label="操作" prop="action">
            <el-select v-model="permissionForm.action" placeholder="请选择">
              <el-option
                v-for="item in actionOptions"
                :key="item.name"
                :label="item.name"
                :value="item.value"/>
            </el-select>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitPermissionForm('permissionForm')">添加权限码</el-button>
          </el-form-item>
        </el-form>
        <el-row type="flex" justify="center">
          <el-table
            :data="ruleForm.permissionCode"
            border>
            <el-table-column
              prop="controller_name"
              label="控制器名"/>
            <el-table-column
              prop="action_name"
              label="方法名"/>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-button
                  size="mini"
                  icon="el-icon-delete"
                  type="danger"
                  @click="handleDelete(scope.$index, scope.row)">删除</el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-row>
        <el-row type="flex" justify="center" class="block">
          <el-button type="success" @click="submitForm('ruleForm')">保存</el-button>
          <el-button @click="resetForm('ruleForm')">重置</el-button>
        </el-row>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import { permissionSrcDetail, permissionSrcEdit, permissionSrcOptions, permissionControllerOptions, permissionActionOptions } from '@/api/permission/permission_src'
export default {
  name: 'PermissionSrcEdit',
  data() {
    return {
      ruleForm: {
        title: '',
        parent_id: '',
        permissionCode: [],
        id: this.$route.query.id
      },
      permissionForm: {
        controller: '',
        action: ''
      },
      options: [
        {
          title: '请选择',
          id: ''
        },
        {
          title: '顶级分组',
          id: 0
        }
      ],
      controllerOptions: [
        {
          name: '请选择',
          value: ''
        }
      ],
      actionOptions: [
        {
          name: '请选择',
          value: ''
        }
      ],
      rules: {
        title: [
          { required: true, message: '分组或者操作名称', trigger: 'blur' },
          { min: 2, max: 10, message: '长度在 2 到 10 个字符', trigger: 'blur' }
        ],
        parent_id: [
          { required: true, message: '请选择上级分组', trigger: 'change' }
        ]
      },
      permissionrules: {
        controller: [
          { required: true, message: '请选择控制器', trigger: 'change' }
        ],
        action: [
          { required: true, message: '请选择操作', trigger: 'change' }
        ]
      }
    }
  },
  mounted() {
    this.getOptions()
    this.getPermissionControllerOptions()
    this.getPermissionSrcDetail()
  },
  methods: {
    getOptions() {
      permissionSrcOptions().then(res => {
        this.options = this.options.concat(res.data)
      }).catch(() => {
      })
    },
    getPermissionControllerOptions() {
      permissionControllerOptions().then(res => {
        this.controllerOptions = this.controllerOptions.concat(res.data)
      }).catch(() => {
      })
    },
    getPermissionSrcDetail() {
      permissionSrcDetail({ id: this.ruleForm.id }).then(res => {
        this.ruleForm = res.data
      }).catch(() => {
      })
    },
    getPermissionActionOptions(value) {
      if (value === '') {
        this.permissionForm.action = ''
        this.actionOptions = [
          {
            name: '请选择',
            value: ''
          }
        ]
        return
      }
      permissionActionOptions({ controller: value }).then(res => {
        this.actionOptions = [
          {
            name: '请选择',
            value: ''
          }
        ].concat(res.data)
        this.permissionForm.action = ''
      }).catch(() => {
      })
    },
    submitPermissionForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          try {
            this.ruleForm.permissionCode.forEach((value, index) => {
              // 已存在则提示
              if (value.controller_name === this.permissionForm.controller && value.action_name === this.permissionForm.action) {
                this.$message({
                  message: '该权限码已存在',
                  type: 'error'
                })
                throw new Error('该权限码已存在')
              }
            })
          } catch (e) {
            return false
          }
          this.ruleForm.permissionCode.push({ controller_name: this.permissionForm.controller, action_name: this.permissionForm.action })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    handleDelete(index, row) {
      this.ruleForm.permissionCode.splice(index, 1)
    },
    submitForm(formName) {
      const _this = this
      this.$refs[formName].validate((valid) => {
        if (valid) {
          permissionSrcEdit(this.ruleForm).then(res => {
            this.$message({
              message: '保存成功',
              type: 'success',
              onClose: function() {
                _this.$router.push('/permission/permission_src/permission_src_lst')
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
<style scoped>
  .permission_src_edit .block{
     margin-top: 20px;
  }
</style>

