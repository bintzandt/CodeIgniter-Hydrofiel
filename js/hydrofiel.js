function hideAll(){
    $('.inschrijving').addClass('d-none');
    $('#show_all').removeClass('d-none');
    $('#hide_all').addClass('d-none');
}

function showAll(){
    $('.inschrijving').toggleClass('d-none', false);
    $('#show_all').toggleClass('d-none', true);
    $('#hide_all').toggleClass('d-none', false);
}

function toggleInschrijf(val){
	if (val==="1") {
		$('#inschrijfdeadline').toggleClass('d-none', false);
		$('#afmelddeadline').toggleClass('d-none', false);
	}
	else {
		$('#afmelddeadline').toggleClass('d-none', true);
		$('#inschrijfdeadline').toggleClass('d-none', true);
	}
}

jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

    $("img").each(function(){
        $(this).addClass("img-fluid");
	});
	
	$("#info").click( () => window.location = "/" );
});

$(document).on('click',function(){
	$('.navbar-collapse').collapse('hide');
});

const pressed = [];
const secret = [ "ArrowUp", "ArrowUp", "ArrowDown", "ArrowDown", "ArrowLeft", "ArrowRight", "ArrowLeft", "ArrowRight" ];
let cornifyLoaded = false;

function magic(){
	document.documentElement.style.setProperty('--background-color', 'magenta' );
	document.documentElement.style.setProperty('--primary-color', '#982cd5');
	document.documentElement.style.setProperty('--footer-color', 'pink' );
	if ( cornifyLoaded ) cornify_add();
}

window.addEventListener('keyup', e => {
	pressed.push( e.key );
	pressed.splice( -secret.length - 1, pressed.length - secret.length );
	if ( pressed.join("").includes( secret.join("") ) ){
		if ( ! cornifyLoaded ){
			const script = document.createElement('script');
			script.onload = () => {
				cornifyLoaded = true;
				magic();
			}
			script.src = "https://www.cornify.com/js/cornify.js";
			document.head.appendChild(script);
		}
		magic();
	}
} );
