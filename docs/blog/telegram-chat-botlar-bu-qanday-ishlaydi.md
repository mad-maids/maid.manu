---
toc: true
title: "Telegram chat botlar. Bu qanday ishlaydi?"
description: "Chat botlar odamlarning texnologiya bilan o'zaro munosabatida inqilob qilmoqda. So'nggi yillarda ularning ishlab chi..."
keywords: "teleram, bot, chat, yaratish, yasash, bot yasash, so'rovlar, http, dasturlash"
---

Chat botlar odamlarning texnologiya bilan o'zaro munosabatida inqilob qilmoqda. So'nggi yillarda ularning ishlab chiqishdagi soddaligi turli sohalar foydalanuvchilar tomonidan qabul qilishi jarayonini ancha ildamladi.

Ayniqsa Pavel Durov bu sohada katta burilish yasay oldi. Telegram messengeri doirasida bugungi kunda ishlab chiqilgan yuz minglab va ularda foydalanadigan millionlab userlar mavjud.

## Chat botlar nimalar qila oladi?

1. Avtomatlashtirilgan tizimlarni joriy etish.
2. Tarmoqlar orqali to'lovlarni amalga oshirish.
3. Ijtimoiy xizmatlarni tashkil etish. Umumiy manfaatlar yoki yaqinlik asosida suhbatdoshlarni bir tomonlama inson omilisiz amalga oshirish.
4. Kundalik turmush tarzi uchun as qotadigan turli dasturiy mahsulotlarning ixcham versiyalarini yaratish
5. Umuman olganda chat botlar juda ko'p vazifalarni bajara oladi. Faqatgina ularning ishlash prinsipini bilish va dasturiy qismini ishlab chiqish uchun mantiq yetarli bo'lsa bas.

## Bu qanday ishlaydi
 
 Aslida chat botlar bugungi kundagi juda ko'plab platformalarda mavjud. Jumladan: facebook, wahatsapp, google... 
 Lekin istisno qilib aytish kerakki bugungi kunda bular orasida eng yetakchisi telegram hisoblanadi.

Durov mahsulotida o'zgachalik shundaki botlar yaratish uchun alohida akkount yaratishlar talab qilinmaydi, oddiy foydalanuvchi akkounti orqali amalga oshiriladi bu. Foydalanuvchilar ham botlar bilan ikki usulda ya'ni shaxsiy xabarlar yoki guruhlar orqali o'zaro aloqada bo'lishlari mumkin. Shuningdek kanallar bilan ishlay olish qobiliyatiga ham ega.

Bot ishlash jarayoni esa juda oddiy. Foydalanuvchilar tomonidan yuborilgan xabarlar, buyruqlar va so'rovlar dasturiy ta'minotga uzatiladi. Shundan so'ng telegram vositachi serveri orqali soddalashtirilgan HTTPS-interfeys yordamida bot boshqaruv amaliyotlarini o'tkazish mumkin.

Odatda dasturiy ta'minotga bog'lashning ikki usuli mavjud va ikki usul ham pinsip jihatdan bir-biridan farq qilmaydi.

### Birinchi usul
Webhook texnologiyasi. Bunda malum bir hostingga o'rnatilgan web dasturga telegram botga yanqilanishlar kelgan vaqtda telegram tomonidan chaqiruv amalga oshiriladi.

### Ikkinchi usul
Long polling. Bu usul ham yuqoridagigan farq qilmaydi. Faqatgina bu safar telegram serveri sizga emas, yangiliklarni olish uchun siz telegram serveriga murojaat qilasiz. Odatda bu jarayon kattaroq so'rovlar orqali ishlaydigan botlarda ishlaydi. Chunki telegram ayni damda bir sekund uchun botda webhook orqali faqat qirq dona xabarni qayta ishlay oladi.

## Dasturiy qism

Chat botlar ishlab chiqish uchun qaysi tilda amalga oshirish ahamiyatsiz. Muhimi http so'rovlar amalga oshirish imkoni mavjud bo'lsa bas. Python, php, c, c++ va shell kabi istalgan tilda ishlab chiqish mumkin albatta. Asosiysi so'rov yuborish uchun sizda api token bo'lsa bas.

### Bot yaratish

Api token olish va umuman olgan bot uchun userlar yaratisg uchun telegramda @BotFather dan foydalaniladi. Botdan foydalanish juda oddiy. Shunchaki /start buyrug'idan so'ng /newbot, bot nomi va bot uchun username kiritish kifoya. Shunda so'ng botfather tomonidan sizga api token beriladi.

### HTTP so'rovlar

So'rovlar `https://api.telegram.org/botTOKEN/Metod` ko'rinishida amalga oshiriladi. POST yoki GET turlari orqali amalga oshirish mumkin albatta.

Metodlar esa bu bot uchun yuklatilgan vazifalarni anglanadi.

Misol uchun **getMe** metodida bot haqidagi ma'lumotlarni olish:
```
https://api.telegram.org/botTOKEN/getMe
```
```json
{
  "ok": true,
  "result": {
    "id": 000000000,
    "is_bot": true,
    "first_name": "Bot nomi",
    "username": "username_bot",
    "can_join_groups": true,
    "can_read_all_group_messages": false,
    "supports_inline_queries": true
  }
}
```

Yoki biror bir foydalanuvchiga xabar yuborishni ko'raylik:

*Eslatman: Foydalanuvchi bot uchun start buyrug'ini bergan bo'lishi lozim*
```
https://api.telegram.org/botTOKEN/sendMessage?chat_id=000000000&text=Salom+dunyo
```
Yuqoridagi so'rovda **chat_id** bu xabar yuborish kerak bo'lgan foydalanuvchining telegram idenfikatori. **text**  xabar matni.

### Webhook

Webhook uchun sizda telegram va veb server orasida shifrlangan aloqa bo'lishi lozim. Ya'ni ssl sertifikat. 80-portga joylashtirilgan web ilovani (masalan php script) ni telegram bot sozlamasiga kiritish lozim. Shundan so'ng botdagi yangilanishlar shu manzildagi scriptga stdin orqali kelib tushadi va  so'rovlar shartlar orqali javob berish mumkin.

Webhookni sozlash:

```
https://api.telegram.org/botTOKEN/setWebhook?url=https://test.dev/bot.php
```

Webhook orqali yangilanish dasturga json formatida keladi. Shundan so'ng siz jsonni o'zingizga maqul variantda massivga o'tkazib massiv kontentiga qarab bot amaliyotini amalga oshirasiz.

Misol ( izohlar asosida berilgan ):

```
Array
(
    [update_id] => 0000000000
    [message] => Array
        (
            // Xabar indenfikatori
            [message_id] => 1
            // Xabar kim tomonidan yuborilgan?
            [from] => Array
                (
                    [id] => 0000000000
                    [is_bot] => 
                    [first_name] => Eshonov
                    [last_name] => Bahodir
                    [username] => lotoreya
                    [language_code] => en
                )
			// Bot xabarlashuvni amalga oshirayotgan chat
            [chat] => Array
                (
                    [id] => 0000000000
                    [first_name] => Eshonov
                    [last_name] => Bahodir
                    [username] => lotoreya
                    [type] => private
                )
			// Xabar yuborilgan vaqt
            [date] => 1656784173
            // Foydalanuvchidan yuborilgan matn
            [text] => /start
            // Xabar tasnifi
            [entities] => Array
                (
                    [0] => Array
                        (
                            [offset] => 0
                            [length] => 6
                            [type] => bot_command
                        )

                )

        )

)
```

### Long polling
Ushbu usulda avval ayganimdek telegram sizga emas siz yangilanishlarni qabul qilish uchun telegramga so'rov berasiz.

```
https://api.telegram.org/botTOKEN/getUpdates
```
Yuqoridagi so'rovdan so'ng telegram api sizga xuddi webhookdagidek massiv qaytaradi. Faqatgina bunda ko'p o'lchovli massiv ya'ni bitta xabar alohida massiv emasu barcha yangi xabarlar umumiy massivda keladi. 

### Webhook va Long polling farqlari

Webhok qulayliklari
: Bir tomonlama aloqa
: Doimiy yangilanishlarni tekshirib turish shart emas
: Tezkorlik
: Individual muloqot 

Webhook noqulayliklari
: Katta auditoriya ega botlarda botning tez-tezt blok holatiga tushib qolishi
: Xuddi ddos kabi web serverga tushadigan yuklamalar (shu narsa sabab ko'plab hostinglar telegram apini bloklaydi o'zida)
---
Long polling qulayliklari
: Katta auditoriya bilan ishlay olish
: Bot dasturini istalgan qurilmada ishga tushira olish. Uydagi kompyuterda ham.

Long polling noqulayliklari
: Yangilanishlari tekshirib turish lozim
: Bot uchun doimiy serverlar topish qiyinroq
: Yangilanishlarni tekshirish vaqti webhookga nisbatan sekinroq ketadi. Webhook kabi ko'p oqimlarda ishlay olmaydi.

## Misol
Quyidagi bot dasturchi PHP dasturlash tilida yozilgan. Ushbu kodni istalgan veb hostingga joylab, webhook orqali bot qanday ishlashini kuzatishingiz mumkin.

```php
<?php

$bot_token = ''; // Bot tokeni

/*
	Http so'rovlarni curl orqali amalga oshirish
*/
function request($method, $content=[]) {
	$url = 'https://api.telegram.org/bot' . $GLOBALS['bot_token'] . '/' . $method;
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, TRUE);
}

/*
	sendChatAction metodini amalga oshirish. Bu bot yozmoqda yoki media fayl yubormoqda kabi jarayonni simulatsiya qilish uchun
*/

function sendChatAction( $chat_id, $action ){
	$actions = array(
        'typing',
        'upload_photo',
        'record_video',
        'upload_video',
        'record_audio',
        'upload_audio',
        'upload_document',
        'find_location',
    );
    if ( in_array( $action, $actions ) ) {
        return request( 'sendChatAction', compact( 'chat_id', 'action' ) );
    }

    return FALSE;
}

/*
	Xabar yuborish
*/

function sendMessage($chat_id, $text){
	return request('sendMessage', compact( 'text', 'chat_id' ));
}

/*
	Rasm yuborish
*/

function sendPhoto($chat_id, $photo, $caption = ''){
	return request('sendPhoto', compact('chat_id', 'photo', 'caption') );
}

/*
	Webhook orqali kelgan yangilanishlarni $updates o'zgaruvchisiga qabul qilish
*/
$updates = json_decode(file_get_contents('php://input'), TRUE);
// Debug uchun yangilanishlarni dasturchi akkauntiga yuborish
// sendMessage('dasturchi_idsi', print_r($updates, TRUE));		

/*
	Yangilanishlarda xabar matni mavjud bolsa xabar kontektiga qarab shartlarni bajarish.
	Eslatma: ushbu misolda faqat xabarlarni qayta ishlash ko'rsatilgan. Foydalanuvchidan kelgan medialar yoki boshqa bir xabar turlari uchun massivda message kalitidan boshqa kalitlar keladi. Jarayon ham shunga qarab o'zgaradi.
*/

if ( !empty( $updates['message'] ) ) {
	$text  = $updates['message']['text']; //Foydalanuvchidan kelgan xabar
	$chat_id  = $updates['message']['chat']['id']; //Foydalanuvchi idenfikatori
	
	
	if( $text == '/start' || preg_match('/\/start@(.+)/', $text) ){
		/*
			Agarda xabar /start matniga mos kelsa. preg_match guruhdan kelgan xabarlarni regex orqali tekshirish uchun. Ya'ni guruhdan kelgan xabarlar /	start@guruh_usernamesi shaklida bo'ladi.
		*/
		sendChatAction( $chat_id, 'typing' ); // Foydalanuvchiga bot yozmoqda hodisasini yuborush
		sendMessage( $chat_id, 'Birinchi telegram botingizga xush kelibsiz. /me buyrug‘i orqali men haqimda ma’lumot olishingiz mumkin.' ); // Foydalanuvchiga bot tomonidan xabar yo'llash.
	}else if( $text == '/me' || preg_match('/\/me@(.+)/', $text) ){
		/*
			Agarda xabar /me buyrug'iga mos kelsa. 
		*/
		sendChatAction( $chat_id, 'upload_photo' ); // Foydalanuvchiga bot rasm yubormoqda hodisasini yuborush
		
		$photo_url = "https://i.ibb.co/jzRQt3f/photo-2022-06-14-03-06-21.jpg"; //rasm manzili
		$caption = "Men yetim dasturchi tomonidan yozilgan tes botman. Menga o'xshagan botlarni ishlab chiqish haqida @yetimdasturchi telegram kanalida ko'proq ma'lumot olishingiz mumkin.";

		sendPhoto( $chat_id, $photo_url, $caption );  // Foydalanuvchiga bot tomonidan rasm yuborish
	}else{
		sendChatAction( $chat_id, 'typing' );
		sendMessage( $chat_id, 'Kechirasiz, men sizni tushuna olmadim. Mening vazifalarim doirasiga siz buyruqga javob berish kiritilmagan.' );
	}
}
```
