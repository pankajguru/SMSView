$(document).ready(function() {
	get_accounts();
	wireClientChange();

});

function wireClientChange() {
	$('#client').change(function() {
		get_files($(this).val());
	});
}

function get_accounts() {
	$.ajax({
		type : 'GET',
		url : base_url + '/xmlprovider/questions/accounts_admin',
		dataType : 'xml',
		success : function(xml) {
			$(xml).find('item').each(function() {
				var id = $(this).find('id').text();
                var email = $(this).find('email').text();
                var brin = $(this).find('brin').text();
				$('#client').append($("<option></option>").attr("value", id).text(brin + ' ' + email));

			});
		},
	});
}

function get_files(id) {
	$('#filename').empty();
	if (id != 0) {
		$.ajax({
			type : 'GET',
			url : base_url + '/xmlprovider/questions/saved_questionaires_admin/' + id,
			dataType : 'xml',
			success : function(xml) {
				$('#filename').append($("<option></option>").attr("value", 0).text('Kies de vragenlijst'));
				$(xml).find('item').each(function() {
					var filename = $(this).text();
					$('#filename').append($("<option></option>").attr("value", filename).text(filename));
				});
			},
		});
	}
}
