import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';
import './styles/style.css';

//console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

require('bootstrap-icons/font/bootstrap-icons.css');
const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
//const $ = require('jquery');
/*On sélectionne tous les éléments avec un attribut data-toggle="popover"*/

/*On sélectionne tous les éléments avec un attribut data-toggle="tooltip"*/
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })