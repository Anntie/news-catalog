<template>
    <div class="articles">
        <el-table
                :data="value.filter(data => !search || data.title.toLowerCase().includes(search.toLowerCase()))"
                :default-sort="{prop: 'date', order: 'descending'}">
            <el-table-column
                    prop="id"
                    label="ID"
                    sortable>
            </el-table-column>
            <el-table-column
                    prop="title"
                    label="Title">
            </el-table-column>
            <el-table-column
                    prop="slug"
                    label="URL">
            </el-table-column>
            <el-table-column
                    align="right">
                <template slot="header" slot-scope="scope">
                    <el-input
                            v-model="search"
                            size="mini"
                            placeholder="Type to search"/>
                    <el-button class="button-new-tag" size="small" @click="showCreate">+ New Article</el-button>
                </template>
                <template slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="handleUpdate(scope.$index, scope.row)">
                        Edit
                    </el-button>
                    <el-button
                            size="mini"
                            type="danger"
                            @click="handleDelete(scope.$index, scope.row)">
                        Delete
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog :title="popupTitle" :visible.sync="dialogFormVisible" top="6vh">
            <el-form :model="form" @submit.native.prevent>
                <el-form-item label="Title">
                    <el-input v-model="form.title" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="URL">
                    <el-input v-model="form.slug" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="Category">
                    <el-select
                            v-model="form.category"
                            filterable
                            :filter-method="filterCategories"
                            placeholder="Please enter a keyword">
                        <el-option
                                v-for="category in filteredCategories"
                                :key="category.id"
                                :label="category.title"
                                :value="category.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Short Description">
                    <el-input
                            type="textarea"
                            :rows="3"
                            placeholder="Type here..."
                            v-model="form.short_description">
                    </el-input>
                </el-form-item>
                <el-form-item label="Description">
                    <el-input
                            type="textarea"
                            :rows="5"
                            placeholder="Type here..."
                            v-model="form.description">
                    </el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="closePopup">Cancel</el-button>
                <el-button type="primary" @click="submitHandler">Confirm</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
  const defaultForm = {
    title: '',
    slug: '',
    short_description: '',
    description: '',
    image: '',
    category: null,
  };

  export default {
    name: "Articles",

    props: {
      value: {
        type: Array,
        default: () => ([]),
      },
      categories: {
        type: Array,
        default: () => ([]),
      },
    },

    data: () => ({
      search: '',
      filteredCategories: [],
      dialogFormVisible: false,
      popupTitle: '',
      form: defaultForm,
      submitHandler: () => {},
    }),

    computed: {
      articleTitle () {
        if (this.dialogFormVisible) {
          return this.form.title;
        }
        return null;
      },
    },

    watch: {
      articleTitle () {
        if (this.articleTitle) {
          this.form.slug = this.$slugify(this.articleTitle);
        }
      },
    },

    methods: {
      filterCategories (query) {
        this.filteredCategories = this.categories.filter(
          ({ title }) => title.toLowerCase().indexOf(query.toLowerCase()) > -1
        );
      },

      showPopup () {
        this.dialogFormVisible = true;
      },

      closePopup () {
        this.form = defaultForm;
        this.submitHandler = () => {};
        this.dialogFormVisible = false;
      },

      showCreate () {
        this.popupTitle = 'Create Article';
        this.submitHandler = this.handleCreate;
        this.showPopup();
      },

      handleCreate () {
        this.$http.post('/admin/api/articles', this.form)
          .then(({ data }) => {
            this.value.push(data.data);
            this.$emit('input', this.value);
            this.closePopup();
          })
          .catch(error => console.log(error));
      },

      handleDelete (index, row) {
        console.log(index, row);
      },

      handleUpdate (index, row) {
        this.popupTitle = 'Update Article';
        console.log(index, row);
      },
    },
  }
</script>

<style scoped>

</style>