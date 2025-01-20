<?php

    define("APPurl", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/");
    define("APPself", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    define("APPlocal", $_SERVER["DOCUMENT_ROOT"].'/');
    define("APPdesc", "");
    define("APPwords", "");
    define("APPauthor", "FIDSUD");
    define("APPname", "Prefact");
    define("APPterms", "");
    define("APPprivacy", "");
    define("APPversion", date("YmdHis"));
    define("APPscript", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"]);

    define("AUTHout", "");
    
    define("COOKIEname", "FIDSUD_PREFACT");
    define("COOKIEexpire", 60 * 60 * 24 * 30);
    define("COOKIEpath", "/");
    define("COOKIEdomain", "");
    define("COOKIEsecure", false);
    define("COOKIEhttp", true);

    define("CRYPTkey", "ZuOiPk5L9PNIenhXmEWyy9T1IUoZrhVL");
    
    define("DATAresp", json_encode(array(
        "rd" => array("code" => "rd", "abr" => "RD", "txt" => "Responsable Déontologique", "all" => "Tous les responsables déontologiques", "num" => 1)
        , "re" => array("code" => "re", "abr" => "RE", "txt" => "Responsable Encadrement", "all" => "Tous les responsables encadrement", "num" => 2)
        , "rc" => array("code" => "rc", "abr" => "RC", "txt" => "Responsable Collaborateur", "all" => "Tous les responsables encadrement", "num" => 3)
        , "ra" => array("code" => "ra", "abr" => "RA", "txt" => "Responsable Auxiliaire", "all" => "Tous les responsables encadrement", "num" => 4)
        , "res" => array("code" => "res", "abr" => "RES", "txt" => "Responsable Encadrement Social", "all" => "Tous les responsables encadrement", "num" => 5)
        , "rs" => array("code" => "rs", "abr" => "RS", "txt" => "Responsable Social", "all" => "Tous les responsables encadrement", "num" => 6)
        , "rj" => array("code" => "rj", "abr" => "RJ", "txt" => "Responsable Juridique", "all" => "Tous les responsables encadrement", "num" => 7)
        , "rfp" => array("code" => "rfp", "abr" => "RFP", "txt" => "Responsable Fiscalité Personnel", "all" => "Tous les responsables encadrement", "num" => 8)
        , "tgr" => array("code" => "tgr", "abr" => "TGR", "txt" => "Responsable à Tanger", "all" => "Tous les responsables encadrement", "num" => 9)
        , "tgra" => array("code" => "tgra", "abr" => "TGRA", "txt" => "Assistant à Tanger", "all" => "Tous les responsables encadrement", "num" => 11)
    )));
    
    define("DATAsegment", json_encode(array(
        "a" => array("code" => "a", "abr" => "A", "txt" => "Rentable,pas d'impayés,acheteur,prescript", "color" => "green")
        , "b" => array("code" => "b", "abr" => "B", "txt" => "Rentable,pas d'impayés,à potontiel/prescript", "color" => "green")
        , "c" => array("code" => "c", "abr" => "C", "txt" => "Rentable sans difficultés de paiement", "color" => "green")
        , "d" => array("code" => "d", "abr" => "D", "txt" => "doit devenir rentable à 1 an,à régulqriser", "color" => "gold")
        , "e" => array("code" => "e", "abr" => "E", "txt" => "consommateur de ressurces,créances impayées", "color" => "red")
        , "z" => array("code" => "z", "abr" => "Z", "txt" => "non qualifiable", "color" => "dark")
    )));

    define("DB", json_encode(array(
        "prefact" => array("host" => "localhost", "port" => 3306, "name" => "prefact", "username" => "root", "password" => '1234')
        , "dia" => array("host" => "localhost", "port" => 3306, "name" => "expert_fidsud", "username" => "root", "password" => '1234')
        , "fact" => array("host" => "localhost", "port" => 3306, "name" => "z_fact", "username" => "root", "password" => '1234')
    )));
    
    define("DTmonth", json_encode(array(1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril", 5 => "Mai", 6 => "Juin", 7 => "Juillet", 8 => "Août", 9 => "Septembre", 10 => "Octobre", 11 => "Novembre", 12 => "Décembre")));
    
    define("ERRlog", APPlocal . "log/error/");

    define("PresDebours", array("ZDB","ZDBA","ZDBJ"));
    
    
    require_once(APPlocal . "require/auth.php");
    require_once(APPlocal . "require/cookie.php");
    require_once(APPlocal . "require/crypt.php");
    require_once(APPlocal . "require/database.php");
    require_once(APPlocal . "require/datetime.php");
    require_once(APPlocal . "require/error.php");
    require_once(APPlocal . "require/form.php");
    require_once(APPlocal . "require/html.php");
    require_once(APPlocal . "require/teams.php");
?>