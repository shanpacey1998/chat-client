/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.less';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/app.js');

document.getElementById('profile_form_imageFile').style.opacity = '100'; //make bootstrap label for profile file upload invisible

document.getElementById('user_form_attachment').style.opacity = '100'; //makes profile file upload elements visible

//automatically scrolls to show most recent message in chat on refresh
let objDiv = document.getElementById("message_box");
objDiv.scrollTop = objDiv.scrollHeight;