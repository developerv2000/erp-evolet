/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import axios from 'axios';
import '../custom-components/script';
import { initializeAll as initializeAllPlugins } from './plugins';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/*
|--------------------------------------------------------------------------
| Initialization
|--------------------------------------------------------------------------
*/

function init() {
    // Initializes all plugin components.
    initializeAllPlugins();
}

init();
