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
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

    $("img").each(function(){
        $(this).addClass("img-fluid");
    });
});

$(document).on('click',function(){
	$('.navbar-collapse').collapse('hide');
});

const pressed = [];
const secret = [ "ArrowUp", "ArrowUp", "ArrowDown", "ArrowDown", "ArrowLeft", "ArrowRight", "ArrowLeft", "ArrowRight" ];
let cornifyLoaded = false;

function magic(){
	document.documentElement.style.setProperty('--background-color', 'magenta' );
	document.documentElement.style.setProperty('--primary-color', 'magenta' );
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
