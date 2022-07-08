---
toc: true
title: "Multilanguage (ko'ptilli dasturlar) bu qanday ishlaydi?"
description: "Dasturiy ta'minot auditoriyasini oshirishning eng yaxshi usullaridan biri bu uning interfeysni ko'p tillar uchun moslay olish hisoblanadi..."
keywords: "javascipt, php, dasturlash, multilanguage, po, poedit"
---

Dasturiy ta'minot auditoriyasini oshirishning eng yaxshi usullaridan biri bu uning interfeysni ko'p tillar uchun moslay olish hisoblanadi.
Umuman olganda bu jarayon har qanday loyiha boshidan hisobga olib ketilsa siz hech narsa yutqazmaysiz qaytaga ertangi kunda loyihani qo'llab-quvvatlashga yanada foydali hisoblanadi.

## Bu o'zi qanday ishlaydi?
Aslida bu funksional qiymatni ishlab chiqish siz o'zlagandan ko'ra ancha oson hisoblanadi. Faqatgina buning bir necha usullari haqida tushunchaga ega bo'lib olsangiz bas.

## Massivlar
Ko'p tilli interfeysning eng sodda usullaridan biri bu massivlar hisoblanadi. Sizdan massivda kerakli kalitlar bo'yicha qiymatlarni saqlash va ularni kalitga asoslanib foydalanish talab etiladi xolos.
Eng sodda usulda ko'radigan bo'lsak:
```php
<?php
$items = [
    "en" => [
        'hi' => 'Hello world!',
        'godbye' => 'Goodbye world!',
    ],
    "uz" => [
        'hi' => 'Salom dunyo!',
        'godbye' => 'Xayr dunyo!',
    ],
];

$lang = 'uz';
echo $items[ $lang ]['hi'];
```
Yuqoridagi koddan qaraydigan bo'lsak `$items` massivi til kalitlari doirasida kerakli qiymatlar bilan to'ldirilgan. Biz ekranga chop etish jarayonida shunchaki massivdan boshlang'ich til  va ekranga chiqarish kerak bo'lgan qiymat kaliti ni belgilab uni stdoutga uzatishimiz kifoya.

Yoki yana bir usulda ko'rsak:

```json
// en.json
{
    "hi": "Hello world!",
    "godbye": "Goodbye world!"
}
```
```json
// en.json
{
    "hi": "Salom dunyo!",
    "godbye": "Xayr dunyo!"
}
```
```php
// print.php
<?php
$lang = 'uz';
$items = json_decode( $lang . '.json', TRUE );
echo $items['hi'];
```
Yuqoridagi usulda biz json faylidan til paketi sifatida foydalaniyapmiz va kerakli massivni shu fayldan qabul qilib olyapmiz.

## po-mo usuli
Wordpress cmsdan foydalangan kishi uchun .po, yoki .mo faylini begona bo'lmasa kerak. Wordpress ushbu fayldan keng foydalanib keladigan dasturiy mahsulotlardan biri hisoblanadi.

PO bu matnga asoslangan portativ obyekt faylidir. Ushbu fayl matn muharririda foydalanuvchi tomonidan o'qiy oladigan formatda saqlanadi.

Dasturlash tillarining ko'pida ushbu formatni  qayta ishlash uchun tayyor funksiyalar mavjud yoki uni oddiy math tahrirlash usulida o'zingizga mos shaklga o'tkazish ham muammo emas.

Fayl strukturasiga qaydigan bo'lsak bundan msgid yuqoridagi misoldagi kabi kalit vazifasida msgstr esa qiymat vazifasida keladi. Har bir til esa alohida faylda saqlanadi.

```po
msgid "hi"
msgstr "Salom dunyo"

msgid "godbye"
msgstr "Xayr dunyo"
```

```php
<?php
$lang = 'uz';
$pofile = file_get_contents($lang . '.po');
preg_match_all('/msgid "(.+?)"\nmsgstr "(.+?)"/', $pofile, $matches, PREG_SET_ORDER);

$lang = [];
foreach ($matches as $i) {
    $lang[ $i[ 1 ] ] = $i[ 2 ];
}
```

MO fayl ham xuddi po kabi ishlaydi. Faqatgina u odam o'qiy oladigan emas balkim binary shaklda ma'lumot saqlaydi va qayta ishlash jarayonlari ham turlicha.

## Bonus ( Javascript )
```js
function Language( lang ) {
    var __construct = () => {
        if ( eval( 'typeof ' + lang ) == 'undefined' ) lang = "en";
        return;
    }

    this.getStr = ( str, defaultStr ) => {
        var retStr = eval( 'eval(lang).' + str );
        
        if (typeof retStr != 'undefined') {
            return retStr;
        } else {
            if ( typeof defaultStr != 'undefined' ) {
                return defaultStr;
            }else{
                return str.replace(/_/g, " ");
            }
        }
    }
}

var uz = {
    hi:"Salom dunyo!",
    goodbye:"Xayr dunyo!"
};

var en = {
    hi:"Hello world!",
    goodbye:"Goodbye world!"
};

var translator = new Language("uz");
console.log(translator.getStr("hi"));

var translator = new Language("en");
console.log(translator.getStr("goodbye"));

console.log(translator.getStr("undefined_key", 'Default value'));
```

## Bonus ( php )

```
// uz.lng
hi = "Salom dunyo!"
goodbye = "Xayr dunyo!"
```
```
// en.lng
hi = "Hello world!"
goodbye = "Goodbye world!"
```
```php
// parse.php
<?php
class Language{
    
    private $items;

    function __construct( $lang ) {
        $file = $lang . '.lng';
        $this->items = parse_ini_file( $file );
    }

    public function getStr( $str, $defaultStr = FALSE ) {
        if ( ! empty( $this->items[ $str ] ) ) {
            return $this->items[ $str ];
        }

        if ( ! empty( $defaultStr ) ) {
            return $defaultStr;
        }

        return ucfirst( str_replace('_', " ", $str) );

    }
}

$lang = new Language( 'uz' );
echo $lang->getStr('hi');
echo $lang->getStr('undefined', 'Default value');
```

## Linux bash
```bash
lang="uz"

declare -A uz
uz[hi]="Salom dunyo!"
uz[goodbye]="Xayr dunyo"

declare -A en
en[hi]="Hello world!"
en[goodbye]="Goodbye world"


getStr () {
  typeset -n l=$1 
  echo ${l[$2]};
}

getStr $lang "hi"
```
