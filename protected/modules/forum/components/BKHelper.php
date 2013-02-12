<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 30.09.12
 * Time: 16:29
 */

class BKHelper
{
    //Usual trimming
    public static function truncateString($string, $limit = 50, $break = ' ', $pad = "...")
    {
        // return with no change if string is shorter than $limit
        if (strlen($string) <= $limit) return $string;

        $string = substr($string, 0, $limit);
        if (false !== ($breakpoint = strrpos($string, $break))) {
            $string = substr($string, 0, $breakpoint);
        }

        return $string . $pad;
    }

    //Use for trimming text with keeping words unbroken
    public static function neatTrim($str, $n, $delim = '...')
    {
        $len = strlen($str);
        if ($len > $n) {
            $str = rtrim(preg_replace('/\s+?(\S+)?$/', '', substr($str, 0, $n)));
            $str = preg_replace('#</?[^>]*$#', '', $str);
            $str .= $delim;
            if (preg_match('#<(\w+)[^>/]*>(?!</\w+>.*?$)#', $str, $matches))
                $str .= '</' . $matches[1] . '>';
            return $str;
        } else {
            return $str;
        }
    }

    //format date to format:  2nd April 2012 - 3:42pm
    public static function formatDateLongWithTime($date = null)
    {
        if ($date)
            return date('jS F Y - g:ia', strtotime($date));
        else
            return date('jS F Y - g:ia');
    }

}

