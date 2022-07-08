---
toc: false
title: "Php dasturlash tilida o'yin yaratish: 3-qism"
description: "PHP dasturlar tilida Snake Xenzia o'yinini ishlab chiqish..."
keywords: "php, o'yin, game, cli, stdin, qism, qismlar, yaratish"
---

Ushbu qismgacha bo'lgan postlar seriyasida foydalanuvchi tomonidan tugmachalarni bosish holatlarini aniqlash va PHP buyruq qatoridan foydalangan holda tic tac toe o'yinini yaratishni ko'rib chiqdik. Keyingi qadam, grafik muhitga ega va real vaqtda foydalanuvchi kiritishiga ega bo'lgan o'yinni yaratishdir. 

Bu jarayonni yanada soddaroq tushunish uchun mashhur Snake Xenzia (Ilon o'yini)ni ishlab chiqishdan foydalanamiz. O'yin bir nechta oddiy qoidalarga ega va uni juda oddiy grafik muhitda ham ishlab chiqish mumkin. O'yinning asosiy sharti ilonga olmalarni tanovvul qilishga yordam berib uni o'sib borishini ta'minlash.

Tik tac toe o'yinidan farqli o'laroq, bu yerda jarayonni kuzatish uchun yana bir nechta o'zgaruvchilar mavjud. Yo'naltiruvchi o'zgaruvchilardan foydalanishdan tashqari ularni obyektiga inkapsulatsiya qilish ham kerak. Ilon o'yinida o'yin taxtasini katta massiv sifatida saqlash o'rniga, biz faqat olma va ilonning harakatini kuzatib borishimiz kerak xolos. Bu jarayonni kuzatish uchun `Snake` obtektini yasab olamiz. Ushbu obyekt bizga ilon va olma holatlarini o'zida saqlab borishdan tashqari ularni boshqarish imkonini ham beradi.

```php
<?php
class Snake {
	public $width = 0;
  	public $height = 0;

  	public $positionX = 0;
  	public $positionY = 0;

  	public $appleX = 15;
  	public $appleY = 15;

  	public $movementX = 0;
  	public $movementY = 0;

  	public $trail = [];
  	public $tail = 5;

  	public $speed = 100000;

  	public function __construct( $width, $height ){
    	$this->width = $width;
    	$this->height = $height;

    	$this->positionX = rand( 0, $width - 1 );
    	$this->positionY = rand( 0, $height - 1 );

    	$appleX = rand( 0, $width - 1 );
    	$appleY = rand( 0, $height - 1 );

    	while ( array_search( [ $appleX, $appleY ], $this->trail ) !== FALSE ) {
      		$appleX = rand( 0, $width - 1 );
      		$appleY = rand( 0, $height - 1 );
    	}
    	
    	$this->appleX = $appleX;
    	$this->appleY = $appleY;
  	}
}
```

Snake obyektini yaratish uchun biz shunchaki o'yin taxtasining kengligi va balandligini ko'rsatishimiz lozim.
```php
$snake = new Snake( 20, 30 ); // kenglik, balandlik
```
Ilon o'yinini konsolda ko'rsatish uchun biz ilon obyektini qayta ishlash funksiyasidan foydalanamiz. Ushbu funksiyaning vazifasi ilonni ( X harfi bilan ifodalangan ), olma (0 raqami bilan ifodalangan) bilan birga harakatlanadigan o'yin taxtasini ko'rsatishdir.

```php
function renderGame( $snake ) {
	$output = '';
  	for ( $i = 0; $i < $snake->width; $i++ ) {
    	for ( $j = 0; $j < $snake->height; $j++ ) {
      		$cell = ( $snake->appleX == $i && $snake->appleY == $j ) ? '0' : '.';
      		foreach ( $snake->trail as $trail ) {
        		if ( $trail[0] == $i && $trail[1] == $j ) $cell = 'X';
      		}
      		$output .= $cell;
    	}
    	$output .= PHP_EOL;
  	}

  	$output .= PHP_EOL;
  	return $output;
}
```
Agar biz yaratilgan `Snake` obyektini ko'rsatsak quyidagi natijani ko'ramiz. Obyekt faqat bitta segmentga ega, shuning uchun u o'yin taxtasida bitta X sifatida chop etiladi. G'oya shundan iboratki, o'yinchi harakat qilganda, biz minimal daraja 5 ga yetguncha segmentlarni hosil qilishimiz kerak.

```
..............................
..............................
..............................
..............................
..............................
..............................
..............................
..............................
...X..........................
..............................
..............................
..............................
..............................
..............................
..............................
......................0.......
..............................
..............................
..............................
..............................
```

Yuqoridagi funksiya hozircha qayta ishlashdan boshqa hech narsa qilmaydi, shuning uchun biz ilonga ba'zi harakat mexanikasini qo'shishimiz kerak. Quyidagi funskiya ilon uchun ana shunday harakat mexanikasiga asoslanib yo'nalishni belgilab beradi.
```php
function direction( $stdin, $snake ) {
	$key = fgets( $stdin );
  	if ( $key ) {
    	$key = translateKeypress( $key );
    	switch ( $key ) {
      		case "UP":
        		$snake->movementX = -1;
        		$snake->movementY = 0;
        	break;
      		case "DOWN":
        		$snake->movementX = 1;
        		$snake->movementY = 0;
        	break;
      		case "RIGHT":
        		$snake->movementX = 0;
        		$snake->movementY = 1;
        	break;
      		case "LEFT":
        		$snake->movementX = 0;
        		$snake->movementY = -1;
        	break;
     	}
  	}
}
```
`direction` funksiyasi ilonning o'rnini o'yin taxtasi bo'ylab harakatlanayotgandek yangilash uchun ishlatiladi. Misol uchun, o'ng tomonga harakat tugmasini bosish movementY harakatini 1 ga o'rnatadi, ya'ni har bir yangilanishda ilon o'z harakatini positionY qiymati bo'yicha bittaga oshirib ketadi.

Yuqoridagi mantiqdan foydalanib, ilonni o'yin taxtasi bo'ylab siljitish uchun quyidagi `move()` funksiyasidan foydalananamiz. Ilon o'yin taxtasi chegarasidan o'tmasligini ta'minlagan holda ilon boshining holatini oldinga qo'shish va so'ngra ilon izi effekti hosil qilish uchun qatorning oxirgi elementni olib tashlashimiz kerak. Bu orqali ilon harakati effektini yaratamiz. Shuningdek, biz ilonning "boshi" olma ustida yetib kelganligini tekshirishimiz kerak va agar mantiq rost bo'lsa ilonning uzunligini bittaga oshirib olmani yangi joyga ko'chirish kerak.

```php
function move( $snake ) {
	// Ilonni harakatlantirish.
  	$snake->positionX += $snake->movementX;
  	$snake->positionY += $snake->movementY;

  	// Ilonni o'yin taxtasi chegarasi bo'ylab saqlash.
  	if ( $snake->positionX < 0 ) $snake->positionX = $snake->width - 1;
  	if ( $snake->positionX > $snake->width - 1 ) $snake->positionX = 0;
  	if ( $snake->positionY < 0 ) $snake->positionY = $snake->height - 1;
  	if ( $snake->positionY > $snake->height - 1 ) $snake->positionY = 0;

  	// Ilon izi old qismiga bitta element qo'shish
  	array_unshift( $snake->trail, [ $snake->positionX, $snake->positionY ] );

  	// Aniq uzunlikni saqlagan holda ilondan bitta belgini olib tashlash.
  	if ( count( $snake->trail ) > $snake->tail ) array_pop($snake->trail);

  	if ( $snake->appleX == $snake->positionX && $snake->appleY == $snake->positionY ) {
    	// Ilon olma pozitsiyasiga teng kelishi
    	$snake->tail++;

    	if ($snake->speed > 2000) {
      		// O'yin tezligini ma'lum chegaragacha oshirish
      		$snake->speed = $snake->speed - ( $snake->tail * ( $snake->width / $snake->height + 10 ) );
    	}
    	
    	// Olma pozitsiyasini almashtirish.
    	$appleX = rand( 0, $snake->width - 1 );
    	$appleY = rand( 0, $snake->height - 1 );
    	
    	while ( array_search( [ $appleX, $appleY ], $snake->trail ) !== FALSE ) {
      		$appleX = rand( 0, $snake->width - 1 );
      		$appleY = rand( 0, $snake->height - 1 );
    	}

    	$snake->appleX = $appleX;
    	$snake->appleY = $appleY;
  	}
}
```
O'yin jarayonida tekshirish kerak bo'lgan oxirgi narsa oxirgi kiritilgan harakat ilon o'limiga sabab bo'lganini aniqlash.  Ushbu holatni aniqlash uchun bizga ilonning boshi o'z tanasiga tegingani holatlari kerak bo'ladi.
```php
function gameOver( $snake ) {
	if ( $snake->tail > 5 ) {
    	// Agar iz 5 dan katta bo'lsa, oxirgi holatni tekshirish.
    	for ( $i = 1; $i < count( $snake->trail ); $i++ ) {
			if ( $snake->trail[ $i ][ 0 ] == $snake->positionX && $snake->trail[ $i ][ 1 ] == $snake->positionY ) {
        		exit('O\'yin tugadi, ilon o\'ldi');
      		}
    	}
  	}
}
```
O'yin uchun kerak bo'ladigan barcha funksiyalar bizda mavjud. Faqatgina cheksiz sikl orqali o'yin jarayonini boshqarish kerak xolos.

```php
while ( 1 ) {
	system('clear'); // Satrni tozalab borish
  	echo 'Olmalar: ' . $snake->tail . PHP_EOL;
  	direction( $stdin, $snake ); // Ilon yo'nalishini belgilash
  	move( $snake ); // Ilon harakatini boshqarish
  	echo renderGame( $snake ); // O'yin taxtasini chop etish
  	gameOver( $snake ); // Ilon o'limi holatini nazorat qilish
  	usleep( $snake->speed ); // O'yin tezligini belgilash
}
```

[2-qismdagi](2-qism.htm) tic ta toe o'yinidagi sikldan farqli o'laroq ilon o'yini siklida `usleep()` funskiyasi ham qo'shilgan. Ushbu funksiya harakat oralig'idagi pauzani begilab beradi. Ushbu funsksiyasiz ilon juda tez harakat qilib o'yinni o'ynash imkoni mavjud bo'lmay qoladi.

O'yinni ishga ishga tushirganimizda u ekranda quyidagi tartibda ko'rinadi va bemalol ilonni boshqarib ochkolar to'plashimiz mumkin bo'ladi.

```
Olmalar: 24
..............................
..............................
..............................
..............................
..............................
..............................
..............................
..........X...................
..........X...................
..........X0..................
..........XXXX................
..........X..X................
..........X..X................
..........X..X................
..........X..X................
..........X..X................
..........X..X................
..........XXXX................
..............................
..............................
O'yin tugadi, ilon o'ldi
```
O'yin kodi to'liq variantda: [Github](https://gist.github.com/con9799/b020651a4b95e4085ee6003bec16af7e)

[👈 Avvalgi bosqich](2-qism.htm) | [👉 Keyingi bosqich](4-qism.htm)
