<?php


function convertQuinseeToInsee(string $quinsee)
{
    $prefix = substr($quinsee, 0, 5);

    //two main digits goes from 01 to 20

    $twoMainDigit = (int) substr($quinsee, 3, 2);

    $lastTwoDigits = substr($quinsee, -2);
    // echo $twoMainDigit;
    if ($twoMainDigit === 1) {
        return $quinsee;
    }
    switch ($lastTwoDigits) {
        case '01':
            $convertLastDigits = 4 * $twoMainDigit - 3;
            break;
        case '02':
            $convertLastDigits = 4 * $twoMainDigit - 2;
            break;
        case '03':
            $convertLastDigits = 4 * $twoMainDigit - 1;
            break;
        case '04':
            $convertLastDigits = 4 * $twoMainDigit;
            break;

            break;
        default:
            $convertLastDigits = $lastTwoDigits;
    }
    // echo $convertLastDigits;
    if ($convertLastDigits < 10) {
        $convertLastDigits = '0' . $convertLastDigits;
    }

    return $prefix . $convertLastDigits;



}

echo convertQuinseeToInsee('7510304');
