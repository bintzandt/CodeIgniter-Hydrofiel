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

function getImage(id){
    var url = "https://graph.facebook.com/";
    var access_token = "/attachments?access_token=1787347374631170|MVXgVmEVfr8FjeOrCV_M-fP68Ys";
    $.getJSON(url + id + access_token, function (json) {
        if (json.data.length > 0) {
            $.each(json.data, function (i, media){
                if (media.media !== undefined) {
                    $('#' + id).html("<img class='img-responsive no_margin' src='" + media.media.image.src + "'>");
                }
                else if (media.subattachments !== undefined) {
                    $('#' + id).html("<img class='img-responsive no_margin' src='" + media.subattachments.data[0].media.image.src + "'>");
                }
            });
        }
        else {
            //console.debug(id);
            $('#' + id).remove();
            $('#txt_' + id).toggleClass('col-md-9 col-sm-12');
        }
    });
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

    if ($('#facebookfeed').length > 0) {
        // URL to access
        var url = "https://graph.facebook.com/hydrofiel/feed?access_token=1787347374631170|MVXgVmEVfr8FjeOrCV_M-fP68Ys&limit=7&fields=id,message&callback=?";
        // JSON request to get the data
        $.getJSON(url, function (json) {
            var html = "";
            //Add some formatting to the message
            $.each(json.data, function (i, fb) {
                if (fb.message != null) {
                    html += "<div class='container container-item'>" +
                        "<div class='col-md-3' id='" + fb.id + "'></div>" +
                        "<div class='col-md-9' id='txt_" + fb.id + "' align='left'>" +
                        "<p>" +
                        $('<div>').html(fb.message).text() +
                        "</p>" +
                        "</div>" +
                        "</div>" +
                        "<hr>";
                    getImage(fb.id);
                }
            });
            //Remove the last <hr>
            html = html.slice(0, -4);
            $('#facebookfeed').html(html);
        });
    }

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