<?php

function _error_handler($severity, $message, $filepath, $line) {
	$is_error = (((E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $severity) === $severity);

	if ( $is_error ) set_status_header(500);

	//if (($severity & error_reporting()) !== $severity) return;

	if (str_ireplace(array('off', 'none', 'no', 'false', 'null'), '', ini_get('display_errors'))) {
		show_php_error($severity, $message, $filepath, $line);
	}

	if ( $is_error ) {
		exit(1); 
	}
}

function show_php_error($severity, $message, $filepath, $line) {
	
	$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;

	$severity = levels_code( $severity, $severity );

	$filepath = str_replace('\\', '/', $filepath);
	if (FALSE !== strpos($filepath, '/')) {
		$x = explode('/', $filepath);
		$filepath = $x[count($x)-2].'/'.end($x);
	}

	$template = 'html'.DIRECTORY_SEPARATOR.'error_php';

	if (ob_get_level() > ob_get_level() + 1) ob_end_flush();
	
	ob_start();
	include($templates_path.$template.'.php');
	$buffer = ob_get_contents();
	ob_end_clean();
	echo $buffer;
}

function set_status_header($code = 200, $text = '') {
	if (empty($code) OR ! is_numeric($code)) show_error('Holat kodi raqam shaklida boʻlishi lozim', 500);

	if (empty($text)) {
		is_int($code) OR $code = (int) $code;
		$stati = [
			100	=> 'Davom etish',
			101	=> 'Protokollarni almashtirish',

			200	=> 'OK',
			201	=> 'Yaratildi',
			202	=> 'Qabul qilindi',
			203	=> 'Vakolatli bo\'lmagan ma\'lumot',
			204	=> 'Kontent mavjud emas',
			205	=> 'Kontentni tiklash',
			206	=> 'Qisman tarkib',

			300	=> 'Bir nechta tanlov',
			301	=> 'Doimiy ko\'chirildi',
			302	=> 'Topildi',
			303	=> 'B.qga qarang',
			304	=> 'Oʻzgartirilmagan',
			305	=> 'Proksi-serverdan foydalaning',
			307	=> 'Vaqtinchalik qayta yo\'naltirish',

			400	=> 'Noto\'g\'ri so\'rov',
			401	=> 'Ruxsat berilmagan',
			402	=> 'To\'lov talab qilinadi',
			403	=> 'Taqiqlangan',
			404	=> 'Mavjud emas',
			405	=> 'Ruxsat berilmagan usul',
			406	=> 'Qabul qilinmaydi',
			407	=> 'So\'rovni kutish vaqti tugashi',
			408	=> 'So\'rov muddati',
			409	=> 'Ziddiyat',
			410	=> 'Resurs mavjud emas',
			411	=> 'Uzunlik talab qilinadi',
			412	=> 'Old shart amalga oshmadi',
			413	=> 'So\'rov ob\'ekti juda katta',
			414	=> 'URI soʻrovi juda uzun',
			415	=> 'Qo\'llab-quvvatlanmaydigan media turi',
			416	=> 'So\'ralgan diapazon qoniqtirilmaydi',
			417	=> 'Kutish amalga oshmadi',
			422	=> 'Ishlov berilmaydigan ob\'ekt',
			426	=> 'Yangilash talab qilinadi',
			428	=> 'Old shart talab qilinadi',
			429	=> 'Juda koʻp soʻrovlar',
			431	=> 'Soʻrov sarlavhasi maydonlari juda katta',

			500	=> 'Serverdagi ichki xatolik',
			501	=> 'Amalga oshirilmagan',
			502	=> 'Shlyuzdan javob olishda xatolik',
			503	=> 'Servis ish faoliyatida emas',
			504	=> 'Shlyuz vaqti tugadi',
			505	=> 'HTTP versiyasi qoʻllab-quvvatlanmaydi',
			511	=> 'Tarmoq indenfikatsiyasi talab qilinadi',
		];

		if ( isset($stati[$code]) ) {
			$text = $stati[$code];
		} else {
			show_error('Ushbu holat uchun hech qanday matn mavjud emas. Iltimos, holat kodi raqaminni tekshiring yoki maqbul xabar matnini kiriting.', 500);
		}
	}

	if (strpos(PHP_SAPI, 'cgi') === 0) {
		header('Status: '.$code.' '.$text, TRUE);
		return;
	}

	$server_protocol = (isset($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.0', 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0'), TRUE))
		? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
	header($server_protocol.' '.$code.' '.$text, TRUE, $code);
}


function show_error($message, $status_code = 500, $heading = 'Xatolik yuzaga keldi') {
	$status_code = abs($status_code);
	if ($status_code < 100) {
		$exit_status = $status_code + 9;
		$status_code = 500;
	} else {
		$exit_status = 1; 
	}
	echo show_error_template($heading, $message, 'error_general', $status_code);
	exit($exit_status);
}

function show_error_template($heading, $message, $template = 'error_general', $status_code = 500) {
	$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;

	set_status_header( $status_code );
	$message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
	$template = 'html'.DIRECTORY_SEPARATOR.$template;

	if (ob_get_level() > ob_get_level() + 1) {
		ob_end_flush();
	}
	ob_start();
	include($templates_path.$template.'.php');
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}

function levels_code( $level, $severity ) {
	$levels = [
		E_ERROR				=>	'Xatolik',
		E_WARNING			=>	'Ogohlantirish',
		E_PARSE				=>	'Tahliliy xatolik',
		E_NOTICE			=>	'Eslatma',
		E_CORE_ERROR		=>	'Asosiy xatolik',
		E_CORE_WARNING		=>	'Asosiy ogohlantirish',
		E_COMPILE_ERROR		=>	'Kompilyatsiya xatosi',
		E_COMPILE_WARNING	=>	'Kompilyatsiya ogohlantirishi',
		E_USER_ERROR		=>	'Foydalanuvchi xatosi',
		E_USER_WARNING		=>	'Foydalanuvchi ogohlantirishi',
		E_USER_NOTICE		=>	'Foydalanuvchi eslatmasi',
		E_STRICT			=>	'Ish jarayoni bildirishnoma'
	];

	return ( isset( $levels[ $level ] ) ) ? $levels[ $level ] : $severity;
}

function route( $regex, $callback ) {
	$regex = '/^' . str_replace( '/', '\/', $regex ) . '$/';
	if ( preg_match( $regex, strtok($_SERVER['REQUEST_URI'], '?'), $params ) ) {
		//array_shift($params);
		call_user_func_array( $callback, array_values( $params ) );
		return TRUE;
	}

	return FALSE;
}

function show_404() {
	$heading = 'Sahifa mavjud emas';
	$message = 'Siz murojaat qilgan sahifa mavjud emas.';
	echo show_error_template($heading, $message, 'error_404', 404);
	exit(4);
}

function config_item($item) {
	if ( !empty( $GLOBALS['config'] ) ) {
		return isset($GLOBALS['config'][$item]) ? $GLOBALS['config'][$item] : NULL;
	}

	return FALSE;
}

function load_view( $path, $vars=[] ) {
	ob_start();
		extract( $vars );
		include(VIEWPATH. $path . '.php');
		$buffer = ob_get_contents();
	ob_end_clean();
	echo $buffer;
}
if ( ! function_exists( '_sanitize_globals' ) ) {
	function _sanitize_globals() {
		if ( is_array( $_GET ) ) {
			foreach ( $_GET as $key => $val ) {
				$_GET[ xss_clean( $key ) ] = xss_clean( $val );
			}
		}

		if ( is_array( $_POST ) ) {
			foreach ( $_POST as $key => $val ) {
				$_POST[ xss_clean( $key ) ] = xss_clean( $val );
			}
		}

		if ( is_array( $_SERVER ) ) {
			foreach ( $_SERVER as $key => $val ) {
				$_SERVER[ xss_clean( $key ) ] = xss_clean( $val );
			}
		}

		if ( is_array( $_COOKIE ) ) {
			unset(
				$_COOKIE[ '$Version' ],
				$_COOKIE[ '$Path' ],
				$_COOKIE[ '$Domain' ]
			);

			foreach ( $_COOKIE as $key => $val) {
				if ( ( $cookie_key = xss_clean( $key ) ) !== FALSE ) {
					$_COOKIE[ $cookie_key ] = xss_clean( $val );
				}else {
					unset( $_COOKIE[ $key ] );
				}
			}
		}

		$_SERVER['PHP_SELF'] = strip_tags( $_SERVER['PHP_SELF'] );
	}
}
