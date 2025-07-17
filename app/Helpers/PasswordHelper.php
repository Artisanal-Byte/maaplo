<?php

namespace App\Helpers;

class PasswordHelper
{
    public static function generatePassword(int $length = 12): string
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $digits = '0123456789';
        $special = '@$!%*?&';

        // Ensure the password contains at least one of each
        $password = [
            $upper[random_int(0, strlen($upper) - 1)],
            $lower[random_int(0, strlen($lower) - 1)],
            $digits[random_int(0, strlen($digits) - 1)],
            $special[random_int(0, strlen($special) - 1)],
        ];

        $all = $upper . $lower . $digits . $special;
        for ($i = 4; $i < $length; $i++) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        shuffle($password);
        return implode('', $password);
    }
}
