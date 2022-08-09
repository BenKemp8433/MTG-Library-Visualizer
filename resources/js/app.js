import {createApp} from 'vue';
import App from './App.vue';

// Import Components
import Nav from './components/Nav.vue';
import LibraryList from './components/LibraryList.vue';
import LibraryVisuals from './components/LibraryVisuals.vue';
import Sidebar from './components/Sidebar.vue';

// Import Subcomponents
import Icon from './components/subcomponents/Icon.vue';
import Tooltip from './components/subcomponents/Tooltip.vue';
import Injector from './components/subcomponents/Injector.vue';

// Hook Axios to be accessible
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp({});

app.component('v-app', App);

// Assign Components to custom elements
app.component('v-nav', Nav);
app.component('v-library-list', LibraryList);
app.component('v-library-visuals', LibraryVisuals);
app.component('v-sidebar', Sidebar);

// Assign Subcomponents to custom elements
app.component('v-icon', Icon);
app.component('v-tooltip', Tooltip);
app.component('v-injector', Injector);

app.mount('#app');
