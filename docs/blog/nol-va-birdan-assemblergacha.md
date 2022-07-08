---
title: "Nol va birdan assemblergacha"
description: "Maktabdagi informatika o'qituvchingiz sizga kompyuter 0 va 1 orqali ishlashini aytgani bilan lekin uni aslida qanday ishlashini tushuntirib bermagan bo'lsa kerak a?"
keywords: "assembler, sanoq, tizim, ikkilik, kompyuter, qanday ishlaydi, cou, ram, tranzistor"
---

Maktabdagi informatika o'qituvchingiz sizga kompyuter 0 va 1 orqali ishlashini aytgani bilan lekin uni aslida qanday ishlashini tushuntirib bermagan bo'lsa kerak a? Bu ma'lumot haqida maktabda eshitmagan bo'lsangiz ham bugungi raqamli dunyoda tez-tez duch kelishingiz mumkin albatta. Ho'sh aslida ular nima va kompyuter uchun nimalar qilib bera oladi?

## Avval sanoq tizimi haqida
Sonlarni maxsus belgilar (raqamlar) toʻplami yordamida yozish usuliga sanoq tizimi deb aytiladi.
Raqamlarni yozish zarurati qadimgi davrlarda odamlar hisoblashni o'rganganlaridan keyin paydo bo'lgan. Bunga paleolit ​​davriga oid arxeologik topilmalar guvohlik beradi. Dastlab, obyektlar soni ma'lum belgilar yordamida tasvirlangan: chiziqlar, tirqishlar, toshlarga, yog'ochga yoki loyga qo'llaniladigan doiralar, shuningdek arqonlardagi tugunlar kabi. Dunyodagi birinchi sanoq tizimini albatta bu birlik hisoblanadi.

Birlik sanoq sistemasidan foydalanish misollarini hayotimizda uchratish mumkin. Misol uchun, kichkina bolalar barmoqlarida necha yoshda ekanligini tasvirlashga harakat qilishadi yoki birinchi sinfda hisoblashni o'rgatish uchun hisoblash tayoqchalaridan foydalaniladi. Men ushbu sanoq tizimini inson genida to'liq saqlanadigan tizim degan bo'lar edim.

Albatta birlik sanoq tizimi  unchalik qulay emas, chunki yozuvlar juda uzun va ularni qo'llash ancha zerikarli, shuning uchun ham vaqt o'tishi bilan ko'proq amaliy sanoq tizimlari paydo bo'la boshladi.

Qadimgi Misr tarixiga ham nazar soladigan bo'lsak bitta kaltak bir soni deb olingan bo'lsa, qo'llarini yuqoriga ko'targan odam surati bir million miqdorini bergan.

Yoki rim tarixidan misol: Barmoq bir sonini belgilasa, ochiq kaft besh sonini anglatgan. Rimliklar keyinchalik sonlarni ifodalash uchun tegishli lotin so'zlarining birinchi harflari ham ishlatishgan (Centum- yuz, Mille - ming kabi). 

Slavyan xalqlari tarixida alfavitga asoslangan sanoq tizimlari ham uchardi. Bu esa yuqoridagi tizimlardan ko'ra ancha mukammalroq va sodda bo'lgan.

Bugungi kunda biz ishlatadigan sanoq tizimi qadimgi arab va hind xalqlariga tegishli hisoblanadi. 

Hisoblash mashinalarida, odatda, Gotfrid Leybnits tomonidan taklif qilingan ikkilik sanoq tizimi ishlatiladi. Bunday sonlarni tasvirlash uchun ikkita - 0 va 1 raqamlaridan foydalaniladi. Ikkilik sanoq tizimining ishla-tilishi EHMlar quriladigan elementlarning faqat 2 ta turgʻun ishchi holatda boʻlishi bilan bogʻliq. Bu elementlar kalitlarga oʻxshash boʻlib, ular yoqilgan yoki oʻchirilgan holatlarda boʻladi. Uchinchi holat yoʻq. Bu holatlarning biriga 1, Ikkinchisiga 0 mos qoʻyilsa, bunday elementlarning ketmaketligi ikkilik sanoq tizimidagi songa mos keladi.

## Nima uchun kompyuterlar ikkilik sanoq tizimidan foydalanadi?
Ikkilik sanoq tizimi hali ham kompyuterlar uchun asosiy til bo'lib, quyidagi sabablarga ko'ra elektronika va kompyuter texnikasi bilan qo'llaniladi.

Elektron kalitlarda (tranzistor) "0" elektr oqimining yo'qligini va "1" oqimga ruxsat berilgan elektr energiyasini anglatadi. Raqamlar hisoblash qurilmasi ichida jismoniy ko'rinishda ifodalanadi, bu esa hisob-kitoblarni amalga oshirishga imkon beradi.

Xotira manbalarida ham xuddi shunday. Masalan magnitli lentani olaylik, magning musbat va manfiy qutblari xuddi binary kabi ifodanaladi.

## Bu qanday ishlaydi?

Keling jarayonni Lenin chirog'i orqali tushunishga harakat qilamiz. Quyosh botib tun kirib kelganda yoritish maqsadida lampochkani yoqish uchun devordagi elektr kalitidan foydalanamiz. Kalit pastga bosilganda lampochka o'chiq, yuqoriga bosilganda esa lampochka yonadi. Bir buni bemalol bir bit deyishimiz mumkin.

Yoki quyidagi misolga qaraylik:

Quyidagi jadvalda 01000001 ikkilik sanoq tizimi uchun misol keltirilgan. Har bir ustun darajalarga ko'tarilgan ikki raqamni ifodalaydi , sakkizta pozitsiyaning har biri bo'ylab harakatlanayotganda bu ko'rsatkichning qiymati bittaga ortib boradi. 

Ushbu misolning javobini chiqarish uchun jadvalni o'ngdan chapga o'qiymiz va har bir ustunning qiymatini oldingi ustunga qo'shib boramiz: (1+64) = 65. Biz 0 ga teng bo'lgan bitlarni hisoblamaymiz, chunki ular "o'chirilgan".

||||||||||
|--|--|--|--|--|--|--|--|--|
|Daraja|2^7^|2^6^|2^5^|2^4^|2^3^ |2^2^|2^1^|2^0^   
|Qiymat|128|64|32|16|8|4|2|1
|Holat|0|1|0|0|0|0|0|1

Keyingi misolda 8-bit qiymatni hisoblaylik. Ya'ni 11111111=255. Jarayoni o'ngdan chapga o'qiganimizda 1 + 2 + 4 + 8 + 16 + 32 + 64 + 128 = 255 javobi chiqqanini ko'ramiz.
||||||||||
|--|--|--|--|--|--|--|--|--|
|Daraja|2^7^|2^6^|2^5^|2^4^|2^3^ |2^2^|2^1^|2^0^   
|Qiymat|128|64|32|16|8|4|2|1
|Holat|1|1|1|1|1|1|1|1
> Kompyuterda hisoblash odatda "1" o'rniga "0" dan boshlanadi. Shuning uchun, barcha bitlarni sanash 255 ga teng, lekin agar siz 0 dan boshlasangiz, bu 256 ga teng.

Kompyuter axborot birliklaridan bizga ma'lumki 8bit 1 baytga teng. Birinchi misoldagi 01000001 ikkilik tizimni javobi 65 deb qaraydigan bo'lsak va buni ASCII jadvalidan izlasak "A" harfiga teng bo'ladi.

## Sodda kompyuter va dasturlash tili

Yuqoridagi misollarda keltirilgan 8bitni, 8ta kalit deb tasavvur qilaylik va misol sifatida 3 raqamini olaylik. 3 sonining 8-bitli ko'rinishi quyidagicha bo'ladi: 00000011.

Demak boshlang'ich oltita kalitni o'chiq holatda va yakuniy ikkitani yoniq holatga o'tkazaylik. Buni sodda ko'rinishga ega registrlar (xotira bloklari) deyish mumkin ( Bunday bloklari hozircha biz uchun absurd bo'lgan Protsessor va RAM ko'rinishini yaratadi ).

Endi yuqoridagi misolga tayanib 2+3 amalini hisoblashimiz kerak. Bu uchun bitta registrda 2 sonini va 3 ni boshqa registrda saqlashimiz lozim. Keyin esa mantiqiy amallarga asoslangan blok (qurilma) orqali  registerdagi bitlar ketma-ketligini berib boshqa bir registerda javob chiqara olamiz.

### Prevdo kodlar misolida

Mashina kodi:
```
80 02 F3
80 03 F4
88 F3 F4 F5
```

Assembler tilida:
```
MOVI 2, REG A
MOVI 3, REG B
ADD REG A, REG B, REG C
```
C tilida:
```
int a = 2; b = 3;
c = a+b;
```
