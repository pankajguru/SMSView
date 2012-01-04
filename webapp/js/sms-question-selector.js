var basetype = "";
var base_url = 'http://smsview';
var school_id;

$(document).ready(function() {
    //set all pages to display:none
    $("div[data-role='page']").hide();

    $('#testpagebutton').click(function() {
        load_page('#testpage');
    });
    //init_login();
    init_login_temp();
    select_survey_type();

});
function load_page(page) {
    $("div[data-role='page']").hide();
    $(page).fadeIn(500);
}

function init_login_temp() {
	$.ajax({
		type: 'GET',
  		url: base_url + '/xmlprovider/questions/school_id',
  		dataType: 'xml',
  		success: function(xml){
    		$(xml).find('xml').each(function(){
    			school_id = $(this).find('school_id').text();
    		});
  		}
	});	
}

function init_login() {
    //see if we are already logged in
    $.post(base_url + "/index.php/auth/login", {}, function(data) {
        error = $(data).find(".error");
        //alert(data);
        if(error.length == 0) {
            $('#login').fadeOut(500, function() {
                $('#typechoice').fadeIn(500);
            });
        } else {
            load_page('#login');
        }
    });
    //create login function
    $('form#form_login').submit(function(e) {
        e.preventDefault();
        var name = $("#login_name").attr('value');
        var password = $("#login_password").attr('value');
        $.post(base_url + "/index.php/auth/login", {
            login : name,
            password : password
        }, function(data) {
            error = $(data).find(".error");
            //alert(data);
            if(error.length == 0) {
                $('#login').fadeOut(500, function() {
                    $('#typechoice').fadeIn(500);
                });
            } else {
                $("#error").html(error[0].innerHTML + error[1].innerHTML);
            }
        });
        return false;
    });
}

function select_survey_type() {
	$('#typechoice').fadeIn(500);
	wireTypeChange();
}

function retrieve_questions_per_type( type ) {
	var questions = new Array();

	$.ajax({
		type: 'GET',
  		url: base_url + '/xmlprovider/questions/all_questions/' + type,
  		dataType: 'xml',
  		success: function(xml){
    		$(xml).find('item').each(function() {
    			
    			var li = '<li class="ui-state-default" id="' + $(this).find('question_id').text() + '">' + $(this).find('question_description').text() + '</li>';
    			$(li).appendTo('#questions_container');
    		});
    		
    		createSorts();
  		}
	});
	
}

function wireTypeChange() {
	$('#select_type').change(function() {
		
		retrieve_questions_per_type( $(this).val() );
		
	});
}

function createSorts() {
	$('.sorts').sortable({
		connectWith: '.connectedSortable',
		update: function(event, ui) {
			var order = $(this).sortable('toArray').toString();
		}
	}).disableSelection();
}
