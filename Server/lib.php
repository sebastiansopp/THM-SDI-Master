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
        $this->kursinfo = "data/kursinfo.json";
        $this->kursname = "data/kursnamen.json";
        $this->teacher = "data/teacher.json";
    }


    function logCallSTart($callID, $ani, $timestamp) {
        $json = $this->readJson($this->ani);
        $matrk = "undefined";
        if( $json != false && isset($json[$ani]) ){
            $matrk = $json[$ani];
        }

        $logObject = [
            "callID"        => $callID,
            "ani"           => $ani,
            "matrkl"        => $matrk,
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
                $fileName = 'logs/'.$callID.'.json';
                $input = $this->readJson($fileName);
                if($input != false) {
                    $input["matrkl"] = $matrklNr;
                    $this->writeJson($fileName, $input);
                }

                return '{"response": "ok", "name": "' . $json[$matrklNr]['vorname'] . " " . $json[$matrklNr]['name'] . '"}';
            }
            return '{"response": "wrong", "name": ""}';
        }
        return '{"response": "unavailable", "name": ""}';
    }

    function getKursInfo($callID, $kurs, $alg) {
        if(strcmp($alg, "true") == 0) {
            $json2 = $this->readJson($this->kursinfo);
            $json3 = $this->readJson($this->kursname);
            if( $json2 != false && isset($json2[$kurs]) ){
                return '{"response": "ok", "name": "' . $json3[strtoupper($kurs)] . '", "info": "' . $json2[$kurs] . '"}';
            }
            if(array_key_exists(strtoupper($kurs), $json3)) {
                return '{"response": "ok", "name": "' . $json3[strtoupper($kurs)] . '", "info": "Es gibt keine aktuellen Informationen zu ' . $json3[strtoupper($kurs)] . '."}';
            }
            else {
                return '{"response": "ok", "name": "", "info": "Der Kurs existiert nicht."}';
            }
        }
        else {
            $fileName = 'logs/'.$callID.'.json';
            $input = $this->readJson($fileName);
			$json3 = $this->readJson($this->kursname);
			
            if($input != false) {
                $matrklNr = $input["matrkl"];
                if(strlen($matrklNr) == 6){
                    $json = $this->readJson($this->matrkl);
                    if( $json != false && isset($json[$matrklNr]) ){
                        if (in_array($kurs, $json[$matrklNr]["kurse"])){
                            $punkte = rand(0, 100);
                            if($punkte < 50) {
                                return '{"response": "ok", "name": "' . $json3[strtoupper($kurs)] . '", "info": "Sie haben nicht bestanden mit ' . $punkte . ' Punkten."}';
                            }
                            else {
                                return '{"response": "ok", "name": "' . $json3[strtoupper($kurs)] . '", "info": "Sie haben bestanden mit ' . $punkte . ' Punkten."}';
                            }
                        }
                        return '{"response": "kurs nicht angemeldet", "name": "", "info": "kurs nicht angemeldet"}';
                    }
                }
                return '{"response": "matrkl unavailable", "name": "", "info": ""}';
            }
        }
        return '{"response": "unavailable", "name": "", "info": ""}';
    }

    function getTelNr($name) {
        $json = $this->readJson($this->nameInfo);
        if( $json != false && isset($json[$name]) ){
            if( isset($json[$name]) ){
                $json2 = $this->readJson($this->teacher);
                return '{"response": "ok", "tel": "' . $json[$name]['tel'] . '", "name": "' . $json2[ucfirst($name)] . '"}';
            }
            return '{"response": "no tel", "tel": "", "name": ""}';
        }
        return '{"response": "unavailable", "tel": "", "name": ""}';
    }

    function getZeiten($name) {
        $json = $this->readJson($this->nameInfo);
        if( $json != false && isset($json[$name]) ){
            if( isset($json[$name]) ){
                $json2 = $this->readJson($this->teacher);
                return '{"response": "ok", "time": "' . $json[$name]['time'] . '", "name": "' . $json2[ucfirst($name)] . '"}';
            }
            return '{"response": "no time", "time": "", "name": ""}';
        }
        return '{"response": "unavailable", "time": "", "name": ""}';
    }

    function getEssen($name, $tag) {
        $json = $this->readJson($this->nameInfo);
        if( $json != false && isset($json[$name]) ){
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
                $json2 = $this->readJson($this->teacher);
                return '{"response": "ok", "room": "' . $json[$name]['room'] . '", "name": "' . $json2[ucfirst($name)] . '"}';
            }
            return '{"response": "no room", "room": "", "name": ""}';
        }
        return '{"response": "unavailable", "room": "", "name": ""}';
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