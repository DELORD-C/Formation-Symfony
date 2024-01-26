import './styles/app.scss';
import 'datatables.net-dt/css/jquery.dataTables.min.css';

require('bootstrap');
require('./js/ajax');

require('datatables.net-dt')

const $ = require('jquery');

global.$ = global.jQuery = $;
