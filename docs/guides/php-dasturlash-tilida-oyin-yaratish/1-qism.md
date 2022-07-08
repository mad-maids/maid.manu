---
toc: false
title: "Php dasturlash tilida o'yin yaratish: 1-qism"
description: "Yaqinda youtubeda eski kompyuterlar haqidagi hujjatli filmni ko'rayotganimda buyruq satri (cli) yordamida..."
keywords: "php, o'yin, game, cli, stdin, qism, qismlar, yaratish"
---

Yaqinda youtubeda eski kompyuterlar haqidagi hujjatli filmni ko'rayotganimda buyruq satri (cli) yordamida yaratilgan o'yinlar e'tiborimni tortdi. Bu matn asosida ishlaydigan yoki savollar berib boradigan emas balkim real vaqtda foydalanuvchi buyruqlarini takrorlovchi o'yin edi. 

Demakki o'ttiz yoshli kompyuterda mumkin bo'lsa, unda PHP tili ham bunday ishni eplay olishi aniq!

Ko'pchilik vazifaning og'riqli deb biladigan nuqtasi real vaqt rejimida buyruq satrida klaviaturadan kirishni qanday aniqlashni hal qilishdir. PHP dasturlash tilida php://stdin kiritish oqimi mavjud.  Ushbu oqim va `fgets()` funksiyasi yordamida biz bemalol foydalanuvchidan kelayotgan kiritish hodisalarini aniqlay olamiz.

```php
// input.php fayli
<?php

$stdin = fopen( 'php://stdin', 'r' ); // kiritish oqimini ochish
stream_set_blocking( $stdin, 0 ); // oqimni to'xtovsiz o'qish uchun kiritish satriga to'siq o'rnatish
system( 'stty cbreak -echo' ); // strdan alohida belgilarni o'qishni belgilash

/***	Satrga kiritilgan maxsus belgilarni o'girib olish	***/

function translateKeypress( $string ) {
  	
  	switch ($string) {
  		// Yuqoriga harakatlantirish tugmasi
    	case "\033[A":
      		return "UP";
    	// Pastga harakatlantirish tugmasi
    	case "\033[B":
      		return "DOWN";
    	// O'ngga harakatlantirish tugmasi
    	case "\033[C":
      		return "RIGHT";
    	// Chapga harakatlantirish tugmasi
    	case "\033[D":
      		return "LEFT";
    	// ENTER (kiritish) tugmasi
    	case "\n":
      		return "ENTER";
    	// Bo'sh joy tugmasi
    	case " ":
      		return "SPACE";
    	// Satrdan belgini o'chirish tugmasi
    	case "\010":
    	case "\177":
      		return "BACKSPACE";
    	// Tabulator tugmasi
    	case "\t":
      		return "TAB";
    	// Bekor qilish tugmasi
    	case "\e":
      		return "ESC";
   	}

  	return $string;
}

// hodisalarni doimiy kuzatish uchun cheksiz sikl
while ( 1 ) {
	$keypress = fgets( $stdin ); // o'qimni o'qish
  	
  	// oqimda ma'lumot mavjud bo'lsa maxsus belgilarni o'girish orqali yangi qatorga chop etish
  	if ( $keypress ) {
    	echo translateKeypress( $keypress ) . " -> tugmasi bosildi" . PHP_EOL;
  	}
}
```
Yuqoridagi koddan ko'radigan bo'lsak, kod biz buyrular qatoriga kiritgan har bir kiritish hodisasini o'qib aynan qaysi tugma bosilganini yangi qatorga chop etadi.

```bash
$: php input.php
a -> tugmasi bosildi
d -> tugmasi bosildi
a -> tugmasi bosildi
s -> tugmasi bosildi
d -> tugmasi bosildi
TAB -> tugmasi bosildi
UP -> tugmasi bosildi
DOWN -> tugmasi bosildi
RIGHT -> tugmasi bosildi
LEFT -> tugmasi bosildi
UP -> tugmasi bosildi
DOWN -> tugmasi bosildi
RIGHT -> tugmasi bosildi
LEFT -> tugmasi bosildi
TAB -> tugmasi bosildi
ESC -> tugmasi bosildi
ESC -> tugmasi bosildi
```

Demak bizda o'yinni boshqarish uchun muhim qismlardan biri bo'lgan tugmalardan hodisalarni qabul qiluvchi qism tayyor. 

[👉 Keyingi bosqich](2-qism.htm)
