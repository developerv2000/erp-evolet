/*
|--------------------------------------------------------------------------
| Necessary dependencies
|--------------------------------------------------------------------------
*/

import axios from 'axios';
import '../custom-components/script';
import { initializeAll as initializePlugins } from './plugins';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/*
|--------------------------------------------------------------------------
| Initialization
|--------------------------------------------------------------------------
*/

function init() {
    // Initializes all plugin components.
    initializePlugins();
}

init();
