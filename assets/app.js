/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

var $ = require("jquery");


const str = $(".drop-content").text();
console.log(str);
const newStr = str.replace("^\"|\"$", "");
$('.drop-content').html(newStr);
$('.drop-content').css('display', 'flex');
$('.spinner-card').css('display', 'none');

console.log($('.drop-content').html(newStr));


var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

