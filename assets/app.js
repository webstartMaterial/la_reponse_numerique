/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
// start the Stimulus application
import './bootstrap';

// loads the jquery package from node_modules
import $ from 'jquery';
import 'bootstrap';
require('bootstrap');


require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

let app = {};

(function () {


    //
    //
    // COPY TO CLIPBOARD
    // 
    //

    $(".btn-clipboard").click(function() {

            // Get the text field
            var copyText = document.getElementById("clipboard");
            console.log(copyText);

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);
            $(".btn-clipboard").html("COPIÉ!")

    });


    $('#search').keypress(function (e) {
        if (e.which == 13) {
          $('.form-search').submit();
          return false;    //<---- Add this line
        }
      });


    $("article").hover(function() {
        $(this).find("h4").addClass("underline");
    }, function() {
        $(this).find("h4").removeClass("underline");
    });

    $(window).scroll(function() {

        let percentageViewed = ($(this).scrollTop() / $(".detail-article").height() ) * 100;
        $(".progression-value").width(percentageViewed + "%");
    });

    $(document).ready(function () {
        console.log("toto");
    });

}());