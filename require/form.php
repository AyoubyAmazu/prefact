<?php

    function formBtn($opts = array())
    {
        if(!isset($opts["key"])) $opts["key"] = ""; $opts["key"] = str_replace("'", "&apos;", $opts["key"]);
        if(!isset($opts["align"])) $opts["align"] = ""; if(!in_array($opts["align"], array("l", "r"))) $opts["align"] = "";
        if(!isset($opts["type"])) $opts["type"] = "solid"; if(!in_array($opts["type"], array("solid", "regular"))) $opts["type"] = "solid";
        if(!isset($opts["ico"])) $opts["ico"] = ""; $opts["ico"] = str_replace("'", "&apos;", $opts["ico"]);
        if(!isset($opts["txt"])) $opts["txt"] = ""; $opts["txt"] = str_replace("'", "&apos;", $opts["txt"]);
        if(!isset($opts["title"])) $opts["title"] = ""; if($opts["title"] == "") $opts["title"] = $opts["txt"]; $opts["title"] = str_replace("'", "&apos;", $opts["title"]);
        if(!isset($opts["href"])) $opts["href"] = ""; if($opts["href"] !== true) $opts["href"] = str_replace("'", "&apos;", $opts["href"]);
        if(!isset($opts["target"])) $opts["target"] = ""; if(!in_array($opts["target"], array("_self", "_blank"))) $opts["target"] = "";
        if(!isset($opts["readonly"])) $opts["readonly"] = false;
        if(!isset($opts["off"])) $opts["off"] = false;
        if(!isset($opts["extra"])) $opts["extra"] = array();
        if(!isset($opts["attr"])) $opts["attr"] = array();

        $div = array();
        array_push($div, "btn");
        if($opts["ico"] == "" || $opts["txt"] == "") array_push($div, "min");
        if($opts["align"] != "") array_push($div, $opts["align"]);
        if($opts["key"] != "") array_push($div, $opts["key"]);
        if(count($opts["extra"]) != 0) $div = array_merge($div, $opts["extra"]);
        if($opts["readonly"]) array_push($div, "readonly");
        if($opts["off"]) array_push($div, "off");

        $attr = array();
        array_push($attr, "class='" . implode(" ", $div) . "'");
        if(count($opts["attr"]) != 0) $attr = array_merge($attr, $opts["attr"]);

        $data = array();
        if($opts["title"] != "") array_push($data, "title='" . $opts["title"] . "'");
        if($opts["href"] != "") array_push($data, "href='" . (($opts["href"] === true)? "" : $opts["href"]) . "'");
        if($opts["target"] != "") array_push($data, "target='" . $opts["target"] . "'");

        $html = "<div " . implode(" ", $attr) . ">";
            $html .= "<a" . ((count($data) == 0)? "" : (" " . implode(" ", $data))) . ">";
                if($opts["ico"] != "") $html .= "<div class='ico'><i class='fa-" . $opts["type"] . " fa-" . $opts["ico"] . "'></i></div>";
                if($opts["txt"] != "") $html .= "<div class='txt'>" . $opts["txt"] . "</div>";
            $html .= "</a>";
        $html .= "</div>";

        return $html;
    }
    function formDisplay($opts = array())
    {
        if(!isset($opts["key"])) $opts["key"] = ""; $opts["key"] = str_replace("'", "&apos;", $opts["key"]);
        if(!isset($opts["align"])) $opts["align"] = ""; if(!in_array($opts["align"], array("c", "r"))) $opts["align"] = "";
        if(!isset($opts["label"])) $opts["label"] = ""; $opts["label"] = str_replace("'", "&apos;", $opts["label"]);
        if(!isset($opts["txt"])) $opts["txt"] = ""; $opts["txt"] = str_replace("'", "&apos;", $opts["txt"]);
        if(!isset($opts["title"])) $opts["title"] = ""; if($opts["title"] == "") $opts["title"] = implode(" : ", array_filter(array_unique(array($opts["label"], $opts["txt"])))); $opts["title"] = str_replace("'", "&apos;", $opts["title"]);
        if(!isset($opts["off"])) $opts["off"] = false;
        if(!isset($opts["extra"])) $opts["extra"] = array();
        if(!isset($opts["attr"])) $opts["attr"] = array();
        if(!isset($opts["op"])) $opts["op"] = array();

        $div = array();
        array_push($div, "display");
        if($opts["align"] != "") array_push($div, $opts["align"]);
        if($opts["key"] != "") array_push($div, $opts["key"]);
        if(count($opts["extra"]) != 0) $div = array_merge($div, $opts["extra"]);
        if($opts["off"]) array_push($div, "off");

        $attr = array();
        array_push($attr, "class='" . implode(" ", $div) . "'");
        if(count($opts["attr"]) != 0) $attr = array_merge($attr, $opts["attr"]);
        
        $label = array();
        array_push($label, "class='label'");
        if($opts["title"] != "") array_push($label, "title='" . $opts["title"] . "'");
                
        $data = array();
        array_push($data, "class='txt'");
        if($opts["title"] != "") array_push($data, "title='" . $opts["title"] . "'");

        $html = "<div " . implode(" ", $attr) . ">";
            if($opts["label"] != "") $html .= "<div " . implode(" ", $label) . ">" . $opts["label"] . "</div>";
            $html .= "<div class='data'>";
                foreach($opts["op"] as $v) if($v["type"] == "pre") $html .= $v["txt"];
                $html .= "<div " . implode(" ", $data) . ">" . $opts["txt"] . "</div>";
                foreach($opts["op"] as $v) if($v["type"] == "post") $html .= $v["txt"];
            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }
    function formInput($opts = array())
    {
        if(!isset($opts["key"])) $opts["key"] = ""; $opts["key"] = str_replace("'", "&apos;", $opts["key"]);
        if(!isset($opts["align"])) $opts["align"] = ""; if(!in_array($opts["align"], array("c", "r"))) $opts["align"] = "";
        if(!isset($opts["type"])) $opts["type"] = "text"; if(!in_array($opts["type"], array("text", "number", "password"))) $opts["type"] = "text";
        if(!isset($opts["label"])) $opts["label"] = ""; $opts["label"] = str_replace("'", "&apos;", $opts["label"]);
        if(!isset($opts["placeholder"])) $opts["placeholder"] = ""; $opts["placeholder"] = str_replace("'", "&apos;", $opts["placeholder"]);
        if(!isset($opts["title"])) $opts["title"] = ""; if($opts["title"] == "") $opts["title"] = implode(" : ", array_filter(array_unique(array($opts["label"], $opts["placeholder"])))); $opts["title"] = str_replace("'", "&apos;", $opts["title"]);
        if(!isset($opts["value"])) $opts["value"] = ""; $opts["value"] = str_replace("'", "&apos;", $opts["value"]);
        if(!isset($opts["step"])) $opts["step"] = ""; $opts["step"] = str_replace("'", "&apos;", $opts["step"]);
        if(!isset($opts["min"])) $opts["min"] = ""; $opts["min"] = str_replace("'", "&apos;", $opts["min"]);
        if(!isset($opts["max"])) $opts["max"] = ""; $opts["max"] = str_replace("'", "&apos;", $opts["max"]);
        if(!isset($opts["list"])) $opts["list"] = ""; $opts["list"] = str_replace("'", "&apos;", $opts["list"]);
        if(!isset($opts["required"])) $opts["required"] = false;
        if(!isset($opts["readonly"])) $opts["readonly"] = false;
        if(!isset($opts["off"])) $opts["off"] = false;
        if(!isset($opts["extra"])) $opts["extra"] = array();
        if(!isset($opts["attr"])) $opts["attr"] = array();
        if(!isset($opts["op"])) $opts["op"] = array();

        $div = array();
        array_push($div, "input");
        if($opts["align"] != "") array_push($div, $opts["align"]);
        if($opts["key"] != "") array_push($div, $opts["key"]);
        if(count($opts["extra"]) != 0) $div = array_merge($div, $opts["extra"]);
        if($opts["required"]) array_push($div, "required");
        if($opts["readonly"]) array_push($div, "readonly");
        if($opts["off"]) array_push($div, "off");

        $attr = array();
        array_push($attr, "class='" . implode(" ", $div) . "'");
        if(count($opts["attr"]) != 0) $attr = array_merge($attr, $opts["attr"]);
        
        $label = array();
        array_push($label, "class='label'");
        if($opts["title"] != "") array_push($label, "title='" . $opts["title"] . "'");

        $data = array();
        array_push($data, "type='" . $opts["type"] . "'");
        if($opts["step"] != "") array_push($data, "step='" . $opts["step"] . "'");
        if($opts["min"] != "") array_push($data, "min='" . $opts["min"] . "'");
        if($opts["max"] != "") array_push($data, "max='" . $opts["max"] . "'");
        if($opts["title"] != "") array_push($data, "title='" . $opts["title"] . "'");
        if($opts["placeholder"] != "") array_push($data, "placeholder='" . $opts["placeholder"] . "'");
        if($opts["value"] != "") array_push($data, "value='" . $opts["value"] . "'");
        if($opts["list"] != "") array_push($data, "list='" . $opts["list"] . "'");
        if($opts["readonly"]) array_push($data, "readonly");

        $html = "<div " . implode(" ", $attr) . ">";
            if($opts["label"] != "") $html .= "<div " . implode(" ", $label) . ">" . $opts["label"] . "</div>";
            $html .= "<div class='data'>";
                foreach($opts["op"] as $v) if($v["type"] == "pre") $html .= $v["txt"];
                $html .= "<input " . implode(" ", $data) . ">";
                foreach($opts["op"] as $v) if($v["type"] == "post") $html .= $v["txt"];
            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    function formTextarea($opts = array())
    {
        if(!isset($opts["key"])) $opts["key"] = ""; $opts["key"] = str_replace("'", "&apos;", $opts["key"]);
        if(!isset($opts["align"])) $opts["align"] = ""; if(!in_array($opts["align"], array("c", "r"))) $opts["align"] = "";
        if(!isset($opts["label"])) $opts["label"] = ""; $opts["label"] = str_replace("'", "&apos;", $opts["label"]);
        if(!isset($opts["placeholder"])) $opts["placeholder"] = ""; $opts["placeholder"] = str_replace("'", "&apos;", $opts["placeholder"]);
        if(!isset($opts["title"])) $opts["title"] = ""; if($opts["title"] == "") $opts["title"] = implode(" : ", array_filter(array_unique(array($opts["label"], $opts["placeholder"])))); $opts["title"] = str_replace("'", "&apos;", $opts["title"]);
        if(!isset($opts["value"])) $opts["value"] = ""; $opts["value"] = str_replace("'", "&apos;", $opts["value"]);
        if(!isset($opts["required"])) $opts["required"] = false;
        if(!isset($opts["readonly"])) $opts["readonly"] = false;
        if(!isset($opts["off"])) $opts["off"] = false;
        if(!isset($opts["extra"])) $opts["extra"] = array();
        if(!isset($opts["attr"])) $opts["attr"] = array();
        if(!isset($opts["op"])) $opts["op"] = array();

        $div = array();
        array_push($div, "textarea");
        if($opts["align"] != "") array_push($div, $opts["align"]);
        if($opts["key"] != "") array_push($div, $opts["key"]);
        if(count($opts["extra"]) != 0) $div = array_merge($div, $opts["extra"]);
        if($opts["required"]) array_push($div, "required");
        if($opts["readonly"]) array_push($div, "readonly");
        if($opts["off"]) array_push($div, "off");

        $attr = array();
        array_push($attr, "class='" . implode(" ", $div) . "'");
        if(count($opts["attr"]) != 0) $attr = array_merge($attr, $opts["attr"]);
        
        $label = array();
        array_push($label, "class='label'");
        if($opts["title"] != "") array_push($label, "title='" . $opts["title"] . "'");

        $data = array();
        if($opts["title"] != "") array_push($data, "title='" . $opts["title"] . "'");
        if($opts["placeholder"] != "") array_push($data, "placeholder='" . $opts["placeholder"] . "'");
        if($opts["readonly"]) array_push($data, "readonly");

        $html = "<div " . implode(" ", $attr) . ">";
            if($opts["label"] != "") $html .= "<div " . implode(" ", $label) . ">" . $opts["label"] . "</div>";
            $html .= "<div class='data'>";
                foreach($opts["op"] as $v) if($v["type"] == "pre") $html .= $v["txt"];
                $html .= "<textarea" . ((count($data) == 0)? "" : (" " . implode(" ", $data))) . ">" . $opts["value"] . "</textarea>";
                foreach($opts["op"] as $v) if($v["type"] == "post") $html .= $v["txt"];
            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    function formSelect($opts = array())
    {
        if(!isset($opts["key"])) $opts["key"] = ""; $opts["key"] = str_replace("'", "&apos;", $opts["key"]);
        if(!isset($opts["align"])) $opts["align"] = ""; if(!in_array($opts["align"], array("c", "r"))) $opts["align"] = "";
        if(!isset($opts["label"])) $opts["label"] = ""; $opts["label"] = str_replace("'", "&apos;", $opts["label"]);
        if(!isset($opts["title"])) $opts["title"] = ""; if($opts["title"] == "") $opts["title"] = $opts["label"]; $opts["title"] = str_replace("'", "&apos;", $opts["title"]);
        if(!isset($opts["selected"])) $opts["selected"] = array();
        if(!isset($opts["selected"]["code"])) $opts["selected"]["code"] = ""; $opts["selected"]["code"] = str_replace("'", "&apos;", $opts["selected"]["code"]);
        if(!isset($opts["selected"]["txt"])) $opts["selected"]["txt"] = ""; $opts["selected"]["txt"] = str_replace("'", "&apos;", $opts["selected"]["txt"]);
        if(!isset($opts["selected"]["placeholder"])) $opts["selected"]["placeholder"] = ""; $opts["selected"]["placeholder"] = str_replace("'", "&apos;", $opts["selected"]["placeholder"]);
        if(!isset($opts["selected"]["title"])) $opts["selected"]["title"] = ""; if($opts["selected"]["title"] == "") $opts["selected"]["title"] = implode(" : ", array_filter(array_unique(array($opts["title"], implode(" - ", array_filter(array_unique(array($opts["selected"]["txt"], $opts["selected"]["placeholder"])))))))); $opts["selected"]["title"] = str_replace("'", "&apos;", $opts["selected"]["title"]);
        if(!isset($opts["code"])) $opts["code"] = false;
        if(!isset($opts["filter"])) $opts["filter"] = true;
        if(!isset($opts["other"])) $opts["other"] = false;
        if(!isset($opts["required"])) $opts["required"] = false;
        if(!isset($opts["readonly"])) $opts["readonly"] = false;
        if(!isset($opts["off"])) $opts["off"] = false;
        if(!isset($opts["list"])) $opts["list"] = array();
        if(!isset($opts["extra"])) $opts["extra"] = array();
        if(!isset($opts["attr"])) $opts["attr"] = array();
        if(!isset($opts["op"])) $opts["op"] = array();

        $div = array();
        array_push($div, "select");
        if($opts["align"] != "") array_push($div, $opts["align"]);
        if($opts["key"] != "") array_push($div, $opts["key"]);
        if(count($opts["extra"]) != 0) $div = array_merge($div, $opts["extra"]);
        if($opts["required"]) array_push($div, "required");
        if($opts["readonly"]) array_push($div, "readonly");
        if($opts["off"]) array_push($div, "off");

        $attr = array();
        array_push($attr, "class='" . implode(" ", $div) . "'");
        array_push($attr, "code='" . $opts["selected"]["code"] . "'");
        if(count($opts["attr"]) != 0) $attr = array_merge($attr, $opts["attr"]);
        
        $label = array();
        array_push($label, "class='label'");
        if($opts["title"] != "") array_push($label, "title='" . $opts["title"] . "'");

        $data = array();
        if($opts["selected"]["title"] != "") array_push($data, "title='" . $opts["selected"]["title"] . "'");
        
        $dataTxt = array();
        array_push($dataTxt, "class='txt'");
        if($opts["selected"]["placeholder"] != "") array_push($dataTxt, "placeholder='" . $opts["selected"]["placeholder"] . "'");

        $html = "<div " . implode(" ", $attr) . ">";
            if($opts["label"] != "") $html .= "<div " . implode(" ", $label) . ">" . $opts["label"] . "</div>";
            $html .= "<div class='data'>";
                foreach($opts["op"] as $v) if($v["type"] == "pre") $html .= $v["txt"];
                $html .= "<a" . ((count($data) == 0)? "" : (" " . implode(" ", $data))) . ">";
                    $html .= "<div class='main'>";
                        $html .= "<div " . implode(" ", $dataTxt) . ">" . $opts["selected"]["txt"] . "</div>";
                        if($opts["code"]) $html .= "<div class='code'>" . $opts["selected"]["code"] . "</div>";
                    $html .= "</div>";
                    $html .= "<div class='ico'><i class='fa-solid fa-angle-down'></i></div>";
                $html .= "</a>";
                foreach($opts["op"] as $v) if($v["type"] == "post") $html .= $v["txt"];
                $html .= "<div class='list'>";
                    if($opts["filter"]) $html .= formInput(array("placeholder" => "Rechercher..."));
                    foreach($opts["list"] as $v)
                    {
                        if(!isset($v["code"])) $v["code"] = ""; $v["code"] = str_replace("'", "&apos;", $v["code"]);
                        if(!isset($v["txt"])) $v["txt"] = ""; $v["txt"] = str_replace("'", "&apos;", $v["txt"]);
                        if(!isset($v["placeholder"])) $v["placeholder"] = ""; $v["placeholder"] = str_replace("'", "&apos;", $v["placeholder"]);
                        if(!isset($v["title"])) $v["title"] = ""; if($v["title"] == "") $v["title"] = implode(" : ", array_filter(array_unique(array($opts["title"], implode(" - ", array_filter(array_unique(array($v["txt"], $v["placeholder"])))))))); $v["title"] = str_replace("'", "&apos;", $v["title"]);
                        if(!isset($v["href"])) $v["href"] = ""; if($v["href"] !== true) $v["href"] = str_replace("'", "&apos;", $v["href"]);
                        if(!isset($v["target"])) $v["target"] = ""; if(!in_array($v["target"], array("_self", "_blank"))) $v["target"] = "";
                        if(!isset($v["readonly"])) $v["readonly"] = false;
                        if(!isset($v["off"])) $v["off"] = false;
                        if(!isset($v["extra"])) $v["extra"] = array();
                        if(!isset($v["attr"])) $v["attr"] = array();

                        $option = array();
                        array_push($option, "option");
                        if(count($v["extra"]) != 0) $option = array_merge($option, $v["extra"]);
                        if($v["readonly"]) array_push($option, "readonly");
                        if($v["off"]) array_push($option, "off");
                        
                        $optionAttr = array();
                        array_push($optionAttr, "class='" . implode(" ", $option) . "'");
                        array_push($optionAttr, "code='" . $v["code"] . "'");
                        if(count($v["attr"]) != 0) $optionAttr = array_merge($optionAttr, $v["attr"]);
                        
                        $optionData = array();
                        if($v["title"] != "") array_push($optionData, "title='" . $v["title"] . "'");
                        if($v["href"] != "") array_push($optionData, "href='" . (($v["href"] === true)? "" : $v["href"]) . "'");
                        if($v["target"] != "") array_push($optionData, "target='" . $v["target"] . "'");
                        
                        $optionTxt = array();
                        array_push($optionTxt, "class='txt'");
                        if($v["placeholder"] != "") array_push($optionTxt, "placeholder='" . $v["placeholder"] . "'");

                        $html .= "<div " . implode(" ", $optionAttr) . ">";
                            $html .= "<a" . ((count($optionData) == 0)? "" : (" " . implode(" ", $optionData))) . ">";
                                $html .= "<div " . implode(" ", $optionTxt) . ">" . $v["txt"] . "</div>";
                                if($opts["code"]) $html .= "<div class='code'>" . $v["code"] . "</div>";
                            $html .= "</a>";
                        $html .= "</div>";
                    }
                    if($opts["other"]) $html .= "<div class='option' code='-1'><a title='" . implode(" : ", array_filter(array_unique(array($opts["title"], "Autres...")))) . "'><div class='txt'>Autres...</div></a></div>";
                $html .= "</div>";
            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }
    
    function formCheckbox($opts = array())
    {
        if(!isset($opts["key"])) $opts["key"] = ""; $opts["key"] = str_replace("'", "&apos;", $opts["key"]);
        if(!isset($opts["align"])) $opts["align"] = ""; if(!in_array($opts["align"], array("c", "r"))) $opts["align"] = "";
        if(!isset($opts["label"])) $opts["label"] = ""; $opts["label"] = str_replace("'", "&apos;", $opts["label"]);
        if(!isset($opts["title"])) $opts["title"] = ""; if($opts["title"] == "") $opts["title"] = $opts["label"]; $opts["title"] = str_replace("'", "&apos;", $opts["title"]);
        if(!isset($opts["selected"])) $opts["selected"] = array();
        if(!isset($opts["selected"]["code"])) $opts["selected"]["code"] = ""; $opts["selected"]["code"] = str_replace("'", "&apos;", $opts["selected"]["code"]);
        if(!isset($opts["selected"]["txt"])) $opts["selected"]["txt"] = ""; $opts["selected"]["txt"] = str_replace("'", "&apos;", $opts["selected"]["txt"]);
        if(!isset($opts["selected"]["placeholder"])) $opts["selected"]["placeholder"] = ""; $opts["selected"]["placeholder"] = str_replace("'", "&apos;", $opts["selected"]["placeholder"]);
        if(!isset($opts["selected"]["title"])) $opts["selected"]["title"] = ""; if($opts["selected"]["title"] == "") $opts["selected"]["title"] = implode(" : ", array_filter(array_unique(array($opts["title"], implode(" - ", array_filter(array_unique(array($opts["selected"]["txt"], $opts["selected"]["placeholder"])))))))); $opts["selected"]["title"] = str_replace("'", "&apos;", $opts["selected"]["title"]);
        if(!isset($opts["code"])) $opts["code"] = false;
        if(!isset($opts["filter"])) $opts["filter"] = true;
        if(!isset($opts["other"])) $opts["other"] = false;
        if(!isset($opts["required"])) $opts["required"] = false;
        if(!isset($opts["readonly"])) $opts["readonly"] = false;
        if(!isset($opts["off"])) $opts["off"] = false;
        if(!isset($opts["list"])) $opts["list"] = array();
        if(!isset($opts["extra"])) $opts["extra"] = array();
        if(!isset($opts["attr"])) $opts["attr"] = array();
        if(!isset($opts["op"])) $opts["op"] = array();

        $div = array();
        array_push($div, "checkbox");
        if($opts["align"] != "") array_push($div, $opts["align"]);
        if($opts["key"] != "") array_push($div, $opts["key"]);
        if(count($opts["extra"]) != 0) $div = array_merge($div, $opts["extra"]);
        if($opts["required"]) array_push($div, "required");
        if($opts["readonly"]) array_push($div, "readonly");
        if($opts["off"]) array_push($div, "off");

        $attr = array();
        array_push($attr, "class='" . implode(" ", $div) . "'");
        if(count($opts["attr"]) != 0) $attr = array_merge($attr, $opts["attr"]);
        
        $label = array();
        array_push($label, "class='label'");
        if($opts["title"] != "") array_push($label, "title='" . $opts["title"] . "'");

        $html = "<div " . implode(" ", $attr) . ">";
            if($opts["label"] != "") $html .= "<div " . implode(" ", $label) . ">" . $opts["label"] . "</div>";
            $html .= "<div class='data'>";
                foreach($opts["op"] as $v) if($v["type"] == "pre") $html .= $v["txt"];
                $html .= "<div class='list'>";
                    foreach($opts["list"] as $v)
                    {
                        if(!isset($v["code"])) $v["code"] = ""; $v["code"] = str_replace("'", "&apos;", $v["code"]);
                        if(!isset($v["txt"])) $v["txt"] = ""; $v["txt"] = str_replace("'", "&apos;", $v["txt"]);
                        if(!isset($v["title"])) $v["title"] = ""; if($v["title"] == "") $v["title"] = implode(" : ", array_filter(array_unique(array($opts["title"], $v["txt"])))); $v["title"] = str_replace("'", "&apos;", $v["title"]);
                        if(!isset($v["href"])) $v["href"] = ""; if($v["href"] !== true) $v["href"] = str_replace("'", "&apos;", $v["href"]);
                        if(!isset($v["target"])) $v["target"] = ""; if(!in_array($v["target"], array("_self", "_blank"))) $v["target"] = "";
                        if(!isset($v["value"])) $v["value"] = false;
                        if(!isset($v["readonly"])) $v["readonly"] = false;
                        if(!isset($v["off"])) $v["off"] = false;
                        if(!isset($v["extra"])) $v["extra"] = array();
                        if(!isset($v["attr"])) $v["attr"] = array();

                        $option = array();
                        array_push($option, "option");
                        if(count($v["extra"]) != 0) $option = array_merge($option, $v["extra"]);
                        if($v["value"]) array_push($option, "on");
                        if($v["readonly"]) array_push($option, "readonly");
                        if($v["off"]) array_push($option, "off");
                        
                        $optionAttr = array();
                        array_push($optionAttr, "class='" . implode(" ", $option) . "'");
                        array_push($optionAttr, "code='" . $v["code"] . "'");
                        if(count($v["attr"]) != 0) $optionAttr = array_merge($optionAttr, $v["attr"]);
                        
                        $optionData = array();
                        if($v["title"] != "") array_push($optionData, "title='" . $v["title"] . "'");
                        if($v["href"] != "") array_push($optionData, "href='" . (($v["href"] === true)? "" : $v["href"]) . "'");
                        if($v["target"] != "") array_push($optionData, "target='" . $v["target"] . "'");
                        
                        $html .= "<div " . implode(" ", $optionAttr) . ">";
                            $html .= "<a" . ((count($optionData) == 0)? "" : (" " . implode(" ", $optionData))) . ">";
                                $html .= "<div class='ico'><i class='fa-solid fa-circle" . (($v["value"])? "-dot" : "") . "'></i></div>";
                                $html .= "<div class='txt'>" . $v["txt"] . "</div>";
                            $html .= "</a>";
                        $html .= "</div>";
                    }
                $html .= "</div>";
                foreach($opts["op"] as $v) if($v["type"] == "post") $html .= $v["txt"];
            $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    function formLabel($opts = array()) {
        if(!isset($opts["href"])) $opts["href"] = ""; if($opts["href"] !== true) $opts["href"] = str_replace("'", "&apos;", $opts["href"]);
            
        if (!isset($opts["key"])) $opts["key"] = "";
        $opts["key"] = str_replace("'", "&apos;", $opts["key"]);
        
        if (!isset($opts["value"])) $opts["value"] = "";
        $opts["value"] = str_replace("'", "&apos;", $opts["value"]);
        
        if (!isset($opts["icon"])) $opts["icon"] = "";
        $opts["icon"] = str_replace("'", "&apos;", $opts["icon"]);
        
        if (!isset($opts["title"])) $opts["title"] = "";
        $opts["title"] = str_replace("'", "&apos;", $opts["title"]);
        
        if (!isset($opts["extra"])) $opts["extra"] = array();
        if (!isset($opts["attr"])) $opts["attr"] = array();
    
        $div = array();
        array_push($div, "label");
    
        if ($opts["key"] != "") array_push($div, $opts["key"]);
        if (count($opts["extra"]) != 0) $div = array_merge($div, $opts["extra"]);
    
        $attr = array();
        array_push($attr, "class='" . implode(" ", $div) . "'");
        if (count($opts["attr"]) != 0) $attr = array_merge($attr, $opts["attr"]);
    
        $data = array();
        if ($opts["title"] != "") array_push($data, "title='" . $opts["title"] . "'");
    
        $html = "<div " . implode(" ", $attr) . ">";
        if ($opts["icon"] != "") $html .= "<div class='label-icon'><i class='" . $opts["icon"] . "'></i></div>"; // Added icon
        if ($opts["key"] != "") $html .= "<div class='label-key'>" . $opts["key"] . "</div>";
        $html .= "<div class='label-value'>" . $opts["value"] . "</div>";
        $html .= "</div>";
    
        return $html;
    }