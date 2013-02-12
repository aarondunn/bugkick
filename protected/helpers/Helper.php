<?php
/**
 * User: Alex kavshirko@gmail.com
 * Date: 16.10.11
 * Time: 16:47
 */
 
class Helper {

    //Ordinary trimming
    public static function  truncateString($string, $limit = 20, $break= ' ', $pad = "&#133;")
    {
      // return with no change if string is shorter than $limit
      if(strlen($string) <= $limit) return $string;

      $string = substr($string, 0, $limit);
      if(false !== ($breakpoint = strrpos($string, $break))) {
        $string = substr($string, 0, $breakpoint);
      }

      return $string . $pad;
    }

    //Use for trimming and keep words unbroken
    public static function neatTrim($str, $n, $delim = '&#133;')
    {
       $len = strlen($str);
       if ($len > $n) {
			$str=rtrim( preg_replace('/\s+?(\S+)?$/', '', substr($str, 0, $n)) );
			$str=preg_replace('#</?[^>]*$#', '', $str);
			$str.=$delim;
			if(preg_match('#<(\w+)[^>/]*>(?!</\w+>.*?$)#', $str, $matches))
				$str.='</'.$matches[1].'>';
			return $str;
       }
       else {
           return $str;
       }
    }

    //format date and time to 12h format
    public static function formatDate12($date = null)
    {
        if($date)
            return date('Y-m-d g:ia', strtotime($date));
        else
            return date('Y-m-d g:ia');
    }

    //format date to format:  oct 12
    public static function formatDateShort($date)
    {
        return strtolower(date('M d', strtotime($date)));
    }

    //format date to format:  03th December 2011
    public static function formatDateLong($date = null)
    {
        if($date)
            return strtolower(date('dS F Y', strtotime($date)));
        else
            return strtolower(date('dS F Y'));
    }

    //format date to format:  2/25/12 - mon/day/year
    public static function formatDateSlash($date = null)
    {
        if($date)
            return strtolower(date('n/j/y', strtotime($date)));
        else
            return strtolower(date('n/j/y'));
    }

    //format date to format:  2/25/12 at 10:38pm - mon/day/year at time
    public static function formatDateSlashFull($date = null)
    {
        if($date)
            return strtolower(date('n/j/y \a\t g:ia', strtotime($date)));
        else
            return strtolower(date('n/j/y \a\t g:ia'));
    }

    //format date to format:  2nd April 2012 - 3:42pm
    public static function formatDateLongWithTime($date = null)
    {
        if($date)
            return date('jS F Y - g:ia', strtotime($date));
        else
            return date('jS F Y - g:ia');
    }

    /**
     * Formats size to readable view
     * @param integer $size in bytes
     * @param null $retString
     * @return string
     */
    public static function getReadableFileSize($size, $retString = null)
    {
            // adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
            $sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

            if ($retString === null) { $retString = '%01.2f %s'; }

            $lastSizeString = end($sizes);

            foreach ($sizes as $sizeString) {
                    if ($size < 1024) { break; }
                    if ($sizeString != $lastSizeString) { $size /= 1024; }
            }
            if ($sizeString == $sizes[0]) { $retString = '%01d %s'; } // Bytes aren't normally fractional
            return sprintf($retString, $size, $sizeString);
    }
}
