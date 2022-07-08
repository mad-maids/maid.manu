---
toc: true
title: "Telegram instant view nima va qanday ishlaydi?"
description: "Instant view vazifa jihatdan kontentni foydalanuvchilarga ortiqcha harakatlarsiz tezkorlik bilan yetkazishni ta'minlaydi..."
keywords: "telegram, instant, view, kontent, maqola, dasturlash, til, qanday ishlaydi"
---

Hmmm, instant view iborasini bir lahzali ko'rish yoki tezkor ko'rish deb tarjima qilish kerakmikan? 🧐 O'zbek tili albatta bunday iboralarni tarjima qilishda odamni o'ylantirib qo'yadi. Shu sababli keling yaxshisi ingliz tilidagi versiya bilan atab qo'yaversak.

Instant view iborasini bugungi kunda odamlarga kontent ulashuvchi ko'plab platformalarda kuzatish mumkin. Facebook, medium, heldocs kabilarda ushbu imkoniyat maqolalarni o'qish uchun ishlasa, google play marketda ilovalarni qurilmaga o'rnatmay turib ishlatish imkonini beradi. Demakki instant view vazifa jihatdan kontentni foydalanuvchilarga ortiqcha harakatlarsiz tezkorlik bilan yetkazishni ta'minlaydi.

Telegramda ham bunday funksiya mavjud. Ayni damda ios, android va macos operatsion tizimlari ushbu funksiyani o'zlaridagi telegram ilovasi orqali qo'llay oladi.

## Bu qanday ishlaydi?

Instant viewning telegramdagi asosiy vazifasi foydalanuvchilarga ilovadan tashqi muhitdagi dasturlardan foydalanmay turib turli ulashilgan havolalar kontentini ko'rish imkonini beradi.

Ya'nikim, siz biror bir havolani ulashganingizda telegram uni o'zidagi instant view mavzularini aniqlovchi bot orqali tekshiradi. Agarda bot ushbu havola manziliga tegishli mavzuni topsa undagi belgilangan shartlar bo'yicha kontentni originalidan qirqib telegramning o'z serveriga joylashtiradi. Shundan so'ng, foydalanuvchilar ushbu havolaga o'tishga uringanda telegram ilovasi kontentni havola joylashgan serverdan emas balkim telegramning o'zining tezkor serveridan uziladi. Bu juda ham tez yuz beradigan jarayonki foydalanuvchilar xuddiki offline ma'lumotdan foydalanayotgan kabi ulanish davriyligini his etishmaydi.

## Instant mavzular qanday yaratiladi?

Bu jarayonni amalga oshirish uchun telegram o'zining https://instantview.telegram.org/my/ sahifasida maxsus tahrirlovchi ilova yaratgan. Har kim o'zining telegram akkounti orqali avtorizatsiya amalga oshirib kerakli havolalar uchun mavzular yaratishi mumkin.

![instant view tahrirlash muhiti](https://i.ibb.co/JzthKcj/photo-2022-07-07-00-48-53.jpg)

### Mavzularni tahrirlash

https://instantview.telegram.org/my/ sahifasiga kirganimizdan so'ng sahifaning yuqori qismida mavzu yaratish uchun havola manzili kiritish so'raladi. Kerakli havola kiritilgach uchta oynadan iborat tahrirlovchi ilovani chiqariladi.

![instant mavzularni tahrirlash oynasi](https://i.ibb.co/kBt5Bh6/download1.jpg)

Telegram velosipedni qayta ixtiro qilib o'tirmasdan mavzularni ishlab chiqish uchun **xpath** tilidan foydalangan. Xpath bu xml hujjatlarni qayta ishlash uchun ishlab chiqilgan ifodalash tili. Ushbu til satrlar, raqamlar, mantiqiy amallar, sana va vaqtni taqqoslash, elementlarni manipulatsiya qilish kabi imkoniyatlarni bera oladi. Ozgina htmldan xabardor odam uchun unchalik ham muammo emas. Jumladan ushbu ifodalash tili bugungi kunda javascript, php, python, java, c kabi ko'plar dasturlash tillari doirasida qo'llab kelinadi. Yanada batafsil https://www.w3.org/TR/xpath/all/ havolasi orqali ma'lumot olishingiz mumkin.

### Koding
Demak telegram biz bergan havoladan kerakli ma'lumotlarni aniqlashi uchun biz unga shartlarni berishimiz kerak. Instant view uchun asosiy kerak bo'lgan ma'lumotlar bu sarlavha, sahifa muallifi, maqola yoki boshqa bir ma'lumot kontenti hisoblanadi.  Ushbu ma'lumotlarni yetkazish uchun esa yuqorida aytilganidek xpathdan foydalanamiz. Buning oson bir yo'li mavjud. Google chrome brauzeridagi elementlarni tekshirish oynasi (Inspect elements yoki devtools). Sahifadagi kerakli element ustiga bosib kontekst menudan inspect element bosilganda dev tools bizni aynan o'sha element tahrirlash qismiga olib boradi. Element ustiga kontekt menyu ochilganda `Copy->Copy Xpath` bo'limini tanaylmiz va sahifa uchun tayyor selektor mavjud bizda.

![xpath](https://i.ibb.co/PMmgTjw/photo-2022-07-07-01-14-36.jpg)

Demak sarlavha uchun:

```xpath
title: /html/body/main/div/h1
```

Maqol kontenti uchun:
```xpath
body: //div[has-class("w")]
```

Maqola muallifi haqida ma'lumot berish:
```
author: "Yetim dasturchi"
author_url: "https://t.me/yetimdasturchi"
```
E'tibor bergan bo'lsangiz yuqoridagi misolda xpath o'rniga oddiy stringdan foydalanilgan. Agarda qaysidir elementlar mavjud bo'lmasa ularni string yoki boshqa bir datatype bilan ham to'ldirsangiz bo'ladi.

### Mavzularni yanada kengaytirish
Yuqorida keltirilgan xpath orqali faqat sarlavha, muallif va kontentni belgiladik xolos. Lekin millionlab web sahifalar orasidagi elementlar uchun bo'gan shartlar doim ham yuqoridagi holatga mos kelmasligi mumkin. Ya'ni qaysidir elementni mavjudligini tekshirish yoki boshqa bir elementlarni olib tashlash kabi. Quyidagi jarayonda aynan shularni ko'rib chiqamiz.

E'tibor bergan bo'lsangiz mendagi blogda (manu.uno) maqola sarlavhasi va kontenti aynan bitta `<div class"w"></div>` element ichida joylashgan. Instant view sarlavhani kontentda takrorlamasligini ta'minlash uchun xpathdagi `@remove` funsksiyasidan foydalanishimiz mumkin.

```
@remove:$body//a[contains(@title,"orqaga")]
```

Biz `$body` uchun allaqachon div element kontentini kiritgan edik. Yuqoridagi holatda endi `$body` o'zgaruvchisi ichidan sarlavha o'zgaruvchisi (`@title`) va "orqaga" (blogda bir ortga harakatlanish) kontentida ishtirok etgan giperhavolani o'chirish kiritildi.

Yoki to'g'ridan to'g'ri `xpath` (element) orqali belgilash: 
```
@remove:$body//h1[1]
```
Hali ko'ngildagidek chiqmadimi?

![meme](https://i.ibb.co/0jzRpZ9/image-3.png)

Kontentni qayta ishlagandan so'ng u uchun muqova rasmini unutdik albatta. Bu jarayondagi oson yechim birinchi uchragan rasmni muqova uchun olish hisoblanadi:
```
cover: $@
```
Mavzularni tekshirish jarayonida foydalanuvchi tomonidan  kiritilgan havolada har doim ham maqolaga tegishli kontent keltirilmasligi mumkin. Masalan izlash sahifasi yoki boshqa bir shunga o'xshash dinamik sahifalar. Ushbu jarayonda xpathdagi exists funksiyasi bizdagi muammoni hal qilib bera oladi.

`<div class"w"></div>` elementi mavjud bo'lsa jarayonni amalga oshirish:
```
?exists: //div[@class="w"]
```

To'liq mavzu:

```xpath
title:  $body/html/body/main/div/h1
author: "Yetim dasturchi"
author_url: "https://t.me/yetimdasturchi"
cover: $@

body: //div[has-class("w")]
@remove:$body//a[contains(@title,"orqaga")]
@remove:$body//h1[1]
@remove:$body//blockquote[1]
@remove:$body//ul[@class="table-of-contents"]
@remove:$body//a[@class="heading-permalink"]
```
### Sahifani kuzatish

Mavzu to'laqonli kiritilgach tahrirlash oynasining yuqori o‘ng burchakdagi TRACK CHANGES havolasini bosing. Shundan so'ng bot mavzuni o‘zgartirgandan so‘ng paydo bo‘ladigan xatolar va o‘zgarishlar uchun ushbu sahifani kuzatib borishni boshlaydi.

### Yangi havolani ulashish

Instant viewni foydalanuvchilarga yetkazish uchun quyidagi formatdagi havoladan foyalanishingiz kerak:
```
https://t.me/iv?url={%URL%}&rhash={%RHASH%}
``` 
Misol:
```
https://t.me/iv?url=https://manu.uno/blog/deepin-linux.htm&rhash=51aed3cfea33c
```
Instant viewda o'ndan ortiq sahifalar qo'shganingizdan so'ng, "Submit template" tugmasini bosish orqali mavzuni to'liq domen uchun e'lon qilishingiz mumkin. Telegram administratsiyasi mavzuni tasdiqlagandan so'ng foydalanuvchilar tomonidan telegramda uzatilgan sizning domeningizga qarashli har qanday havola instant view bot orqali avtomatik tekshiruvni boshlaydi.

## Instant view qanday holatlar uchun arzimaydi?

Instant View mavzular yaratish faqat statik tarkibga ega sahifalar uchun foydalidir. Misol:
- Blog postlar
- Yangiliklar
- Wiki sahifalar
- Qo'llanmalar
- Turli xildagi mahsulot tavsiflari
---
Qanday sahifalar uchun arzimaydi:
- Ruknlar sahifasi
- Qidiruv natijalari
- Forumlar va sharhlar
- Har qanday dinamik yaratilgan sahifalar (masalan, mening asosiy blog sahifam kabi)
- Foydalanuvchi bilan muloqot qilish uchun belgilangan sahifalar (qayta aloqa sahifasi)
- Avtorizatsiya sahifalari

## Instant viewning audit uchun ta'siri

Agarda siz kuzatuvchilarga asosiy maqsad sifatida faqat ma'lumot berishni ko'zlagan bo'lsangiz instant view bu ajoyib imkoniyat. O'qish uchun juda qulay bu. Lekin siz maqola boshida aytilganidek ushbu sahifalar telegram serverida keshlanadi va foydalanuvchilar sizdagi instant havoladan foydalanganda asosiy sahifa uchun hostlar hisoblanmaydi.
