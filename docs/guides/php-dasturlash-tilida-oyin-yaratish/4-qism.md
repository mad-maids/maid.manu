---
toc: false
title: "Php dasturlash tilida o'yin yaratish: 4-qism"
description: "PHP dasturlar tilida otishma o'yinini ishlab chiqish..."
keywords: "php, o'yin, game, cli, stdin, qism, qismlar, yaratish"
---

Avvalgi qismda murakkabroq harakat jarayoniga ega ilon o'yini yaratish haqida so'z olib borilgandi. Ushbu qismda yanada jarayonni mukammalroq qilish uchun **action** doirasida kiradigan otishma o'yini yaratish haqida bosh qotiamiz.

O'yin konsepsiyasiga keladigan bo'lsak ASCII asosida o'yin jadvali o'ngdan chapga tomon harakatlanib boradi. O'yinda o'yinchi kemasi va dushmanlar doimiy harakatlanib boradi. O'yinchi kemasi dushmanlarga tegib ketmagan holatda dushmanlarni o'qga tutishi lozim bo'ladi.

Otishma o'yinini yaratish uchun bir nechta aniqlash kerak bo'lgan elementlar kerak bo'ladi.

1. O'yinchining kemasi.
2. O'yinchiga qarshi harakatlanadigan dushmanlar.
3. O'yinchining kemasi tomonidan otilgan o'qlar.

```php
class Entity {
	public $positionX = 0;
  	public $positionY = 0;

  	public function __construct( $x, $y ) {
    	$this->positionX = $x;
    	$this->positionY = $y;
  	}
}
```
Entity obyekti o'yin uchun kerakli komponentlarni yaratishga ishlatiladi. Qo'shimcha tarzda kema uchun voqea joyida harakatlanishi va dushmanlar tomon o'q uzishi uchun yana bir nechta xususiyatlar ham kerak bo'ladi.
```php
class Spaceship extends Entity {
	public $movementX = 0;
  	public $movementY = 0;
  	public $fire = FALSE;
}

class Enemy extends Entity {}
class Bullet extends Entity {}
```
Umumiy o'yin bitta `Scene` obyekti orqali boshqariladi. Ushbu obyekt o'yin uchun zarur bo'lgan barcha komponentlarni saqlaydi va o'yinni o'yinchi tomonidan boshqarishga imkon beradi. Obyekt konstruktori o'yin sahnasining o'lchamlarini saqlaydi va keyingi bosqichda o'yinchining kema obyektini yaratadi. 
```php
class Scene {
	public $height;
  	public $width;

  	public $ship;
  	public $enemies = [];
 	public $bullets = [];
  	public $score = 0;

  	public function __construct( $width, $height ) {
    	$this->width = $width;
    	$this->height = $height;
    	$this->ship = new Spaceship( 2, round( $this->height / 2 ) );
  	}
}
```
Kemani harakatlantirish uchun moveShip() funskiyasini qo'llash kerak bo'ladi. Bu joriy harakat ya'ni x, y ni oladi va uni kemaning joylashuv xususiyatlariga qo'llaydi. O'yinchining sahna chegarasidan tashqariga chiqishiga yo'l qo'ymaslik va yarim yo'l nuqtasiga yetganda kemani to'xtatish uchun harakatlarga ba'zi bir mantiqlar ham qo'llaniladi.
```php
public function moveShip() {
	$this->ship->positionX += $this->ship->movementX;
    $this->ship->positionY += $this->ship->movementY;
    $this->ship->movementX = 0;
    $this->ship->movementY = 0;

    if ( $this->ship->positionX < 0 ) {
	    $this->ship->positionX = 0;
    }	
    if ( $this->ship->positionX >= $this->height ) {
	    $this->ship->positionX = $this->height - 1;
	}	
    if ( $this->ship->positionY < 0 ) {
	    $this->ship->positionY = 0;
	}
    if ( $this->ship->positionY > $this->width / 4 ) {
	    $this->ship->positionY = $this->width / 4;
	}
}
```
Kemani harakatga keltirish bilan bir qatorda kemadan o'q otishni ham ta'minlash lozim. Kema "o't ochish" rejimida bo'lganda o'qning pozitsiyasi uchun asos sifatida kema turgan joydan foydalanib, yangi o'q obyekti yaratiladi. O'q otilgandan so'ng kema uchun o'q ochish holatini o'chirib qo'yamiz. Ya'ni bir vaqtning o'zida faqat bitta o'q o'tishga ruxsat beramiz.
```php
public function shoot() {
	if ( $this->ship->fire == TRUE ) {
	    $this->bullets[] = new Bullet( $this->ship->positionX, $this->ship->positionY + 1 );
      	$this->ship->fire = FALSE;
	}
}
```
Foydalanuvchi tomonidan kiritilgan hodisalarni kemaga qo'llash uchun avvalgi qismlarda ishlab chiqilgan funksiyalarning ma'lum qismlaridan foydalanamiz. Bu orqali keramani harakatga keltirish, o'q otish kabi amallarni bajarishimiz mumkin bo'ladi. Qo'shimcha tarzda o'yinni to'xtatish uchun **ESC** tugmasini ham tinglashni qo'shamiz.

```php
public function action( $stdin ) {
	$key = fgets( $stdin );
    if ( $key ) {
	    $key = $this->translateKeypress( $key );
      	switch ( $key ) {
	        case "UP":
		        $this->ship->movementX = -1;
          		$this->ship->movementY = 0;
          	break;
        	case "DOWN":
	          	$this->ship->movementX = 1;
          		$this->ship->movementY = 0;
          	break;
        	case "RIGHT":
          		$this->ship->movementX = 0;
          		$this->ship->movementY = 1;
          	break;
        	case "LEFT":
          		$this->ship->movementX = 0;
         	 	$this->ship->movementY = -1;
          	break;
        	case "ENTER":
        	case "SPACE":
          		$this->ship->fire = TRUE;
          	break;
        	case "ESC":
          		exit();
      	}
    }
}
private function translateKeypress( $string ) {
	switch ( $string ) {
	    case "\033[A":
	        return "UP";
		case "\033[B":
	        return "DOWN";
		case "\033[C":
	        return "RIGHT";
		case "\033[D":
	        return "LEFT";
		case "\n":
	       	return "ENTER";
		case " ":
	        return "SPACE";
		case "\e":
	        return "ESC";
	}
    return $string;
}
```

O'qlarni o'yin sahnasi bo'ylab harakatlantirish juda oddiy. Bu uchun shunchaki sahnadagi har bir o'qning y pozitsiyasini oshirish kifoya. Bu o'qlarni gorizontal holatda sahnaning o'ng tomoniga siljishiga yordam beradi.

```php
public function moveBullets() {
	foreach ( $this->bullets as $bullet ) {
	    $bullet->positionY++;
	}
}
```
O'yin jarayonida dushmanlar haqida ham unutmaslik lozim. Quyidagi funksiya o'yin sahnasida doimiy 15 dona tasodifiy dushman haraktini generatsiya qilib beradi. Agarda o'yinchi bir dushmanni yo'q qilsa funksiya massivda kamaygan elementni tasodifiy kordinatada to'ldirib boradi.
```php
public function spawnEnemies() {
	if ( count( $this->enemies ) < 15) {
	    $y = rand( $this->width, $this->width * 2 );
      	$x = rand( 0, $this->height - 1 );
      	$this->enemies[] = new Enemy( $x, $y );
	}
}
```
Dushmanlarni o'yin sahnasida harakatlantirish uchun biroz mantiqiy harakat talab qilinadi. Quyidagi funksiya orqali dushmanni otib tashlash jarayoniga aniqlashtirish mumkin bo'ladi. Agar dushman o'yin sahnasining chap qismiga yetib kelgan bo'lsa uni voqea joyidan hech qanday hodisasiz olib tashlash mumkin.  Yoki dushman o'q bilan bir pozitsiyada bo'lsa, ikkalasi ham o'yin sahnadan o'chirishga to'g'ri keladi va o'yinchining umumiy baliga +1 qo'shish kerak bo'ladi. 

```php
public function moveEnemies() {
	foreach ( $this->enemies as $enemyId => $enemy ) {
	    $enemy->positionY--;
      	if ( $enemy->positionY == 0 ) {
	        // Dushman sahnaning chap tomoniga taqalgan bo'lsa uni hech qanday hodisasiz olib tashlash lozim.
	        unset( $this->enemies[ $enemyId ] );
        	continue;
		}
      		
      	foreach ( $this->bullets as $bulletId => $bullet ) {
	        if ( $bullet->positionX == $enemy->positionX && ( $bullet->positionY == $enemy->positionY || $bullet->positionY == $enemy->positionY - 1 ) ) {
	          	unset( $this->enemies[ $enemyId ] );
          		unset( $this->bullets[ $bulletId ] );
          		$this->score++;
			}
		}
	}
}
```
Agarda o'yinchi kemasi va dushman bir xil kordinataga mos kelsa o'yinni tugatish kerak bo'ladi.

```php
public function gameOver() {
	foreach ( $this->enemies as $enemy ) {
	    if ( $this->ship->positionX == $enemy->positionX && $this->ship->positionY == $enemy->positionY ) {
	        exit('O\'yin tugadi, dushman kemasi sizni mag\'lub qildi');
	    }
	}
}
```

Yuqoridagi elementlarning barchasi joyida bo'lsa bemalol o'yin sahnasini ishlab chiqish mumkin. Ushbu qayta ishlash usuli sahnaning balandligi va kengligi bo'ylab aylanadi va sahna ichidagi har bir elementni chop etadi.
```php
public function renderGame() {
	$output = '';
	
	for ( $i = 0; $i < $this->height; $i++ ) {
	    for ( $j = 0; $j < $this->width; $j++ ) {
	        $cell = ( $this->ship->positionX == $i && $this->ship->positionY == $j ) ? '>' : ' ';
        	foreach( $this->enemies as $enemy ) {
	          	if ( $enemy->positionX == $i && $enemy->positionY == $j ) $cell = 'X';
			}
			foreach( $this->bullets as $bullet ) {
	          	if ( $bullet->positionX == $i && $bullet->positionY == $j ) $cell = '-';
			}
        	$output .= $cell;
		}
      	$output .= PHP_EOL;
	}

    $output .= PHP_EOL. 'Ochkolar: ' . $this->score . PHP_EOL;
	return $output;
}
```

O'yinni ishga tushirish uchun balandlik va kenglikga asoslanga `Scene` (Sahna) obyektini yaratish, foydalanuvchidan kelayotgan kiritishilarni aniqlash uchun oqimni tinglovchi cheksiz sikl yaratish lozim.
```php
$scene = new Scene( 50, 10 ); // kenglik, balandlik

system( 'stty cbreak -echo' ); // strdan alohida belgilarni o'qishni belgilash
$stdin = fopen( 'php://stdin', 'r' ); // kiritish oqimini ochish
stream_set_blocking( $stdin, 0 ); // oqimni to'xtovsiz o'qish uchun kiritish satriga to'siq o'rnatish

while ( 1 ) {
  system( 'clear' ); // satrni tozalash
  $scene->action( $stdin ); // Kema uchun harakatlarni belgilash
  $scene->moveShip(); // Kemani harakatga keltirish
  $scene->shoot(); // Otish
  $scene->moveEnemies(); // Dushmanlarni harakatlantirish
  $scene->moveBullets(); // O'qlarni harakatlantirish
  echo $scene->renderGame(); // O'yin sahnasini chop etish
  $scene->gameOver(); // O'yindagi kema holatini kuzatish (O'yinni tugatish jarayoni)
  $scene->spawnEnemies(); // Dushmanlarni generatsiya qilib borish
  usleep(100000);
}
```

Ushbu kod ishga tushirilganda o'yinchining kemasi bilan sahna ko'rsatila boshlanadi. Bir necha soniyadan so'ng dushmanlar paydo bo'la boshlaydi va bo'sh joydan foydalanib, ularga o'q uzish mumkin. Agar o'q dushmanga yetib borsa dushman ochkolar soni +1 hisobda oshirib boriladi.

```                                    
                                       X          
                                -- -   X        X 
                     - - - -                      
                                                  
            - - -                   X     X       
     >                              X             
                                       X          
                          X    X              X   
                                                  

Ochkolar: 1
```
O'yin kodi to'liq variantda: [Github](https://gist.github.com/con9799/9be18e7c4e9841c5d72ff1d5b99d1aeb)

[👈 Avvalgi bosqich](3-qism.htm)
