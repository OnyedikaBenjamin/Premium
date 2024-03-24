
window.Vue = require('vue').default;
import Snackbar from 'node-snackbar/src/js/snackbar.js';
import 'node-snackbar/src/sass/snackbar.sass';

Vue.use(Snackbar);

export const displayMessage = (message) => {
    return Snackbar.show({ text: message, pos: 'bottom-center', duration: 10000 });
}
export const displayError = (message) => {
    return Snackbar.show({ text: message, pos: 'bottom-center', backgroundColor: '#f5365c', actionTextColor: '#fff', duration: 10000 });
}
