function hideAll(){
    $('.inschrijving').toggleClass('hidden', true);
    $('.show_all').toggleClass('hidden', false);
    $('.hide_all').toggleClass('hidden', true);
}

function showAll(){
    $('.inschrijving').toggleClass('hidden', false);
    $('.show_all').toggleClass('hidden', true);
    $('.hide_all').toggleClass('hidden', false);
}

function toggleInschrijf(val){
	if (val==="1") {
		$('#inschrijfdeadline').toggleClass('hidden', false);
		$('#afmelddeadline').toggleClass('hidden', false);
	}
	else {
		$('#afmelddeadline').toggleClass('hidden', true);
		$('#inschrijfdeadline').toggleClass('hidden', true);
	}
}

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

    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });


    $("h1").each(function(){
        $(this).addClass("oranje_tekst");
    });
    $("h3").each(function(){
        $(this).addClass("oranje_tekst");
    });
    $("h4").each(function(){
        $(this).addClass("oranje_tekst");
    });
    $("img").each(function(){
        $(this).addClass("img-responsive");
    });
});