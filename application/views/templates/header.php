<!DOCTYPE HTML>
<html lang="<?= $this->session->english ? "en" : "nl" ?>">
<head>
	<base href="<?= base_url() ?>">

	<title>N.S.Z.&W.V. Hydrofiel</title>

	<!--    meta tags-->
	<meta name="keywords"
	      content="Hydrofiel, Studenten, Nijmeegse, Vereniging, zwem, zwemmen, waterpolo, Nijmegen, gofferttoernooi, nijmegen, gezellig" />
	<meta name="title" content="Nijmeegse Studenten Zwem- en Waterpolo Vereniging" />
	<meta name="description"
	      content="N.S.Z.&W.V.Hydrofiel is dé Nijmeegse Studenten Vereniging voor Zwemmen en Waterpolo." />
	<meta property="og:title" content="Nijmeegse Studenten Zwem- en Waterpolo Vereniging" />
	<meta property="og:description"
	      content="N.S.Z.&W.V.Hydrofiel is dé Nijmeegse Studenten Vereniging voor Zwemmen en Waterpolo." />
	<meta property="og:image" content="/android-icon-192x192.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<!-- icon -->
	<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<!--	<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">-->
	<!--	<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>-->
	<script src="/assets/jquery.js"></script>

	<script src="/assets/bootstrap.js"></script>
	<link rel="stylesheet" href="/assets/bootstrap.css">

	<script src="/assets/webauthnauthenticate.js"></script>
	<script src="/assets/webauthnregister.js"></script>

	<script src="/assets/hydrofiel.js"></script>

	<!--        css-->
	<link rel="stylesheet" href="/assets/hydrofiel.css" />

	<script>
		document.addEventListener( "DOMContentLoaded", function() {
			var lazyImages = [].slice.call( document.querySelectorAll( "img.lazy" ) );

			if ( "IntersectionObserver" in window ) {
				var lazyImageObserver = new IntersectionObserver( function( entries, observer ) {
					entries.forEach( function( entry ) {
						if ( entry.isIntersecting ) {
							var lazyImage = entry.target;
							lazyImage.src = lazyImage.dataset.src;
							lazyImage.srcset = lazyImage.dataset.srcset;
							lazyImage.classList.remove( "lazy" );
							lazyImageObserver.unobserve( lazyImage );
						}
					} );
				} );

				lazyImages.forEach( function( lazyImage ) {
					lazyImageObserver.observe( lazyImage );
				} );
			} else {
				// Possibly fall back to a more compatible method here
			}
		} );
	</script>
</head>
<body>
<div class="container-fluid" id="content">