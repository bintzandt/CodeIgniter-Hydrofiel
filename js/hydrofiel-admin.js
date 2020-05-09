jQuery(document).ready(function($) {
    $(function() {
        $('#summernote').summernote({
            // unfortunately you can only rewrite
            // all the toolbar contents, on the bright side
            // you can place uploadcare button wherever you want
            height: 350,
            toolbar: [
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table', 'picture']],
                ['insert', ['link', 'hr']],
                ['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ]
        });
    });
    $(function() {
        $('#engels').summernote({
            // unfortunately you can only rewrite
            // all the toolbar contents, on the bright side
            // you can place uploadcare button wherever you want
            height: 350,
            toolbar: [
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table', 'picture']],
                ['insert', ['link', 'hr']],
                ['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ]
        });
	});

	$('#los').multiselect({
		enableCaseInsensitiveFiltering: true,
		maxHeight: 300,
		inheritClass: false,
		buttonWidth: '100%',
		numberDisplayed: 5,
		optionClass: function(element) {
			return 'multi';
		}
	});
} );

function showModal(){
	var aan = "";
	var email = "";
	var names = "";
	var str = "";
	if ($('#aan option:selected').val()!=='select'){
		aan = "De mail wordt naar de groep " + $('#aan option:selected').text() + ' gestuurd.<br><br>';
	}
	if ($('#email').val() !== ""){
		email = "De mail wordt ook naar de volgende adressen gestuurd:<br>" + $('#email').val() + ".<br><br>";
	}
	$('#los option:selected').each(function() {
		// concat to a string with comma
		names += $(this).text() + ", ";
	});
	// trim comma and white space at the end of string
	if (names!=="") {
		names = names.slice(0, -2);
		names += ".";
		str = "De mail wordt ook naar de volgende personen gestuurd:<br>" + names;
	}

	showBSModal({
		title: "Controleer gegevens",
		body: aan + email + str,
		actions: [{
			label: 'Verstuur',
			cssClass: 'btn-primary',
			onClick: function(e){
				$("#postForm").submit();
			}
		},{
			label: 'Annuleer',
			cssClass: 'btn-warning',
			onClick: function(e){
				$(e.target).parents('.modal').modal('hide');
			}
		}]
	});
}
