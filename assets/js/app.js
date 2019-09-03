import axios from 'axios';
import Vue from 'vue';
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css';
import slugify from './slugify';

Vue.prototype.$http = axios;
Vue.prototype.$slugify = slugify;
Vue.use(ElementUI, { locale });

import Admin from './components/Admin';

const app = new Vue({
  el: '#app',
  components: { Admin },
});
