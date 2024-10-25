<?php

    function cookieInit()
    {
        $respData = json_decode(DATAresp, true);

        $obj = ((isset($_COOKIE[COOKIEname]))? json_decode(cryptDel($_COOKIE[COOKIEname]), true) : false);
        if($obj == false) $obj = array();

        if(!isset($obj["filter"]["annee"])) $obj["filter"]["annee"] = -1;
        if(!isset($obj["filter"]["soc"])) $obj["filter"]["soc"] = -1;
        if(!isset($obj["filter"]["grp"])) $obj["filter"]["grp"] = "";
        if(!isset($obj["filter"]["resp"])) $obj["filter"]["resp"] = -1;
        foreach($respData as $v) if(!isset($obj["filter"][$v["code"]])) $obj["filter"][$v["code"]] = "";
        if(!isset($obj["filter"]["naf"])) $obj["filter"]["naf"] = "";
        if(!isset($obj["filter"]["segment"])) $obj["filter"]["segment"] = "";
        if(!isset($obj["filter"]["txt"])) $obj["filter"]["txt"] = "";
        
        if(!isset($obj["index"])) $obj["index"] = array();
        if(!isset($obj["index"]["sortCol"])) $obj["index"]["sortCol"] = "dossierCode";
        if(!isset($obj["index"]["sortDir"])) $obj["index"]["sortDir"] = "ASC";
        if(!isset($obj["index"]["displayCol"])) $obj["index"]["displayCol"] = array("dossierCode", "dossierNom");

        if(!isset($obj["title"])) $obj["title"] = array();
        if(!isset($obj["title"]["desc"])) $obj["title"]["desc"] = false;
        if(!isset($obj["title"]["resp"])) $obj["title"]["resp"] = false;
        if(!isset($obj["title"]["mission"])) $obj["title"]["mission"] = false;

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