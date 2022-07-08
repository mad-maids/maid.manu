---
toc: true
title: "Raspberry PIni radioxuliganlarning orzusiga aylantiramiz"
description: "RPI bizga ortiqcha elektron komponentlarsiz radio uzatgich yaratishni amalga oshirishga yordam beradi..."
keywords: "Raspberry, radio, xuligan, modulator, eshittirish, fm, dizapazon, radiotexnika"
---

## RPI haqida yuz gramm

![rpi](https://www.distrelec.de/Web/WebShopImages/landscape_large/9-/01/Raspberry%20Pi-PI4%20MODEL%20B_1GB-30152779-01.jpg)

RPI bank kartasi hajmidagi kompyuter. U dastlab kamchiqim qurilma sifatida informatika fanini o'qitish uchun ishlab chiqilgan. Ammo keyinchalik o'quvchilar qolib robototexniklar orasida keng tarqaldi.

Uning yutug'i shundaki, kichkinagina qurilma sizga 133Mhzdan 1.5Ghz chastotaga ega protsessor, 264kb operativ xotiradan boshlab 8Gbgacha bo'lgan va bemalol 4K grafika bilan ishlay oladigan imkoniyatni yaratib beradi.

Kompyuter o'ziga asos sifatida linuxdan foydalanadi. Lekin u uchun ishlab chiqilgan windows iot, freebsd kabi oslar ham yo'q emas. Bugungi kunda 30ga yaqin keng qamrovdagi linux distrolar raspberryda aktiv ishlay oladi. Ulardan mukammal varianti Debian asosida qurilgan RasPiOS.

## Radio nima?

Sodda qilib aytsak, radio bu elektromagnit to'lqinlarini chiqaradigan yoki ularga javob beradigan qurilma. Eng sodda radio antenna, kirish konturi, demodulator kabi elementlardan tashkil topgan.

Kirish konturi
: Radio antennalariga kelib urilayotgan elektromagnit to'lqinlarni kerakli diapazonga qarab qabul qiluvchi blok.
: Misol: Siz kerakli tv kanal yoki radio eshittirish uchun chastotani sozlash vaqtidagi jarayon aynan shu qismda ta'sir beradi.

Modulator
: Garmonik tebranishlarni sun'iy hosil qilib beruvchi blok. Shu orqali yuqori va past chastotalarni bir-biriga qo'shib uzoq masfaga yuborishni ta'minlaydi.
: Misol: Oddiygina audio bu past chastotali signal. Uni uzoqroq masofaga yuborish uchun unga yuqori chastota qo'shish kerak bo'ladi. 

Demodulator
: Kirish konturi tomonidan qabul qilingan to'lqinlarni past va yuqori chastotaga ajratuvchi qism. 
: Misol: Buyerda yuqori chastotadan yuqorida modulator tomonidan yuborilgan audioni ajratib olinadi. Audio kuchaytirgichka, yuqori chastotali signal esa shunchaki boshqa bir elektr sig'imiga sarflanib yuboriladi (chunki endi bu keraksiz).

 Eng sodda modulator (Tebranishlar generatori) ko'rinishi:
 
![enter image description here](https://i.ibb.co/f2vFKyp/image.png)

Eng sodda radio ko'rinishi:

![enter image description here](https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Detector_receiver_rus_standart.svg/1200px-Detector_receiver_rus_standart.svg.png)

## Radio xuligan kim?

Radio xuligan bu bugungi kundagi qora xakerlarning bir ko'rinishi. Qora xakerlar buzish bilan shug'ullanishsa radioxuliganlar noqonuniy radio eshittirishlar olib borish bilan shug'ullanishadi. Hozirda bunday shaxslar juda kam. 20-asrning ikkinchi yarmida ya'ni radio erasining rivojlangan davrida bu juda rivojlangan ko'ngilochar bezoriliklardan biri bo'lgan.

## RPIni radioxuliganlar orzusiga qanday aylantiramiz?

Yuqoridagi qisqacha tariflarning o'zidayoq tasavvur qilish mumkinki oddiygina radio uzatgich yasashning o'zi elektronika tomondan juda katta bir muammoga aylanadi. RPI bizga ortiqcha elektron komponentlarni titkilamasdan shunchaki kod yozishni o'zi bilan ushbu ishni amalga oshirishga yordam beradi.

## Bu qanday ishlaydi?

Kundalik turmushdagi deyarli barcha qurilmalar o'zidan elektromagnit to'lqinlar tarqatadi. Odatda ular bizga kerakli diapazonga yetishi yoki undan ortib ketishi ham mumkin. Misol sabbuferda musiqa eshitayotgan vaqtda qo'ng'iroq bo'lib qolishi oqibatidagi chiyillashni ko'pchilik sezgan bo'lsa kerak. Bu ham shunday biz ahamiyat bermaydigan jarayonlardan biri. Kompyuterda ham bunday holat doimiy kuzatiladi. 

Raspberry linux yadrosi asosida ishlaydi. Biz esa /dev/mem va mmap yordamida 0x20000000 manziliga periferik shinani virtualizatsiya qilamiz ( *Linuxda /dev/mem asosan periferik qurilmalar bilan bog'liq kiritish va chiqarish xotira manzillariga yo'l sifatida ishlatiladi* ). Shundan so'ng raspberryda taktotali generator ishga tushib GPIO4 portiga  chiqish uchun o'rnatiladi. 

## Kodlashtirish


Konstantalarni sozlash:
```c
#define CM_GP0CTL (0x7e101070) // Taktota generatori boshqarish
#define GPFSEL0 (0x7E200000)   // GPIO liniyasi
#define CM_GP0DIV (0x7e101074) // Taktota generatori bo'linmasi
#define GPIO_BASE (0x7E200000)
#define ACCESS(offset, type) (*(volatile type*)(offset+(int)allof7e-0x7e000000)) // Register uchun ruxsat olish
```

Periferik shinani o'rnatish:

```c
void setup_fm(int state) {
    int mem_fd = open("/dev/mem", O_RDWR|O_SYNC);
    if (mem_fd < 0) {
        printf("/dev/mem manzilini ochishda xatolik\n");
        exit(-1);
    }
    allof7e = (unsigned char *)mmap(
                  NULL,
                  0x01000000,
                  PROT_READ|PROT_WRITE,
                  MAP_SHARED,
                  mem_fd,
                  0x20000000
              );

    if (allof7e == (unsigned char *)-1) {
        exit(-1);
    }

    //
    // GPIO 4 ni pulsatsiya qilish uchun sozlash.
    //
    struct GPFSEL0_T {
        char FSEL0 : 3;
        char FSEL1 : 3;
        char FSEL2 : 3;
        char FSEL3 : 3;
        char FSEL4 : 3;
        char FSEL5 : 3;
        char FSEL6 : 3;
        char FSEL7 : 3;
        char FSEL8 : 3;
        char FSEL9 : 3;
        char RESERVED : 2;
    };
    
    int tmp = ACCESS(GPFSEL0, int);
    tmp = (tmp | (1<<14)) & ~ ((1<<12) | (1<<13));
    ACCESS(GPFSEL0, int) = tmp;

    //
    // Taktota generatori uchun PLLD.
    //
    struct GPCTL {
        char SRC         : 4;
        char ENAB        : 1;
        char KILL        : 1;
        char             : 1;
        char BUSY        : 1;
        char FLIP        : 1;
        char MASH        : 2;
        unsigned int     : 13;
        char PASSWD      : 8;
    };
    ACCESS(CM_GP0CTL, struct GPCTL) = (struct GPCTL) {6, state, 0, 0, 0, state, 0x5a };
}
```

Modulatsiyalash jarayoni sinusi to'g'ri to'rtburchak asosidagi chastota hosil qilish bilan amalga oshirilib davriylik faqat 12 bit aniqligi uchun ishlaydi xolos

```c
void modulate(int period) {
    struct CM_GP0DIV_T {
        unsigned int DIV : 24;
        char PASSWD : 8;
    };
    ACCESS(CM_GP0DIV, struct CM_GP0DIV_T) = (struct CM_GP0DIV_T) { period, 0x5a };
}
```
To'lqinli audioni ( Wav ) modulatsiya jarayoni uchun uzatish:

```c
void delay(int n) {
    volatile static int clock = 0;
    for (int i = 0; i < n; ++i) {
        ++clock;
    }
}

void playWav(char *filename, int mod, float bw) {
    int fp = open(filename, 'r');
    lseek(fp, 22, SEEK_SET);
    short *data = (short *)malloc(1024);
    printf("Efirda: %s\n", filename);
    while (read(fp, data, 1024)) {

        for (int j = 0; j<1024/2; j++) {
            
            // Modulyatsiya davrini hisoblash
            float dval = (int) floor( (float) ( data[ j ] ) / 65536.0f * bw );

            // Wav shakllarini generatorga uzatish
            modulate(dval+mod);
            delay(6350);
        }
    }
}
```
Umumiy funksiyalarni birlashtirish
```c
int main(int argc, char **argv) {
    signal(SIGTERM, &shutdown_fm);
    signal(SIGINT, &shutdown_fm);
    atexit(&shutdown_fm);

    setup_fm(1);
    float freq = atof(argv[2]);
    float bw;
    int mod = (500/freq)*4096;
    modulate(mod);

    if (argc==3) {
         bw = 25.0;
         printf("Modulyatsiyani sozlash: %f Mhz / %d @ %f\n",freq,mod,bw);
         playWav(argv[1], mod, bw);
    } else if (argc==4) {
         bw = atof(argv[3]);
         printf("Modulyatsiyani sozlash: %f Mhz / %d @ %f\n",freq,mod,bw);
         playWav(argv[1], mod, bw);
    } else {
         fprintf(stderr,
                "Foydalanish: %s audio.wav chastota [to'lqin kuchi]\n\n"
                "Audio fayl talabi: 16 bit 22.050kHz Mono\n"
                "To'lqin kuchi maksimal 25gacha belgilanishi mumkin.",argv[0]);
    }
    return 0;
}
```
Kompiliyatsiya jarayoni:
```bash
gcc -std=c99 -g radio.c -o radio -lm
```

Modulatorni ishga tushirish:
- 100.00 bu uzatish chastotasi
- 25 modulatsiya kuchi

*Eslatma: To'lqin va to'lqin kuchi qanchalik yuqori bo'lsa cpudan shunchalik ko'p resurs talab qilinadi*
```bash
./radio /dev/stdin 100.00 25
```
## Eslatma
Maxsus ruxsatnomasiz radioeshittirishlar qilish, uzoq masofaga elektromagnit to'lqinlar yuborish O'zbekiston Respublikasi qonunchiligida taqiqlangan. 

Ushbu maqola faqatgina ta'lim maqsadida yozildi.

## Xulosa

Raspberry PI shunchaki linux distro o'rnatib test qilishdan tashqari juda ko'plab maqsadlar uchun foydali bo'la oladigan qurilma hisoblanadi. Ushbu maqolada birgina RPI va C tilidan foydalanib fm diapazon uchun ishlaydigan radio modulator yasash ko'rsatildi. Agarda siz buni elektron komponentlar bilan termoqchi bo'lsangiz bu juda bosh og'riq jarayon. Oldschool radiotexniklar bundan juda yaxshi xabardor. 

Shunday ekan atrofingizdagi qurilmalarni har doim qanday ishlashini kuzatishga o'rganing. Ular ba'zan juda murakkab bo'lsada juda qiziqarli narsalarni o'rgata oladi.
