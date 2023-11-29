<?php

namespace App\Helpers;

class ValidateCPF
{
    public static function validate(string $value): bool
    {
        $c = empty($value) ? '' : preg_replace('/[^\d]/', '', $value);

        if (11 != mb_strlen($c) || preg_match("/^{$c[0]}{11}$/", $c)) {
            return false;
        }

        for (
            $s = 10, $n = 0, $i = 0;
            $s >= 2;
            $n += $c[$i++] * $s--
        );

        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for (
            $s = 11, $n = 0, $i = 0;
            $s >= 2;
            $n += $c[$i++] * $s--
        );

        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }
}
