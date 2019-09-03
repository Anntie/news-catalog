<template>
    <el-row class="tac">
        <side-menu></side-menu>
        <el-col :span="18">
            <categories v-model="categories"></categories>
            <hr>
            <articles v-model="articles" :categories="categories"></articles>
        </el-col>
    </el-row>
</template>

<script>
  import SideMenu from './SideMenu';
  import Categories from './Categories';
  import Articles from './Articles';

  export default {
    name: "Admin",
    components: { Articles, Categories, SideMenu },
    props: {
      user: {
        type: Object,
        default: null,
      }
    },

    data: () => ({
      articles: [],
      categories: [],
      form: {},
      currentIndex: 1,
    }),

    computed: {},

    created () {
      this.$http.get('/admin/api/categories')
        .then(
          ({ data }) => this.categories = data.data
        );
      this.$http.get('/admin/api/articles')
        .then(
          ({ data }) => this.articles = data.data
        );
    },

    methods: {

    },
  }
</script>
