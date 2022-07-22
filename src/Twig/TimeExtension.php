<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TimeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('ago', [$this, 'diff']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('diff', [$this, 'diff']),
        ];
    }

    public function diff(DateTime $from)
    {
        $today = new DateTime();
        $interval = $today->diff($from);
        $totalDays = $interval->days;
        $totalMonths = $interval->m + ($interval->y * 12);
        $totalYears = $interval->y;
        $totalHours = $interval->h;
        $totalMinutes = $interval->i;

        if($totalYears > 1){
            return $interval->format('%y years ago');
        } else if($totalYears === 1){
            return $interval->format('%y year ago');
        } else if($totalMonths > 1){
            return $interval->format('%m months ago');
        } else if($totalMonths === 1){
            return $interval->format('%m month ago');
        } else if($totalDays > 1){
            return $interval->format('%a days ago');
        } else if($totalDays === 1) {
            return $interval->format('%a day ago');
        } else if($totalHours > 1) {
            return $interval->format('%h hours ago');
        } else if($totalHours === 1) {
            return $interval->format('%h hour ago');
        } else if($totalMinutes > 1) {
            return $interval->format('%i minutes ago');
        } else if($totalMinutes === 1) {
            return $interval->format('%i minute ago');
        } else {
            return $interval->format('now');
        }
    }
}
