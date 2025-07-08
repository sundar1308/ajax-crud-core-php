<?php
$s = 'tcaa';
$n = 10;
function repeatedString($s, $n)
{
    // $string = '';
    // $arr = explode(',', $s);
    // while (strlen($string) < $n) {
    //     if ($s[0] == 'a' && strlen($s) == 1) {
    //         return $n;
    //     }
    //     for ($i = 0; $i < strlen($s); $i++) {
    //         if (strlen($string) < $n) {
    //             if ($s[$i] == 'a') {
    //                 $count++;
    //             }
    //             $string .= $s[$i];
    //         } else {
    //             break;
    //         }
    //     }
    // }
    $count = 0;

    for ($j = 0; $j < strlen($s); $j++) {
        if ($s[$j] == 'a') {
            $count++;
        }
    }
    $d = floor($n / strlen($s));
    $count *= $d;
    $k = $n % strlen($s);
    for ($i = 0; $i < $k; $i++) {
        if ($s[$i] == 'a') {
            $count++;
        }
    }
    return $count;
}
// print_r(repeatedString($s, $n));


$arr = [1, 2, 3, 1, 2, 3, 3, 3, 1, 1, 1];
function equalizeArray($arr)
{
    $a = array_count_values($arr);
    arsort($a);
    $max = array_keys($a)[0];

    return count(array_filter($arr, function ($it) use ($max) {
        return $it != $max;
    }));
}
// print_r(equalizeArray($arr));
$s = 'sundadm';
function repeatedString2($s)
{
    $repeatedStr = '';
    for ($i = 0; $i < strlen($s); $i++) {
        for ($j = 0; $j < strlen($s); $j++) {
            if (($i != $j) && $s[$i] == $s[$j]) {
                $repeatedStr .=  $s[$i];
            }
        }
    }
    return $repeatedStr;
}
// print_r(repeatedString2($s));
echo ("test");
