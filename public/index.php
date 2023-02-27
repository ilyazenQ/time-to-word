<?php

interface TimeToWordConvertingInterface
{
    public function convert(int $hours, int $minutes): string;
}

class TimeToWordConverter implements TimeToWordConvertingInterface
{
    private array $hourWords = [
        1 => 'один',
        2 => 'два',
        3 => 'три',
        4 => 'четыре',
        5 => 'пять',
        6 => 'шесть',
        7 => 'семь',
        8 => 'восемь',
        9 => 'девять',
        10 => 'десять',
        11 => 'одиннадцать',
        12 => 'двенадцать'
    ];
    public function convert(int $hours, int $minutes): string
    {
        if(!$this->checkRangeTime($minutes,$hours)) {
            throw new \InvalidArgumentException('Invalid request Time(out of range)');
        }
        if ($minutes == 0) {
            return $this->responseWhenZeroMinutes($hours);
        } elseif ($minutes == 1) {
            return 'Одна минута после ' . $this->hoursToWords($hours).'.';
        } elseif ($minutes == 59) {
            return 'Одна минута до ' . $this->hoursToWords($hours+1).'.';
        } elseif ($minutes == 15) {
            return 'Четверть ' . $this->hoursToWords($hours+1).'.';
        } elseif ($minutes == 30) {
            return 'Половина ' . $this->hoursToWords($hours+1).'.';
        } elseif ($minutes == 45) {
            return 'Без пятнадцати минут ' . $this->hoursToWords($hours+1).'.';
        } elseif ($minutes < 30) {
            return $this->mb_strtoupper_first($this->minutesToWords($minutes)) . ' минут'. $this->getEndingToMinutes($minutes) .  ' после ' . $this->hoursToWords($hours).'.';
        } else {
            $minutesLeft = 60 - $minutes;
            return $this->mb_strtoupper_first($this->minutesToWords($minutesLeft)) . ' минут'. $this->getEndingToMinutes($minutesLeft). ' до ' .$this->hoursToWords($hours+1).'.';
        }
    }

    private function getEndingToMinutes(int $minutes) {
        if($minutes === 1) {
            return 'a';
        }

        if($minutes > 10 && $minutes < 20) {
            return '';
        }

        $needEndingArray = [
            '2','3','4'
        ];

        $minutes = explode(' ', strval($minutes));
        $lastMinutes = $minutes[count($minutes)-1];
        if(in_array($lastMinutes, $needEndingArray)) {
            return 'ы';
        } else {
            return '';
        }
    }
    private function hoursToWords(int $hours): string
    {
        if(!$this->checkHours($hours)) {
            throw new \InvalidArgumentException('Invalid response Time(out of range)');
        }
        return $this->hourWords[$hours];
    }
    private function checkMinutes(int $minutes): bool
    {
        if($minutes < 0 || $minutes > 60) {
            return false;
        }
        return true;
    }

    private function checkHours(int $hours): bool
    {
        if($hours < 0 || $hours > 12) {
            return false;
        }
        return true;
    }
    private function checkRangeTime(int $minutes,int $hours):bool
    {
        if(!$this->checkMinutes($minutes) || !$this->checkHours($hours)) {
            return false;
        }
        return true;
    }

    private function responseWhenZeroMinutes(int $hours): string {
        $post = ' часов';
        if($hours === 1) {
            $post = ' час';
        }
        return $this->mb_strtoupper_first($this->hourWords[$hours]) . $post;
    }

    private function minutesToWords(int $minutes): string
    {
        $units = [
            1 => 'одна',
            2 => 'две',
            3 => 'три',
            4 => 'четыре',
            5 => 'пять',
            6 => 'шесть',
            7 => 'семь',
            8 => 'восемь',
            9 => 'девять',
            10 => 'десять',
            11 => 'одиннадцать',
            12 => 'двенадцать',
            13 => 'тринадцать',
            14 => 'четырнадцать',
            15 => 'пятнадцать',
            16 => 'шестнадцать',
            17 => 'семнадцать',
            18 => 'восемнадцать',
            19 => 'девятнадцать',
            20 => 'двадцать',
            30 => 'тридцать',
            40 => 'сорок',
            50 => 'пятьдесят'
        ];

        if ($minutes <= 20)
        {
            return $units[$minutes];
        } elseif ($minutes < 30) {
            return $units[20] . ' ' . $units[$minutes - 20];
        } elseif ($minutes == 30) {
            return $units[30];
        } elseif ($minutes < 40) {
            return $units[30] . ' ' . $units[$minutes - 30];
        } elseif ($minutes == 40) {
            return $units[40];
        } elseif ($minutes < 50) {
            return $units[40] . ' ' . $units[$minutes - 40];
        } elseif ($minutes == 50) {
            return $units[50];
        } else {
            return $units[50] . ' ' . $units[$minutes - 50];
        }
    }
    function mb_strtoupper_first($str, $encoding = 'UTF8')
    {
        return
            mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
            mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
    }
}
$converter = new TimeToWordConverter();

echo $converter->convert(7, 0) . "<br>"; // Семь часов
echo $converter->convert(7, 1) . "<br>"; // Одна минута после семи
echo $converter->convert(7, 3) . "<br>"; // Три минуты после семи
echo $converter->convert(7, 12) . "<br>"; // Двенадцать минут после семи
echo $converter->convert(7, 15) . "<br>"; // Четверть восьмого
echo $converter->convert(7, 22) . "<br>"; // Двадцать две минуты после семи
echo $converter->convert(7, 30) . "<br>"; // Половина восьмого
echo $converter->convert(7, 35) . "<br>"; // Двадцать пять минут до восьми
echo $converter->convert(7, 41) . "<br>"; // Девятнадцать минут до восьми
echo $converter->convert(7, 56) . "<br>"; // Четыре минуты до восьми
echo $converter->convert(10, 59) . "<br>"; // Одна минута до восьми