<?php

    function dt($txt) // YYYYMMDDHHIISS => DD/MM/YYYY HH:II:SS
    {
        return ((strlen($txt) == 14)? implode(" ", array_filter(array(dtDate(substr($txt, 0, 8)), dtTime(substr($txt, 8, 6))))) : "");
    }
    
    function dtFull($txt) // YYYYMMDDHHIISS => D MMMMM YYYY HH:II:SS
    {
        return ((strlen($txt) == 14)? implode(" ", array_filter(array(dtDateFull(substr($txt, 0, 8)), dtTime(substr($txt, 8, 6))))) : "");
    }

    function dtDate($txt) // YYYYMMDD => DD/MM/YYYY
    {
        $txt = str_replace("_", "", $txt);
        if(strlen($txt) == 8)
        {
            $y = substr($txt, 0, 4); if($y == "") return ""; $y = intval($y); if($y < 1) return "";
            $m = substr($txt, 4, 2); if($m == "") return ""; $m = intval($m); if($m < 1 || $m > 12) return "";
            $d = substr($txt, 6, 2); if($d == "") return ""; $d = intval($d); if($d < 1 || $d > 31) return "";
            if(!checkdate($m, $d, $y)) return "";
            return str_pad($d, 2, "0", STR_PAD_LEFT) . "/" . str_pad($m, 2, "0", STR_PAD_LEFT) . "/" . str_pad($y, 4, "0", STR_PAD_LEFT);
        }
        if(strlen($txt) == 6)
        {
            $y = substr($txt, 0, 4); if($y == "") return ""; $y = intval($y); if($y < 1) return "";
            $m = substr($txt, 4, 2); if($m == "") return ""; $m = intval($m); if($m < 1 || $m > 12) return "";
            return str_pad($m, 2, "0", STR_PAD_LEFT) . "/" . str_pad($y, 4, "0", STR_PAD_LEFT);
        }
        if(strlen($txt) == 4)
        {
            $y = substr($txt, 0, 4); if($y == "") return ""; $y = intval($y); if($y < 1) return "";
            return str_pad($y, 4, "0", STR_PAD_LEFT);
        }
        return "";
    }
    
    function dtDateFull($txt) // YYYYMMDD => D MMMMM YYYY
    {
        $dtMonth = json_decode(DTmonth, true);
        $txt = str_replace("_", "", $txt);
        if(strlen($txt) == 8)
        {
            $y = substr($txt, 0, 4); if($y == "") return ""; $y = intval($y); if($y < 1) return "";
            $m = substr($txt, 4, 2); if($m == "") return ""; $m = intval($m); if($m < 1 || $m > 12) return "";
            $d = substr($txt, 6, 2); if($d == "") return ""; $d = intval($d); if($d < 1 || $d > 31) return "";
            if(!checkdate($m, $d, $y)) return "";
            return $d . " " . strtolower($dtMonth[$m]) . " " . str_pad($y, 4, "0", STR_PAD_LEFT);
        }
        if(strlen($txt) == 6)
        {
            $y = substr($txt, 0, 4); if($y == "") return ""; $y = intval($y); if($y < 1) return "";
            $m = substr($txt, 4, 2); if($m == "") return ""; $m = intval($m); if($m < 1 || $m > 12) return "";
            return $dtMonth[$m] . " " . str_pad($y, 4, "0", STR_PAD_LEFT);
        }
        if(strlen($txt) == 4)
        {
            $y = substr($txt, 0, 4); if($y == "") return ""; $y = intval($y); if($y < 1) return "";
            return str_pad($y, 4, "0", STR_PAD_LEFT);
        }
        return "";
    }
    
    function dtTime($txt) // HHIISS => HH:II:SS
    {
        $txt = str_replace("_", "", $txt);
        if(strlen($txt) == 6)
        {
            $h = substr($txt, 0, 2); if($h == "") return ""; $h = intval($h); if($h < 0 || $h > 23) return "";
            $i = substr($txt, 2, 2); if($i == "") return ""; $i = intval($i); if($i < 0 || $i > 59) return "";
            $s = substr($txt, 4, 2); if($s == "") return ""; $s = intval($s); if($s < 0 || $s > 59) return "";
            return str_pad($h, 2, "0", STR_PAD_LEFT) . ":" . str_pad($i, 2, "0", STR_PAD_LEFT) . ":" . str_pad($s, 2, "0", STR_PAD_LEFT);
        }
        if(strlen($txt) == 4)
        {
            $h = substr($txt, 0, 2); if($h == "") return ""; $h = intval($h); if($h < 0 || $h > 23) return "";
            $i = substr($txt, 2, 2); if($i == "") return ""; $i = intval($i); if($i < 0 || $i > 59) return "";
            return str_pad($h, 2, "0", STR_PAD_LEFT) . ":" . str_pad($i, 2, "0", STR_PAD_LEFT);
        }
        if(strlen($txt) == 2)
        {
            $h = substr($txt, 0, 2); if($h == "") return ""; $h = intval($h); if($h < 0 || $h > 23) return "";
            return str_pad($h, 2, "0", STR_PAD_LEFT) . ":00";
        }
        return "";
    }

?>