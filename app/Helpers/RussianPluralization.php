<?php
namespace App\Helpers;

class RussianPluralization {
    public static function make($number, ...$forms)
    {
        $n = $number % 100;
        $n1 = $number % 10;

        if ($n > 10 && $n < 20) {
            return $forms[2];
        }

        if ($n1 > 1 && $n1 < 5) {
            return $forms[1];
        }

        if ($n1 == 1) {
            return $forms[0];
        }

        return $forms[2];
    }
}

