import Sortable from '../../vendor/node_modules/sortablejs/modular/sortable.complete.esm.js';
// import { MultiDrag, Swap } from '../../vendor/node_modules/sortablejs';

var el = document.getElementById('items');
var sortable = new Sortable(el, {
    handle: '.handle',
    swap: true,
    group: 'nested',
    animation: 150,
    onSort: function (/**Event*/evt) {
        console.log(evt);
    },
});
