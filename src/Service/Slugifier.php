<?php

namespace App\Service;

class Slugifier
{
    public function slugify(string $string): string
    {
        // Handmade slugifier but should resolve most cases
        $string = mb_strtolower($string);
        $string = str_replace(['!', '?', '/', '\\', '@', '&', '$', '€', '$', '%', '.'], '', $string);
        $string = trim($string);
        $string = str_replace(['é','è','ë'], 'e', $string);
        $string = str_replace(['à', 'ä'], 'a', $string);
        $string = str_replace(['ö', 'ò'], 'o', $string);
        $string = str_replace(['ü'], 'u', $string);
        $string = str_replace('ç', 'c', $string);
        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);

        return $string;
    }
}