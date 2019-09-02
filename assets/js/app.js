import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

Vue.use(ElementUI);

import Admin from './components/Admin';

const app = new Vue({
  el: '#app',
  components: { Admin },
});
