---
toc: false
title: "Php dasturlash tilida o'yin yaratish: 2-qism"
description: "Buyruqlar qatoriga mos keladigan o'yinlardan eng soddalaridan biri bu Tic Tac Toe bo'lsa kerak..."
keywords: "php, o'yin, game, cli, stdin, qism, qismlar, yaratish"
---

Ushbu qo'llanmaning birinchi qismidan mavjudki bizda foydalanuvchilar tugmalarga daxldor hodisalarni qabul qilish yechimi mavjud. Keyingi navbatda esa soddaroq shakldagi o'yin algoritmini ishlab chiqish hisoblanadi.

Buyruqlar qatoriga mos keladigan o'yinlardan eng soddalaridan biri bu **Tic Tac Toe** bo'lsa kerak. Ushbu o'yin sodda shaklda bo'lib unda o'yin maydoni, harakatlarni belgilash va go'libni aniqlash kabi holatlarni ishlab chiqish bizga jarayonni boshlab olish uchun ancha oson kechadi.

Tic tac toe ikki kishilik o'yin, unga ayni damda ikkinchi shaxs sifatida sun'iy idrokga daxldor kodlar haqida bosh qotirmagan maqul. Shunchaki o'yinni sinab ko'rish va ishga tushirish uchun kontseptsiyani yaratish bu asosiy vazifa hisoblanadi. Shu sababli o'yindagi ikki kishining ham harakatlarini bir satr orqali qilish yechimni yanada osonlashtiradi.

Keling, jarayon osonroq kechishi uchun ishni o'yin qolipidan boshlasak. Tic tac toe 3x3 kataklar orqali o'ynaladi, shuning uchun kodda buni ifodalash uchun bizga uchta sub massivdan iborat 2 o'lchovli massiv kerak bo'ladi. Har bir massiv bloki uchta elementdan iborat. Ushbu format har bir katakning holatini satr yordamida oson saqlashga imkon beradi va o'yinchi o'z navbatini olganida, massivdagi elementlardan biri "X" yoki "O" qiymatiga o'zgarib saqlab borilaveriladi.

```php
$state = [
  ['', '', ''],
  ['', '', ''],
  ['', '', ''],
];
```
Jarayonni amalga oshirish uchun bizga joriy o'yinchi va faol katakni aniqlashtiruvchi yana ikkita o'zgaruvchi kerak bo'ladi. Faol katak - qiymatni joylashtirmoqchi bo'lgan katakchani tanlash uchun o'yin taxtasi bo'ylab qanday harakat qilishimizni belgilab beradi. Faol katak 2 o'lchovli massivdagi elementga mos bo'lishi uchun biz x  va y koordinatali qismlarga ega bo'lishimiz kerak.
```php
$player = 'X';
$activeCell = [0 => 0, 1 => 0];
```
Yuqoridagi massiv va o'zgaruvchilar orqali bemalol boshlang'ich o'yin taxtasini yaratishimiz mumkin. Demak ko'p o'lchovli massivni o'yinga mos 3x3 panjaraga aylantirishimiz kerak. O'yin tatxtasini chop etish jarayonida faol katak va o'yinchi o'zgaruvchilari ham hisobga olinishi kerak. Bu o'yinning keyingi jarayonlaridagi o'zgarishlarni real vaqtda kuzatishni belgilab beradi.

Quyidagi funksiya `$stage`, `$activeCell` va `$player` o'zgaruvchilaridan o'yin taxtasi matnini ekranga chiqaradi.

```php
// game.php fayli
<?php
function renderGame( $state, $activeCell, $player ) {
	$output = '';

  	$output .= 'O\'yinchi: ' . $player . PHP_EOL;

  	foreach ( $state as $x => $line ) {
    	$output .= '|';
    	
    	foreach ( $line as $y => $item ) {
      		switch ( $item ) {
        		case '';
          			$cell = ' ';
          		break;
        		case 'X';
          			$cell = 'X';
          		break;
        		case 'O';
          			$cell = 'O';
          		break;
      		}
      	
      		$cell = ( $activeCell[ 0 ] == $x && $activeCell[ 1 ] == $y ) ? '-'. $cell . '-' : ' ' . $cell . ' ';
		
			$output .= $cell . '|';
    	}
    	
    	$output .= PHP_EOL;
  	}

	return $output;
}

$state = [
  [ '', '', '' ],
  [ '', '', '' ],
  [ '', '', '' ],
];

$player = 'X';
$activeCell = [ 0 => 0, 1 => 0 ];

echo renderGame( $state, $activeCell, $player );
```
```bash
$: php game.php
O'yinchi: X
|- -|   |   |
|   |   |   |
|   |   |   |
```
Funksiya faqatgina o'yin taxtasini chop etish vazifasini bajaradi xolos, ma'lumotlar ekranga chiqarilgach script o'z ishini tugatadi. Keyingi jarayonda o'yin holatini bir xil buyruq satrida saqlab borishi va chop etishini ta'minlash kerak. Ushbu yechimni cheksiz sikl yordamida satrni tozalash va yuqoridagi funksiya yordamida o'yin taxtasini qayta-qayta chop etish orqali hal qilishimiz mumkin.
```php
while (1) {
	system( 'clear' ); // buyruq satrini tozalash
  	echo renderGame( $state, $activeCell, $player ); // o'yin taxtasini chop etish
}
```
Endilikda o'yinni jonlantirish uchun o'yin taxtasi bo'ylab harakatlanish va harakatlarini tanlashga imkon berish yechimi kerak. Yechimni [1-qism](1-qism.htm) dagi amaliyot orqali amalga oshiramiz faqat yana bir necha amallar ham ushbu tarkibga kiritilishi lozim.

1. Foydalanuvchi harakatlanish tugmachalarini kiritishiga qarab faol katakchani mos ravishda almashtirish va o'yin taxtasi kataklari uchun chegarani nazorat qilish.
2. Agar foydalanuvchi ENTER tugmasini bossa, joriy faol katak boʻsh boʻlsa faol o'yinchi uchun katakni token bilan toʻldirish va faol o'yinchini almashtirish.
```php
function move( $stdin, &$state, &$activeCell, &$player ) {
	$key = fgets( $stdin );
  	if ( $key ) {
		$key = translateKeypress( $key );
    	switch ($key) {
      		case "UP":
        		if ( $activeCell[0] >= 1 ) $activeCell[0]--;
        	break;
      		case "DOWN":
        		if ( $activeCell[0] < 2 ) $activeCell[0]++;
        	break;
      		case "RIGHT":
        		if ( $activeCell[1] < 2 ) $activeCell[1]++;
        	break;
      		case "LEFT":
        		if ( $activeCell[1] >= 1 ) $activeCell[1]--;
        	break;
      	
      		case "ENTER":
      		case "SPACE":
        		if ( $state[ $activeCell[ 0 ] ][ $activeCell[ 1 ] ] == '' ) {
          			$state[ $activeCell[ 0 ] ][ $activeCell[ 1 ] ] = $player;
          			$player = ( $player == 'X' ) ? 'O' : 'X';
        		}
        	break;
    	}
  	}
}
```
Yuqoridagi `move()` funksiyasimizni faylga qo'shib asosiy sikl ichidagi `renderGame()` funksiyasidan oldin chaqiramiz, shunda o'yin taxtasi foydalanuvchi tomonidan kiritilgan har qanday o'zgarishlar bilan yangilanib boradi va o'yinchilar o'yin taxtasi bo'ylab harakatlanishlari va tokenlarni joylashtirishlari mumkin bo'ladi.

```php
$stdin = fopen( 'php://stdin', 'r' );
stream_set_blocking( $stdin, 0 );
system( 'stty cbreak -echo' );

while ( 1 ) {
  system( 'clear' );
  move( $stdin, $state, $activeCell, $player );
  echo renderGame( $state, $activeCell, $player );
}
```
O'yinning yakuni sifatida unda g'alaba holatlarini ham `$state` massivi orqali aniqlashimiz va ekranga chiqarishimiz lozim. Aks holda o'yin go'liblarsiz qolib ketishi mumkin.

G'olibni aniqlash uchun vertikal va gorizontal pozitsiyalarda ketma-ket uchta tokenlardan iborat qiymat mavjudligini tekshirishimiz kerak. Agar ushbu pozitsiyalar bo'yicha hech qanday natija topilmasa o'yin durang deb e'lon qilinadi. 
```php
function isWinState( $state ) {
  	foreach ( [ 'X', 'O' ] as $player ) {
		foreach ($state as $x => $line) {
      	
      		// Gorizontal qator mavjud.
      		if ( $state[ $x ][ 0 ] == $player && $state[ $x ][ 1 ] == $player && $state[ $x ][ 2 ] == $player ) exit( $player . ' yutdi' );

      		foreach ( $line as $y => $item ) {
      			// Vertikal qator mavjud.
        		if ( $state[ 0 ][ $y ] == $player && $state[ 1 ][ $y ] == $player && $state[ 2 ][ $y ] == $player ) exit( $player . ' yutdi' );
      		}
    	}
    
    	// Diagonal chiziq yuqoridan, chapdan, pastdan o'ngga shaklda topildi.
    	if ( $state[ 0 ][ 0 ] == $player && $state[ 1 ][ 1 ] == $player && $state[ 2 ][ 2 ] == $player ) exit( $player . ' yutdi' );
     
     	// Diagonal chiziq pastdan, chapdan, yuqoriga o'ngga shaklda topildi.
    	if ( $state[ 2 ][ 0 ] == $player && $state[ 1 ][ 1 ] == $player && $state[ 0 ][ 2 ] == $player ) exit( $player . ' yutdi' );
    }

  	// Durang holati
  	$blankQuares = 0;
  	foreach ( $state as $x => $line ) {
    	foreach ( $line as $y => $item ) {
      		if ( $state[ $x ][ $y ] == '' ) $blankQuares++;
    	}
  	}

  	// Agar bo'sh kataklar qolmasa va yuqoridagi shartlar bajarilmasa durang deb e'lon qilish.
  	if ( $blankQuares == 0 ) exit( 'Durang natija!' );   	
}
```
Cheksiz sikl uchun o'yin g'olibini aniqlash funksiyasini qo'shish

```php
while( 1 ) {
  	system( 'clear');
  	move( $stdin, $state, $activeCell, $player );
  	echo renderGame( $state, $activeCell, $player );
	isWinState( $state ); // g'olibni aniqlash
}
```
Yuqorida ishlab chiqilgan **Tic Tac Toe** o'yini ozgina qo'pol shaklda bo'lsada bizga keyingi qismlarda boshqa o'yinlarni ham ko'rib chiqishimizga yordam beradi. Bu shunchaki o'yin konsepsiyani tushunish va phpning foydalanilmagan imkoniyatlaridan foydalanish xolos.

O'yin kodi to'liq variantda: [Github](https://gist.github.com/con9799/ba3fd95da1f5eadd108060e79fef381a)

[👈 Avvalgi bosqich](1-qism.htm) | [👉 Keyingi bosqich](3-qism.htm)
