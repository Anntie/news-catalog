<template>
    <el-col :span="6">
        <h5>Menu</h5>
        <el-menu
                default-active="1"
                class="el-menu-vertical-demo"
                @select="handleChange">
            <el-menu-item index="1">
                <i class="el-icon-menu"></i>
                <span>Articles</span>
            </el-menu-item>
            <el-menu-item index="2">
                <i class="el-icon-setting"></i>
                <span>Logout</span>
            </el-menu-item>
        </el-menu>
    </el-col>
</template>

<script>
  export default {
    name: "Menu",

    props: {
      value: {
        type: Number,
        default: 1,
      }
    },

    methods: {
      handleChange (index) {
        this.$emit('input', index);
      }
    }
  }
</script>
