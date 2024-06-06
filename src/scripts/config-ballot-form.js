import { initializeConfigurationJS as ConfigJS } from './configuration.js';
import Sortable from '../../vendor/node_modules/sortablejs/modular/sortable.complete.esm.js';
// import { MultiDrag, Swap } from '../../vendor/node_modules/sortablejs';

var el = document.getElementById('items');
var sortable = new Sortable(el, {
    handle: '.handle',
    filter: 'add-item',
    group: 'nested',
    multiDrag: true, // Enable the plugin
    selectedClass: "sortable-selected", // Class name for selected item
    avoidImplicitDeselect: false,
    animation: 150,
    onSort: function (/**Event*/evt) {
        console.log(evt);
    },
});

// Sortable.create(list, {
//     multiDrag: true,
//     selectedClass: "selected"
// });
