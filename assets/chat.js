import $ from 'jquery';

$(document).ready(function() {

    document.getElementById('profile_form_imageFile').style.opacity = '100'; //make label for profile file upload invisible

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }


});