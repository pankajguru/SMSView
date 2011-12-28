var loggedin = false;
var basetype = "";

$(document).ready(function() {
    //set all pages to display:none
    $("div[data-role='page']").css("display", "none");

    $('#testpagebutton').click(function() {
        load_page('#testpage');
    });
    //if logged in, goto choose type or if type already chosen, go to main page else go to login
    if(loggedin) {
        if(basetype != "") {
            load_page('#main');
        } else {
            load_page('#typechoice');
        }
    } else {
        load_page('#login');
    }

});
function load_page(page) {
    $("div[data-role='page']").css("display", "none");
    $(page).css("display", "block");
}