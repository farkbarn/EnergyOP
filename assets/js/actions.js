$(document).ready(function() {
	//cambio de datos en idioma
		const $es='/es/';
		const $en='/en/';
	//	$url=window.location.href;
	//	$path=window.location.path;
		$url=$(location).attr('href');
		$path=$(location).attr('path');

		if ($path==$es) {
			$('#btn_about_us').text('Sobre Nosotros');
			$('#btn_contact').text('Contacto');
		}
	
});

