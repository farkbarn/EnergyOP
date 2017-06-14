$(document).ready(function() {
	//cambio de datos en idioma
		//ES_es
		const $es='/es/';

		//EN_en
		const $en='/en/';
	//	$url=window.location.href;
	//	$path=window.location.path;
		$url=$(location).attr('href');
		$path=$(location).attr('path');

		if ($path==$es) {
			$('#btn_about_us').text('Sobre Nosotros');
			$('#btn_contact').text('Contacto');
		}

		if ($path==$en) {
			$('#btn_about_us').text('Sobre Nosotros');
			$('#btn_contact').text('Contacto');
		}
	
});

