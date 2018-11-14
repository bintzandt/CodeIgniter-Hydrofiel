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
			$('#' + id).remove();
			$('#txt_' + id).remove();
			$('#hr_' + id).remove();
		}
	});
}

jQuery(document).ready(function($) {
	if ($('#facebookfeed').length > 0) {
		// URL to access
		var url = "https://graph.facebook.com/273619436014416?access_token=1787347374631170|MVXgVmEVfr8FjeOrCV_M-fP68Ys&fields=posts.limit(5)";
		// JSON request to get the data
		$.getJSON(url, function (json) {
			var html = "";
			//Add some formatting to the message
			$.each(json.posts.data, function (i, fb) {
				if (fb.message != null) {
					html += "<div class='container container-item'>" +
						"<div class='col-md-3' id='" + fb.id + "'></div>" +
						"<div class='col-md-9' id='txt_" + fb.id + "' align='left'>" +
						"<p>" +
						$('<div>').html(fb.message).text() +
						"</p>" +
						"</div>" +
						"</div>" +
						"<hr id='hr_" + fb.id + "'>";
					getImage(fb.id);
				}
			});
			//Remove the last <hr>
			html = html.slice(0, -4);
			$('#facebookfeed').html(html);
		});
	}
});