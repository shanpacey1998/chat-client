<<<<<<< HEAD
import $ from 'jquery';

$(document).ready(function() {

    //automatically scrolls to show most recent message in chat on refresh
    let objDiv = document.getElementById('message_box')
    objDiv.scrollTop = objDiv.scrollHeight

    document.getElementById('profile_form_imageFile').style.opacity = '100' //make label for profile file upload invisible

    document.getElementById('user_form_attachment').style.opacity = '100' //makes profile file upload elements visible

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

});
=======
document.getElementById('profile_form_imageFile').style.opacity = '100'; //make bootstrap label for profile file upload invisible
>>>>>>> a925a5dd926723b2d1f86a7925cd3833f1717528
