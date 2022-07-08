---
toc: false
title: "Telegram chat botni linux shell orqali boshqarish"
description: "Telegram chat botni os muhitida dastur kodlashtirish..."
keywords: "teleram, bot, chat, yaratish, yasash, bot yasash, so'rovlar, http, dasturlash"
---

## Curl kengaytmasini o'rnatish

Debian:
```
sudo apt install curl
```

Arch:
```
sudo pacman -S curl
```

## Foydalanish
```
chmod a+x bot.sh
bot.sh -h
bot.sh "Salom dunyo"
```

## bot.sh

```
#!/bin/bash
    
CHAT_ID="indefikator"
BOT_TOKEN="tokeningiz"

# Ilovadan foydalanish qo'llanmasi

if [ "$1" == "-h" ]; then
  echo "Foydalanish: `basename $0` \"xabar matni\""
  exit 0
fi

# Xabar kiritilmasa
if [ -z "$1" ]
  then
    echo "Xabar matnini kiriting"
    exit 0
fi

# Argumentlar soni bittadan ortiq bo'lsa
if [ "$#" -ne 1 ]; then
    echo "Faqatgina bitta argument qabul qilinadi. Xabar so'zlari alohida argument sifatida qabul qilinmasligi uchun xabarni qo'shtirnoq ichiga oling"
    exit 0
fi

# Curl orqali so'rovni yuborish
curl -s --data "text=$1" --data "chat_id=$CHAT_ID" 'https://api.telegram.org/bot'$BOT_TOKEN'/sendMessage' > /dev/null
```
