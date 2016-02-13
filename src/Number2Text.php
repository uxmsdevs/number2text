<?php namespace Uxms\Number2Text;

/**
 * Creates textual equivalent of given numbers which as long as 450 digits (in Turkish)
 */
/**
 * Verilen bir sayinin turkce olarak metinsel okunusunu verir
 */
class Number2Text
{
    /* Original number as int form */
    /* Verilen sayinin int olarak orjinali */
    private $originalNumber;

    /* Calculating step count of the number */
    /* Sayinin ayrilmis basamak sayisini buluyoruz */
    private $stepCount;

    /* Putting negativity based on the number's status */
    /* Sayinin negatiflik durumuna gore basina eksi yazdiriyoruz */
    private $negative;

    /*
     * Amounts allocated to the triple digit number sets.
     * This provide us to break the limit of integer, so 450 digit processing will be possible
     */
    /*
     * Uclu basamaklarina ayrilmis sayi kumelerini tutar.
     * Bu sayede integer limitinden kurtulmus oluyoruz
     */
    private $step = [];

    /* String form of the allocated triple digit numbers */
    /* Uclu basamaklarina ayrilmis yeni sayinin string olarak metnini tutar */
    public $newNumber = [];

    private $unitsDigits = ['', 'bir', 'iki', 'üç', 'dört', 'beş', 'altı', 'yedi', 'sekiz', 'dokuz'];
    private $tensDigits = ['', 'on', 'yirmi', 'otuz', 'kırk', 'elli', 'altmış', 'yetmiş', 'seksen', 'doksan'];
    private $biggerDigits = ['', 'yüz', 'bin', 'milyon', 'milyar', 'trilyon', 'katrilyon', 'kentilyon', 'seksilyon',
        'septilyon', 'oktilyon', 'nonilyon', 'desilyon', 'undesilyon', 'dodesilyon', 'tredesilyon', 'kattuordesilyon',
        'kendesilyon', 'sexdesilyon', 'septendesilyon', 'oktodesilyon', 'novemdesilyon', 'vigintilyon', 'unvigintilyon',
        'dovigintilyon', 'trevigintilyon', 'kattuorvigintilyon', 'kenvigintilyon', 'sexvigintilyon', 'septenvigintilyon',
        'oktovigintilyon', 'novemvigintilyon', 'trigintilyon', 'untrigintilyon', 'dotrigintilyon', 'tretrigintilyon',
        'kattuortrigintilyon', 'kentrigintilyon', 'sextrigintilyon', 'septentrigintilyon', 'oktotrigintilyon',
        'novemtrigintilyon', 'katragintilyon', 'unkatragintilyon', 'dokatragintilyon', 'trekatragintilyon',
        'kattuorkatragintilyon', 'kenkatragintilyon', 'sexkatragintilyon', 'septenkatragintilyon', 'oktokatragintilyon',
        'novemkatragintilyon', 'kenquagintilyon', 'unkenquagintilyon', 'dokenquagintilyon', 'trekenquagintilyon',
        'kattuorkenquagintilyon', 'kenkenquagintilyon', 'sexkenquagintilyon', 'septenkenquagintilyon',
        'oktokenquagintilyon', 'novemkenquagintilyon', 'sexagintilyon', 'unsexagintilyon', 'dosexagintilyon',
        'tresexagintilyon', 'kattuorsexagintilyon', 'kensexagintilyon', 'sexsexagintilyon', 'septensexagintilyon',
        'oktosexagintilyon', 'novemsexagintilyon', 'septuagintilyon', 'unseptuagintilyon', 'doseptuagintilyon',
        'treseptuagintilyon', 'kattuorseptuagintilyon', 'kenseptuagintilyon', 'sexseptuagintilyon',
        'septenseptuagintilyon', 'oktoseptuagintilyon', 'novemseptuagintilyon', 'oktogintilyon', 'unoktogintilyon',
        'dooktogintilyon', 'treoktogintilyon', 'kattuoroktogintilyon', 'kenoktogintilyon', 'sexoktogintilyon',
        'septenoktogintilyon', 'oktooktogintilyon', 'novemoktogintilyon', 'nonagintilyon', 'unnonagintilyon',
        'dononagintilyon', 'trenonagintilyon', 'kattuornonagintilyon', 'kennonagintilyon', 'sexnonagintilyon',
        'septennonagintilyon', 'oktononagintilyon', 'novemnonagintilyon', 'sentilyon', 'senuntilyon', 'sendotilyon',
        'sentretilyon', 'senkattuortilyon', 'senkentilyon', 'sensextilyon', 'senseptentilyon', 'senoktotilyon',
        'sennovemtilyon', 'sendesilyon', 'senundesilyon', 'sendodesilyon', 'sentredesilyon', 'senkattuordesilyon',
        'senkendesilyon', 'sensexdesilyon', 'senseptendesilyon', 'senoktodesilyon', 'sennovemdesilyon', 'senvigintilyon',
        'senunvigintilyon', 'sendovigintilyon', 'sentrevigintilyon', 'senkattuorvigintilyon', 'senkenvigintilyon',
        'sensexvigintilyon', 'senseptenvigintilyon', 'senoktovigintilyon', 'sennovemvigintilyon', 'sentrigintilyon',
        'senuntrigintilyon', 'sendotrigintilyon', 'sentretrigintilyon', 'senkattuortrigintilyon', 'senkentrigintilyon',
        'sensextrigintilyon', 'senseptentrigintilyon', 'senoktotrigintilyon', 'sennovemtrigintilyon',
        'senkatragintilyon', 'senunkatragintilyon', 'sendokatragintilyon', 'sentrekatragintilyon',
        'senkattuorkatragintilyon', 'senkenkatragintilyon', 'sensexkatragintilyon', 'senseptenkatragintilyon',
        'senoktokatragintilyon'
    ];

    public function __construct($givenNumber)
    {
        /* Assigning number to variable */
        /* Verilen sayiyi, sayi degiskenimize tanimliyoruz */
        $this->originalNumber = $givenNumber;

        /*
         * This limitation is based on limits of biggerDigits strings
         * If biggerDigits populated, this could be extended.
         */
        /*
         * 450 karakter uzunluguna kadar olan sayilarin okunusunu bildigimiz icin limitliyoruz.
         * Eger basamaklar degiskenine yeni eklemeler yapilirsa bu kisim genisletilebilir
         */
        if (strlen($this->originalNumber) > 450) {
            die('Maximum 450 digits accepted..');
        }

        /*
         * Allocate number to triple digits. Every tripler will be handled separately
         * Turkish language is suitable for this kind processing
         */
        /*
         * Sayiyi ucer ucer basamaklara ayiriyoruz. Her 3 lu grup ayri ele alinacak.
         * Turkce dili bu yapiya uygun. Bu sayede cok uzun sayilarin da okunusunu elde edebilecegiz
         */
        $this->step = array_reverse(str_split(strrev($this->originalNumber), 3));

        /* Finding allocated step count */
        /* Ayrilmis basamak sayisini buluyoruz */
        $this->stepCount = count($this->step);

        /* Method reforms the number */
        /* Sayinin duzenlenmesi islemlerini bu metodumuz gerceklestiriyor */
        $this->reFormNumber();

        /* Method converts number to textual format */
        /* Duzenlenmis sayiyi son olarak metinsel ifadeye ceviriyoruz */
        $this->convertToTextual();
    }

    private function isNegative($number = 0)
    {
        return ($number < 0) ? true : (($number > 0) ? false : 0);
    }

    private function reFormNumber()
    {
        /* If number is negative, we should put "Eksi" string to the header of the text */
        /* Sayimiz negatifse basina eksi yazalim */
        if ($this->isNegative($this->originalNumber)) {
            $this->negative = 'eksi ';
        }

        /* For every group of 3 */
        /* Her 3'lu grup icin */
        for ($i = 0; $i < $this->stepCount; ++$i) {

            /* Because of number reversed, we should re-reverse here */
            /* Sayiyi basamaklarina ayirdigimizda basamaklar tersine dondugu icin burada basamaklari duzeltiyoruz */
            $this->step[$i] = strrev($this->step[$i]);

            /*
             * If step is 4, 8, 15, 16, 23, 42 which has 1 or 2 digit,
             * let's add 0's to header
             */
            /*
             * Eger basamak 4, 8, 15, 16, 23, 42 gibi 1 veya 2 rakamliysa
             * basina 3'e tamamlayacak sekilde "0" ekliyoruz
             */
            if (strlen($this->step[$i]) == 1) {
                $this->step[$i] = '00'.$this->step[$i];
            } elseif (strlen($this->step[$i]) == 2) {
                $this->step[$i] = '0'.$this->step[$i];
            }
        }

        /* Fixed form of the number */
        /* Sayinin duzeltilmis halini geri dönderiyoruz */
        return $this->step;
    }

    private function convertToTextual()
    {
        /* For each of the step, we are concatenating proper string to newNumber variable */
        /* newNumber degiskenine her basamak icin ayri ayri yazdiriyoruz */
        foreach($this->step as $s) {

            /* If 1. digit of step (hundreds digit) bigger than zero */
            /* Basamagin 1. rakami (yuzler hanesi) 0'dan buyukse */
            if ($s[0] > 0) {
                /* Adding textual converts and also "yuz" to the variable */
                /* Degiskene rakamin harfle yazilisi ve "yuz" ifadesini ekliyoruz */
                $this->newNumber[] = ($s[0] > 1 ? $this->unitsDigits[$s[0]] . '' : '') . $this->biggerDigits[1];
            }

            /* If 2. digit of step (tens digit) bigger than zero */
            /* Basamagin 2. rakami (onlar hanesi) 0'dan buyukse */
            if ($s[1] > 0){
                /* Adding textual converts to the variable */
                /* Degiskene rakamin harfle yazilisini ekliyoruz */
                $this->newNumber[] = $this->tensDigits[$s[1]];
            }

            /*
             * If 3. digit of step (units digit) bigger than zero and step count equals to 2,
             * and first step equals to zero, second step equals to zero, third digit equals to one,
             * It's wiser saying to "Bin" rather than "Bir Bin" in turkish.
             */
            /*
             * Basamagin 3. rakami (birler hanesi) 0'dan buyukse ve basamak sayisi 2'ye esitse,
             * ve birinci basamak 0'a, ikinci basamak 0'a, ucuncu basamakta 1'e esitse,
             * "Bir Bin" ifadesi yerine "Bin" seklinde yazilmasini saglar
             */
            if ($s[2] > 0 && !($this->stepCount == 2 && $s[0] == 0 && $s[1] == 0 && $s[2] == 1)) {
                $this->newNumber[] = $this->unitsDigits[$s[2]];
            }

            /* 
             * Adding name of the step (bin, milyon, milyar) to variable.
             * Also checking if number is bigger than zero..
             */
            /* 
             * Degiskene basamagin ismini (bin, milyon, milyar) ekliyoruz.
             * Burada o basamakta bulunan sayinin 0'dan buyuklugune de bakiyoruz
             */
            if ($this->stepCount > 1 && ($s[0] > 0 || $s[1] > 0 || $s[2] > 0) ) {
                $this->newNumber[] = $this->biggerDigits[$this->stepCount];
            }

            /* Reducing the step count for proper converting */
            /* Basamak sayisini azaltiyoruz ki her basamagin sonuna ilkinde ne yaziyorsa o yazilmasin */
            --$this->stepCount;
        }

        $this->newNumber['str'] = $this->negative.implode(' ', $this->newNumber);
    }

    public function textual($ucFirst = false)
    {
        if ($ucFirst) {
            return ucfirst($this->newNumber['str']);
        } else {
            return $this->newNumber['str'];
        }
    }

}
