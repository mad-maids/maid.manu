---
toc: false
title: "PHP Command-line interface argumentlarini REQUEST_URIga o'tkazish."
description: "Funksiya argumentlarni quyidagi ko'rinishda request uriga moslab beradi. Bu esa o'z navbatida bir vaqtda ro...."
keywords: "php, cli, argument, request, query, build"
---

Yaqinda bir loyiha uchun backend qism yozayotganda loyiha uri request va bir vaqtda cli orqali ishlashini yo'lga qo'yishga to'g'ri kelib qoldi. Odatda phpda `$_SERVER` global o'zgaruvchisidagi `argv` orqali cli orqali berilgan argumentlarni qabul qilish mumkin. Lekin istisno holatlarda uri request uchun http query o'zgaruchilarini ham qo'qishga to'g'ri kelib qoladi. Ushbu kichik kod aynan ushbu vazifasi soddalashtirish uchun xizmat qiladi.

Misol:

```bash
php agr.php sayhello --name "Toshmash" --logged -age="21"
```

Funksiya yuqoridagi argumentlarni quyidagi ko'rinishda request uriga moslab beradi. Bu esa o'z navbatida bir vaqtda routerlarda ishlashda cli uchun alohida sozlamalar tuzib chiqish oldini oladi hamda kodlar ixchamlashishiga xizmat qiladi.

```
sayhello?name=Toshmash&logged=&age=21
```

```php
function parseArguments() {
	$argumentsRaw = array_slice( $_SERVER['argv'], 1 );
    $argumentBuffer = '';
    $argumentsParsed = [];
    $route = [];
    foreach ($argumentsRaw as $argument) {
        if ($argument[0] == '-') {
            $argumentBuffer = substr($argument, ($argument[1] == '-' ? 2 : 1));
            $equalSign = strpos($argumentBuffer, '=');
            if ($equalSign !== false) {
                $argumentKey = substr($argumentBuffer, 0, $equalSign);
                $argumentsParsed[$argumentKey] = substr($argumentBuffer, $equalSign + 1);
                $argumentBuffer = '';
            } else {
                $argumentKey = $argumentBuffer;
                $argumentsParsed[$argumentKey] = '';
            }
        } else {
            if ($argumentBuffer != '') {
                $argumentKey = $argumentBuffer;
                $argumentsParsed[$argumentKey] = $argument;
                $argumentBuffer = '';
            }else{
            	$route[] = $argument;	
            }
        }
    }
    return ( $route ? implode('/', $route) : '' ) . '?' .http_build_query($argumentsParsed);
}

echo parseArguments(), PHP_EOL;
```
