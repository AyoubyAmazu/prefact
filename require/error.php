<?php

    function err($opts = array())
    {
        if(!isset($opts["ajax"])) $opts["ajax"] = false;
        if(!isset($opts["txt"])) $opts["txt"] = ""; if($opts["txt"] == "") "Une erreur est survenue";
        if(!isset($opts["btn"])) $opts["btn"] = "";
        if(!isset($opts["det"])) $opts["det"] = "";
        if(!isset($opts["user"])) $opts["user"] = array();
        if(!isset($opts["user"]["id"])) $opts["user"]["id"] = "";

        $det = array();
        array_push($det, $opts["user"]["id"]);
        array_push($det, $opts["txt"]);
        array_push($det, $_SERVER["PHP_SELF"]);
        array_push($det, $opts["det"]);
        $det = implode(" | ", array_filter($det));
        errLog($det);

        if($opts["ajax"]) die(json_encode(array("code" => 400, "txt" => $opts["txt"], "btn" => $opts["btn"], "det" => str_replace(" | ", "<br>", $opts["det"]))));
        
        $cont = "<div class='txt'>" . $opts["txt"] . "</div>";
        if($opts["btn"] !== false) $cont .= "<div class='op'>" . formBtn(array("key" => "cancel", "txt" => (($opts["btn"] == "" || $opts["btn"] === true)? "Ré-essayer" : "Annuler"), "href" => (($opts["btn"] == "")? true : $opts["btn"]))) . "</div>";
        if($opts["det"] != "") $cont .= "<div class='det off'>" . formBtn(array("key" => "toggle", "ico" => "angle-down", "title" => "Afficher plus d'information")) . "<div class='txt'>" . str_replace(" | ", "<br>", $opts["det"]) . "</div></div>";

        $html = html(array_merge($opts, array("cont" => $cont, "script" => "error", "adr" => false)));
        die($html);
    }

    function errLog($txt)
    {
        $path = ERRlog . date("Y") . "/" . date("Ym") . "/"; if(!file_exists($path)) @mkdir($path, 0777, true);
        $file = @fopen($path . date("Ymd") . ".txt", "a+"); if($file == false) die(APPname . "<hr>" . str_replace(" | ", "<hr>", $txt));
        fwrite($file, dt(date("YmdHis")) . " | " . $txt . "\n");
        fclose($file);
    }

?>