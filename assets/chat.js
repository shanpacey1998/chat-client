import $ from 'jquery';

$(document).ready(function() {

    document.getElementById('profile_form_imageFile').style.opacity = '100' //make label for profile file upload invisible

    document.getElementById('user_form_attachment').style.opacity = '100' //makes profile file upload elements visible

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

});