var basetype = "";
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

function expand_all() {
	$('<button id="expand" />').text('open').appendTo('#list_controls');
	
	$('#expand').click(function() {
		( $( this ).text() === 'open' ) ? $( this ).text('close') : $( this ).text('open');
		( $( this ).text() === 'open' ) ? $( '.question_not_selected' ).addClass('hide') : $( '.question_not_selected' ).removeClass('hide');
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
    			
    			var li = '<li title="' + $(this).find('category_name').text() + '" class="ui-state-default hide question_not_selected" id="' + $(this).find('question_id').text() + '">' + $(this).find('question_description').text() + '</li>';
    			$(li).appendTo('#questions_container');
    		});
    		sort_on_category();
    		
    		//get_categories();
    		expand_all();
    		createSorts();
  		}
	});
	
}

function wireTypeChange() {
	$('#select_type').change(function() {
		retrieve_questions_per_type( $(this).val() );
		$('#survey_type').remove();
	});
}

function createSorts() {
	$('.sorts').sortable({
		connectWith: '.connectedSortable',
		update: function(event, ui) {
			if ( $( this ).attr( 'id' ) === 'question_list_container' ) {
				ui.item.removeClass('question_not_selected');
				var order = $(this).sortable('toArray').toString();
				console.log(order);
			}
			else {
				ui.item.addClass('question_not_selected');
			}
		},
		stop: function(event, ui) {
			
			var list = $('#question_list_container > li.ui-state-default' );
			if ( list.length >= 1 ) {
				$('#select_info').remove();
				$('#clear_questions').toggleClass('hide');
			}
			else {
				if ( $( '#select_info' ).length === 0 ) {
					$('<li id="select_info" class="info error">Sleep hier uw vragen heen</li>').appendTo('#question_list_container');
				}
				$('#clear_questions').toggleClass('hide');
			}
		}
	}).disableSelection();
	
	filter_questions();
}

function filter_questions() {
	$('#filter_field').keyup( function() {
		var re = $('#filter_field').val();

		$('.question_not_selected').each( function() {
			
			var str = $(this).text();
			var match = str.search(re);

			if ( match == -1) {
				$(this).addClass('hide');
			}
			else {
				$(this).removeClass('hide');
			}
		});
	});
}

function sort_on_category() {

	var groups = [];
	
	$('#questions_container > li').each( function() {
    	var li			= $( this );
    	var title		= $( this ).attr('title');
    	var li_group	= 'list' + $(this).attr('title');

		if( !groups[ li_group ] ) {
      		groups[ li_group ] = [];
      		var first_li = $('<li title="' + title + '" class="category_name" />').text( title );
      		groups[ li_group ].push( first_li );
      	}
      		groups[ li_group ].push( li );
	});
  
 	for( group in groups ) {
		var ul = $('<ul class="connectedSortable ui-sortable sorts" />').attr( 'id', group );
    	var lis = groups[ group ];
    
		for( i = 0; i < lis.length; i++ ){
    		ul.append( lis[ i ] );
    	}
    	ul.appendTo('#questions_container');
  	}
  	
  	create_clicks();
}

function create_clicks() {
	$('.category_name').click( function() {
		$( this ).parent().children('.ui-state-default').toggleClass('hide');
	});
}

function new_question() {
	$('')
}

//function sort_on_category( a, b ) {
//    return $(a).attr('title').toLowerCase() > $(b).attr('title') ? 1 : -1;
//};