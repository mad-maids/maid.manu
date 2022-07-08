---
toc: true
title: "Led ring uchun ikkinchi hayotni bag'shlash"
description: "Yaqinda veb kamera yorug'ligini ta'minlash uchun do'kondan Led RING sotib olishga to'g'ri keldi. Boshida muammosiz ishla..."
keywords: "led, svetodiod, eleketronika, arduino, video, python"
---

Yaqinda veb kamera yorug'ligini ta'minlash uchun do'kondan Led RING sotib olishga to'g'ri keldi. Boshida muammosiz ishladi. Bir kun o'tgach esa Gobliddin aka aytgandek Xitoyni moli parot qilishni boshladi. Aniqroq aytadigan bo'lsam platadagi controller ishdan chiqqan edi. 

![led ring](https://i.ibb.co/DrDt4jB/godox-lr-120b-led-ring-light.jpg)

Boshida shunchaki controllerni amashtirib tuzatish fikri tug'ildi. Lekin platani ochib ko'rganimda doimgidek mikrosxema haqida hech qanday ma'lumotlar mavjud emas. 

![plata](https://i.ibb.co/RTLz6J0/photo-2022-06-19-19-36-00.jpg)

Asosiysi svetodiodlar butunligi. Platadagi controller ishdan chiqishi esa boshqa bir foydali tomonga yordam berdi. 
 Led ring uchun ikkita svetodiod ishlatilgan, biri sarg'ish, yana bir oq fon uchun. Controller vazifasi svetodiodni o'chirib yoqish, ikkita svetodiod rejimini almashtirish va yorqinlikni nazorat qilish edi.

Controllerdan keyingi o'rinlarda svetodiod uchun ikkita mosfet (led dimmer) qo'yilgan ekan. Demak yangi g'oya uchun yarim tayyor plata, qo'shimcha knopkalar ham yo'q emas.
![led dimmer](https://i.ibb.co/1LLCRM3/Sot-23-N-CH-3400-A09-T-30-V-5-6-A-SMD-LED-Driver-Dimmer-Transistor-Mosfet-with-Ro-HS-ISO.jpg)

## G'oya o'zi qanday?

Platadagi controller ishlamagach uni qayta yasashga to'g'ri keladi. Albatta birgina potensiometr bilan ishni hal qilish mumkin aslida lekin bu yana mexanik harakatlarni yuzaga keltiradi.
Kompyuter qarshisidan turib yorug'likni sozlash kimga ham yoqardi deysiz.
Demak biz qayta qiladigan ishlar:
- O'chirib yoqishni ta'minlash
- Rejimlarni almashtira olish
- Yorqinlikni boshqarish
- Yoquridagi bandlarni kompyuter orqali ham bajara olishni ta'minlash

## Yechim

Velosipedni qayta ixtiro qilib o'tirish shart emas. Shu sababli ham tayyor arduino (atmega 328)dan foydalanish mumkin va bu ishni ancha osonlashtiradi ham. Kompyuter bilan ulash uchun plata o'zida tayor ch40 serial controller ham bor qaytaga.

## Sxematika
Led ring platasini modlash oson masala. A09T dimmer gate uchun arduinodagi interruptlardan foydalanamiz. Bu bizga yorqinlikni boshqarish uchun yuqori signal generatsiya qilishni ta'minlab bera oladi.
Qolgan buttonlarni esa istalgan pinlar orqali **INPUT_PULLUP** yordamida holatini boshqaramiz.

*INPUT_PULLUP arduinoda tashqi resistor yordamisiz ichki resiztordan source olish imkonini beradi. Bu bilan biz button va groundlar o'rtasida kalit yasash orqaligina yengil chiqa olamiz.*

## Arduino
```c
#include <EEPROM.h>

const int onOffPin = 2; // O'chirib yoqish tugmasi
const int modePin = 3; // Rejimni almashtirish tugmasi
const int upPin = 5; // Yorug'likni ko'tarish tugmasi
const int downPin = 4; // Yorug'likni pasaytirish tugmasi

const int ledF= 11; // PIN11 (interrupt)
const int ledS= 10; // PIN10 (interrupt)

const int maxBrightness= 10; // map funksiyasiga asoslanib 0-255gacha darajalarni chiqarish
const int brightnessInterval=1; // har bir daraja intervali

int onOffState = 0;
int mode = 1;
int brightness = maxBrightness;

unsigned long buttonMillis = 0;
unsigned long serialMillis = 0;

void setup() {
  Serial.begin(9600);

  // Pinlarni sozlash
  pinMode(onOffPin, INPUT_PULLUP);
  pinMode(modePin, INPUT_PULLUP);
  pinMode(upPin, INPUT_PULLUP);
  pinMode(downPin, INPUT_PULLUP);

  // EEPROM xotiradan konfiguratsiyalarni o'qish
  int c_onfstate, c_mode, c_brightness;
  c_onfstate = EEPROM.read(1);
  c_mode = EEPROM.read(2);
  c_brightness = EEPROM.read(3);

  // Joriy konfiguratsiyalarni moslash
  if(c_onfstate != 255){
    onOffState = c_onfstate;
  }
  if(c_mode != 255){
    mode = c_mode;
  }
  if(c_brightness != 255){
    brightness = c_brightness;
  }
}
void loop() {
  // Serial (kompyuter orqali kelgan ma'lumotlarni o'qish)
  if( Serial.available() > 0 ){
    int val = Serial.read();
    Serial.println(val);
    if ( val == 49 ) {
      onOffState = 1;
      EEPROM.write(1, 1);
    }
    if ( val == 50 ) {
      onOffState = 0;
      EEPROM.write(1, 0);
    }
    if ( val == 51 ) {
      mode = 1;
      EEPROM.write(2, 1);
    }
    if ( val == 52 ) {
      mode = 2;
      EEPROM.write(2, 2);
    }
    if ( val == 53 ) {
      mode = 3;
      EEPROM.write(2, 3);
    }
    if ( val == 54 && brightness < maxBrightness && onOffState) {
      brightness = brightness + brightnessInterval; 
      EEPROM.write(3, brightness);
    }
    if ( val == 55 && brightness > 0 && onOffState ) {
      brightness = brightness - brightnessInterval;
      EEPROM.write(3, brightness);
    }
  }

  // Tugma holatlarini kuzatish
  unsigned long currentMillis = millis();
  if(currentMillis - buttonMillis > 500){
    if (digitalRead(onOffPin) == LOW) {
      onOffState = !onOffState;
      EEPROM.write(1, onOffState);
      buttonMillis = currentMillis; 
    }
    if (digitalRead(modePin) == LOW && onOffState) {
      switch (mode) {
        case 1:
          mode = 2;
        break;
        case 2:
          mode = 3;
        break;
        case 3:
          mode = 1;
        break;
      }
      EEPROM.write(2, mode);
      buttonMillis = currentMillis; 
    }
    if (digitalRead(upPin) == LOW && brightness < maxBrightness && onOffState) {
      brightness = brightness + brightnessInterval;
      EEPROM.write(3, brightness);
      buttonMillis = currentMillis; 
    }
    if (digitalRead(downPin) == LOW && brightness > 0 && onOffState ) {
      brightness = brightness - brightnessInterval;
      EEPROM.write(3, brightness);
      buttonMillis = currentMillis; 
    }
  }

  // Tizimni ishga tushirish
  if(onOffState){
    if(mode == 1 || mode == 3){
      analogWrite(ledF, map(brightness, 0, maxBrightness, 0, 255));      
    }else{
      digitalWrite(ledF, LOW);
   }
    if(mode == 2 || mode == 3){
      analogWrite(ledS, map(brightness, 0, maxBrightness, 0, 255));      
    }else{
      digitalWrite(ledS, LOW);  
    }
  }else{
    digitalWrite(ledF, LOW);
    digitalWrite(ledS, LOW);  
  }
  
  // Agarda kompyuter bilan sinxronizatsiya qilish kerak bo'lsa o'zgaruvchilarni kompyuterga yuborish
  if (currentMillis - serialMillis >= 300) {
    serialMillis = currentMillis;
    Serial.println( String( onOffState)  + '|' + String(mode) + '|' + String(brightness ) );
  }

  // Jarayonni stabillashtirish
  delay(10);
}
```

## Python

Kompyuter orqali boshqarish uchun eng maqul yo'l python dasturlash tilini tanlash. Tabga ko'ra boshqa til orqali ham ushbu ishni amalga oshirish mumkin.

```python
import serial
import time
import tkinter
from tkinter import ttk
from pystray import MenuItem as item
import pystray
from PIL import Image, ImageTk

ser = serial.Serial('/dev/ttyUSB0', 9600) # Arduino porti (Windows uchun COM_X ko'rinishida beriladi)
#time.sleep(3)

def button_on():
	ser.write(bytes('1', 'UTF-8'))

def button_off():
	ser.write(bytes('2', 'UTF-8'))

def button_m1():
	ser.write(bytes('3', 'UTF-8'))

def button_m2():
	ser.write(bytes('4', 'UTF-8'))

def button_m3():
	ser.write(bytes('5', 'UTF-8'))

def button_up():
	ser.write(bytes('6', 'UTF-8'))

def button_down():
	ser.write(bytes('7', 'UTF-8'))

def aboutprogram():
	about_win = tkinter.Toplevel(win)
	ws = win.winfo_screenwidth()
	hs = win.winfo_screenheight()
	about_win.resizable(0, 0)
	sx = (ws/2) - (160/2)
	sy = (hs/2) - (100/2)
	about_win.geometry('%dx%d+%d+%d' % (160, 100, sx, sy))
	about_win.resizable(0, 0)
	about_win.title("Dastur haqida")
	
	comp = tkinter.Label(about_win, text ="Yetim dasturchi\npiradakshin :)\n\nwww.manu.uno\n@yetimdasturchi")
	comp.pack(pady = 25, side = tkinter.RIGHT)
	comp.configure(font=("Courier", 12))
	comp.place(x=3, y=4)

def quit_window(icon, item):
   icon.stop()
   win.destroy()

def show_window(icon, item):
   icon.stop()
   win.after(0,win.deiconify())

def hide_window():
   win.withdraw()
   image=Image.open("favicon.ico")
   menu=(item('Ochish', show_window), item('Chiqish', quit_window))
   icon=pystray.Icon("name", image, "Manu Light", menu)
   icon.run()

win = tkinter.Tk()
#win.geometry("800x600")
win.title("Led") #Manu ledlight
#win.iconbitmap(default=resource_path("icon.ico"))
win.resizable(0, 0)
ws = win.winfo_screenwidth()
hs = win.winfo_screenheight()
sx = (ws/2) - (166/2)
sy = (hs/2) - (166/2)
win.geometry('%dx%d+%d+%d' % (166, 166, sx, sy))
win.protocol('WM_DELETE_WINDOW', hide_window)

button_on = tkinter.Button(win,
    text="Yoqish",
    command=button_on,
    height = 1,
    fg = "black",
    width = 5,
    bd = 1,
)
button_on.pack(side='top', ipadx=5, padx=5, pady=5)
button_on.place(x=2, y=2)

button_off = tkinter.Button(win,
    text="O'chirish",
    command=button_off,
    height = 1,
    fg = "black",
    width = 5,
    bd = 1,
)
button_off.pack(side='top', ipadx=5, padx=5, pady=5)
button_off.place(x=90, y=2)

separator = ttk.Separator(win, orient='horizontal')
separator.place(relx=0, rely=0.22, relwidth=1, relheight=1)

button_m1 = tkinter.Button(win,
    text="M1",
    command=button_m1,
    height = 1,
    fg = "black",
    width = 2,
    bd = 1,
)
button_m1.pack(side='top', ipadx=5, padx=5, pady=5)
button_m1.place(x=2, y=42)

button_m2 = tkinter.Button(win,
    text="M2",
    command=button_m2,
    height = 1,
    fg = "black",
    width = 2,
    bd = 1,
)
button_m2.pack(side='top', ipadx=5, padx=5, pady=5)
button_m2.place(x=59, y=42)

button_m3 = tkinter.Button(win,
    text="M3",
    command=button_m3,
    height = 1,
    fg = "black",
    width = 2,
    bd = 1,
)
button_m3.pack(side='top', ipadx=5, padx=5, pady=5)
button_m3.place(x=117, y=42)

separator1 = ttk.Separator(win, orient='horizontal')
separator1.place(relx=0, rely=0.47, relwidth=1, relheight=1)

button_up = tkinter.Button(win,
    text="+",
    command=button_up,
    height = 1,
    fg = "black",
    width = 5,
    bd = 1,
)
button_up.pack(side='top', ipadx=5, padx=5, pady=5)
button_up.place(x=2, y=84)

button_down = tkinter.Button(win,
    text="-",
    command=button_down,
    height = 1,
    fg = "black",
    width = 5,
    bd = 1,
)
button_down.pack(side='top', ipadx=5, padx=5, pady=5)
button_down.place(x=90, y=84)

separator2 = ttk.Separator(win, orient='horizontal')
separator2.place(relx=0, rely=0.72, relwidth=1, relheight=1)

aboutprogram = tkinter.Button(win,
    text="Dastur haqida",
    command=aboutprogram,
    height = 1,
    fg = "black",
    width = 15,
    bd = 1,
)
aboutprogram.pack(side='top', ipadx=5, padx=5, pady=5)
aboutprogram.place(x=2, y=127)

#win.state("zoomed")
win.mainloop()
```
