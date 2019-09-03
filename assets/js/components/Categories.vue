<template>
    <div class="categories">
        <el-tag
                :key="category.id"
                v-for="category in value"
                closable
                :disable-transitions="false"
                @close="handleDelete(category)">
            {{ category.title }}
        </el-tag>
        <el-input
                class="input-new-tag"
                v-if="categoriesInputVisible"
                v-model="categoriesInputValue"
                ref="saveTagInput"
                size="mini"
                @keyup.enter.native="handleInputConfirm"
                @blur="handleInputConfirm"
        >
        </el-input>
        <el-button v-else class="button-new-tag" size="small" @click="showInput">+ New Category</el-button>
    </div>
</template>

<script>
  export default {
    name: "Categories",

    props: {
      value: {
        type: Array,
        default: () => ([]),
      },
    },

    data: () => ({
      categoriesInputVisible: false,
      categoriesInputValue: '',
    }),

    methods: {
      handleDelete (category) {
        const index = this.value.indexOf(category);
        const removed = this.value[index];
        this.$http.delete('/admin/api/categories/' + removed.id)
          .then(({ data }) => {
            if (data.success) {
              this.value.splice(index, 1);
              this.$emit('input', this.value);
            }
          })
          .catch(error => console.log(error));
      },

      showInput () {
        this.categoriesInputVisible = true;
        this.$nextTick(() => {
          this.$refs.saveTagInput.$refs.input.focus();
        });
      },

      handleInputConfirm () {
        let inputValue = this.categoriesInputValue;
        if (inputValue) {
          let category = {title: inputValue};
          this.$http.post('/admin/api/categories', category)
            .then(({ data }) => {
              this.value.push(data.data);
              this.$emit('input', this.value);
            })
            .catch(error => console.log(error));
        }
        this.categoriesInputVisible = false;
        this.categoriesInputValue = '';
      },
    },
  }
</script>

<style scoped>
    .el-tag + .el-tag {
        margin-left: 10px;
    }

    .button-new-tag {
        margin-left: 10px;
        height: 32px;
        line-height: 30px;
        padding-top: 0;
        padding-bottom: 0;
    }

    .input-new-tag {
        width: 90px;
        margin-left: 10px;
        vertical-align: bottom;
    }
</style>
