<?php


class SDIData {
    var $nameInfo;
    var $teacher;
    var $matrkl;
    var $kursname;
    var $kurse;
    var $ani;

    function SDIData(){
        $this->nameInfo = "data/nameInfo.json";
        $this->matrkl = "data/matrkl.json";
        $this->ani = "data/ani.json";


        /*$this->teacher = "data/teacher.json";
        $this->kursname = "data/kursname.json";
        $this->kurse = "data/kurse.json";*/

    }


    function logCallSTart($callID, $ani, $timestamp) {
        $logObject = [
            "callID"        => $callID,
            "ani"           => $ani,
            "callStart"     => $timestamp,
            "callEnd"       => "",
            "callEndStatus" => "",
            "log"           => []
        ];

        $fileName = 'logs/'.$callID.'.json';
        $this->writeJson($fileName, $logObject);

        return $this->checkAni($ani);
    }

    function logMenuChoice($callID, $menu, $timestamp) {
        $fileName = 'logs/'.$callID.'.json';
        $input = $this->readJson($fileName);

        if($input != false) {
            $tmpObject = [
                "menu" => $menu,
                "time"    => $timestamp
            ];
            array_push($input["log"], $tmpObject);
            $this->writeJson($fileName, $input);
            return '{"response": "ok"}';
        }
        return '{"response": "error"}';
    }

    function logCallEnd($callID, $status, $timestamp) {
        $fileName = 'logs/'.$callID.'.json';
        $input = $this->readJson($fileName);

        if($input != false) {
            $input["callEnd"] = $timestamp;
            $input["callEndStatus"] = $status;
            $this->writeJson($fileName, $input);
            return '{"response": "ok"}';
        }
        return '{"response": "error"}';
    }

    function checkAni($ani) {
        $json1 = $this->readJson($this->ani);
        if( $json1 != false && isset($json1[$ani]) ){
            $matrk = $json1[$ani];

            $json2 = $this->readJson($this->matrkl);
            if( $json2 != false && isset($json2[$matrk]) ){
                return '{"response": "ok", "name": "' . $json2[$matrk]['vorname'] . " " . $json2[$matrk]['name'] . '"}';
            }
            return '{"response": "wrong", "name": ""}';
        }
        return '{"response": "unavailable", "name": ""}';
    }

    function checkMatrkl($callID, $matrklNr, $pin) {
        $json = $this->readJson($this->matrkl);
        if( $json != false && isset($json[$matrklNr]) ){
            if( strcmp($json[$matrklNr]['pin'], $pin) == 0 ){
                return '{"response": "ok", "name": "' . $json[$matrklNr]['vorname'] . " " . $json[$matrklNr]['name'] . '"}';
            }
            return '{"response": "wrong", "name": ""}';
        }
        return '{"response": "unavailable", "name": ""}';
    }

    function getKursInfo($callID, $kurs) {
        return "";
    }

    function getTelNr($name) {
        $json = $this->readJson($this->nameInfo);
        if( $json != false && isset($json[$name]) ){
            if( isset($json[$name]) ){
                return '{"response": "ok", "tel": "' . $json[$name]['tel'] . '"}';
            }
            return '{"response": "no tel", "tel": ""}';
        }
        return '{"response": "unavailable", "tel": ""}';
    }

    function getZeiten($name) {
        $json = $this->readJson($this->nameInfo);
        if( $json != false && isset($json[$name]) ){
            if( isset($json[$name]) ){
                return '{"response": "ok", "time": "' . $json[$name]['time'] . '"}';
            }
            return '{"response": "no time", "time": ""}';
        }
        return '{"response": "unavailable", "time": ""}';
    }

    function getEssen($name, $tag) {
        $json = $this->readJson($this->nameInfo);
        if( $json != false && isset($json[$name]) ){
            //return '{"response": "ok", "room": "' . $json[$name]['room'] . '"}';
            if( isset($json[$name]['menu']) ){
                if( isset($json[$name]['menu'][$tag]) ){
                    return '{"response": "ok", "menu": "' . $json[$name]['menu'][$tag] . '"}';
                }
                return '{"response": "no food that day", "menu": ""}';
            }
            return '{"response": "no menu", "menu": ""}';
        }
        return '{"response": "unavailable", "menu": ""}';
    }

    function getRaum($name) {
        $json = $this->readJson($this->nameInfo);
        if( $json != false && isset($json[$name]) ){
            if( isset($json[$name]['room']) ){
                return '{"response": "ok", "room": "' . $json[$name]['room'] . '"}';
            }
            return '{"response": "no room", "room": ""}';
        }
        return '{"response": "unavailable", "room": ""}';
    }

    /*
    * Helper functions
    */

    function readJson($path) {
        $input = file_get_contents($path);

        if($input == false) { return false; }
        return json_decode($input, true);
    }

    function writeJson($path, $data) {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($path, $jsonData);
    }
}