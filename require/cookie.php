<?php

    function cookieInit()
    {
        $obj = ((isset($_COOKIE[COOKIEname]))? json_decode(cryptDel($_COOKIE[COOKIEname]), true) : false);
        if($obj == false) $obj = array();

        if(!isset($obj["filter"]["annee"])) $obj["filter"]["annee"] = -1;
        if(!isset($obj["filter"]["soc"])) $obj["filter"]["soc"] = -1;
        if(!isset($obj["filter"]["grp"])) $obj["filter"]["grp"] = "";

        return $obj;
    }

    function cookieDel()
    {
        setcookie(COOKIEname, "", time() - 3600, COOKIEpath, COOKIEdomain, COOKIEsecure, COOKIEhttp);
    }
    
    function cookieSave($data)
    {
        setcookie(COOKIEname, cryptSave(json_encode($data)), time() + COOKIEexpire, COOKIEpath, COOKIEdomain, COOKIEsecure, COOKIEhttp);
    }