<?php 
declare(strict_types=1);
namespace Example\Utility;

final class Random
{
    public static function generateNumbers(int $length) : int
    {
        $random = '';

        for ($i=0;$i<$length;$i++)
        {
            if ($i==0){$random.=rand(1, 9);}
            else {$random.=rand(0, 9);}
        }

        return intval($random);
    }

    public static function generateCharacters(int $length) : string
    {
        $random = '';
        $max = ceil($length / 40);

        for ($i = 0; $i < $max; $i ++) {
          $random .= sha1(microtime(true).mt_rand(10000,90000));
        }

        return substr($random, 0, $length);
    }
}