---
toc: true
title: "Mantiqiy eshiklar"
description: "Ko'pgina zamonaviy kompyuterlarda mantiqiy eshiklar rezistorlar, diodlar va boshqa elektr komponentlar bilan birlashtirilgan tranzistorlar yordamida qurilgan..."
keywords: "logic, gates, eshik, mantiq, and, or, xor, xnor, qanday ishlaydi, kompyuter, sxema"
---

Avvalgi [maqolada](https://manu.uno/blog/nol-va-birdan-assemblergacha.htm) 0 va 1 soni , oz bo'lsada kompyuter ishlash prinsipi haqida so'z olib borgan edim.

Endi esa mantiqiy eshiklar haqida ham so'zlashsak.

**Mantiqiy eshik** - bu kirish pulsining raqamli zanjirlarda chiqishga o'tishi mumkinligini aniqlaydigan oddiy kommutatsiya sxemasi bo'lib ko'plab mantiqiy operatsiyalarni bajarish uchun xizmat qiladi.

Ko'pgina zamonaviy kompyuterlarda mantiqiy eshiklar rezistorlar, diodlar va boshqa elektr komponentlar bilan birlashtirilgan tranzistorlar yordamida qurilgan.  Albatta kompyuteringizni ochib ko'rganingizda bularni ko'ra olmaysiz. Chunki ular nanometrlar ortida joylashgan. Masalan mening kompyuterimda 6 milliondan ortiq bunday mantiqiy eshiklar bor. Kompyuter vazifasi va texnik talabiga ko'ra bu son belgilanib borilaveriladi.

Mantiqiy eshiklarni real hayotdagi qurilmalarda ko'z bilan ko'rishimiz qiyin bo'lsada ularni ishlash prinsipini o'rganishimiz o'zimizdagi mavjud komponentlar orqali yaratishimiz ham mumkin. 

> Oddiyroq misolda tasavvur qilsak: kompyuterni katta bir savdo
> markazi deb olaylik. Siz bozorlik qilishingiz uchun markaz ichidagi
> mayda do'konlarda foydalanasiz. Do'kon vazifalari takrorlanshi mumkin
> lekin uning vazifasi sizga tovar yetkazish (shartni tekshirish).
> Asosiysi qaysidir do'kon ochiq rastada, boshqasida kirish va chiqish
> eshiklari bor.
> Yoki yana ham oddiyroq misolda ko'radigan bo'lsak binodagi ko'proq chiroqlar va ular uchun belgilangan o'chirish, yoqish kalitlari bu.

Kirish eshiklari odatda bir yoki bir nechta bo'lishi mumkin, lekin chiqish har doim bitta holatda bo'ladi xolos.

## Mantiqiy eshik turlari

Mantiqiy eshik - signalni o'tkazish yoki o'tmaslikni aniqlash uchun ma'lumotlar oqimini ma'lum qoidalar asosida boshqaradi.

Uning quyidagi turlari keng tarqalgan:

1.  NOT
2.  AND
3.  OR
4.  NOR
5.  NAND
6.  XOR
7.  XNOR

## NOT eshigi

![not gate](https://media.geeksforgeeks.org/wp-content/uploads/20211107120202/SymbolofNOTgate.jpg)

Ushbu eshikda kirish va chiqishlar bitta. Kirish 1 (rost) bo'lsa, chiqish 0 (yolg'on) bo'ladi yoki aksincha. 

NOT eshigi o'zining xususiyati tufayli ba'zan inverter deb ham ataladi. Agar faqat bitta A kirish bo'lsa, chiqish mantiqiy tenglama Y=A' yordamida hisoblanishi mumkin.

|Kirish|Chiqish|
|--|--|
|A|X|
|1|0|
|0|1|

### AND eshigi

![enter image description here](https://media.geeksforgeeks.org/wp-content/uploads/20211105170309/SymbolofANDgate.jpg)

Ushbu eshik bitta chiqish va ikki yoki undan ortiq kirishlarga ega. Barcha kirishlar 1 (rost) bo'lsa, chiqish 1 (rost) ga teng, aks holda esa faqat 0 (yolg'on) holatda bo'ladi.

AND eshigining mantiqiy tenglamasi: Y=A.B (Kirishlash soniga qarab oshib borishi mumkin)

|Kirish|Chiqish|
|--|--|
|A - B|X|
|0 - 0|0|
|0 - 1 |0|
|1 - 0 |0|
|1 - 1 |1|

### OR eshigi
![or gate](https://media.geeksforgeeks.org/wp-content/uploads/20211107115351/SymbolofORgate.jpg)

Ushbu eshik xuddi AND kabi, faqatgina bunda kirishlardan istalgan bittasi 1 (rost) holatga keltirilsa chiqish ham shunga teng bo'ladi.

OR eshigining mantiqiy tenglamasi: Y=A+B

|Kirish|Chiqish|
|--|--|
|A - B|X|
|0 - 0|0|
|0 - 1 |1|
|1 - 0 |1|
|1 - 1 |1|

### NOR eshigi
![nor gate](https://media.geeksforgeeks.org/wp-content/uploads/20211107121157/sybmolofNORgate.jpg)
Bu eshik NOT va OR eshigining chatishmasi sifatida keng tanilgan. Bunda chiqish faqat uning barcha kirishlari 0 (yolg'on) bo'lganda 1 (rost) ga tenglashadi.

NOR eshigining mantiqiy tenglamasi: Y=(A+B)`

|Kirish|Chiqish|
|--|--|
|A - B|X|
|0 - 0|1|
|0 - 1 |0|
|1 - 0 |0|
|1 - 1 |0|

*NOT eshigi ba'zan universal eshik sifatida tanilgan, chunki u OR, AND va NOT eshiklarini amalga oshirish uchun ishlatilishi mumkin.*

### NAND eshigi
![nand eshigi](https://media.geeksforgeeks.org/wp-content/uploads/20211107122015/symbolofNANDgate-300x112.jpg)

Bu eshik ham xuddi NOR kabi faqat uning teskari prinsipida ishlaydi. Agar kirishlarning hech biri 1 (rost) ga teng bo'lmasa chiqish 1 ga tenglashadi. Agar barcha kirishlar rost bo'lmasa va kamida bittasi yolg'on bo'lsa, chiqish rostga aylanadi.

NAND eshigining mantiqiy tenglamasi: Y=(A.B)'

|Kirish|Chiqish|
|--|--|
|A - B|X|
|0 - 0|1|
|0 - 1 |1|
|1 - 0 |1|
|1 - 1 |0|

### XOR eshigi
![xor gate](https://media.geeksforgeeks.org/wp-content/uploads/20211107123017/SymbolofXORgate.png)
Eksklyuziv OR eshigida kirishlardan birortasi 1 (rost) bo'lsa chiqish ham shunga teng bo'ladi. Agar barcha kirish 1 (rost) bo'lsa, chiqish 0 (yolg'on) bo'ladi. Yoki ikkala kirish 0 (yolg'on) bo'lsa, chiqish ham 0 (yolg'on) bo'ladi. 

XOR eshigining mantiqiy tenglamasi: Y=A'.B+A.B'
|Kirish|Chiqish|
|--|--|
|A - B|X|
|0 - 0|0|
|0 - 1 |1|
|1 - 0 |1|
|1 - 1 |0|


### XNOR eshigi
![xnor](https://media.geeksforgeeks.org/wp-content/uploads/20211107124605/SymbolofXnorgate-300x132.jpg)
Eksklyuziv NORda agar ikkala kirish ham 1 (rost) bo'lsa eshikning chiqishi 1 (rost) bo'ladi. Agar ikkala kirish ham 0 (yolg'on) bo'lsa, chiqish ham 0 (yolg'on) bo'ladi. Yoki kirishlardan biri 0 (yolg'on) bo'lsa, chiqish ham 0 (yolg'on) bo'ladi. 

XNOR eshigining mantiqiy tenglamasi: Y=A.B+A'B'

|Kirish|Chiqish|
|--|--|
|A - B|X|
|0 - 0|1|
|0 - 1 |0|
|1 - 0 |0|
|1 - 1 |1|

