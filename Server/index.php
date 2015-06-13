<?php


header('Content-Type: application/json; charset=utf-8');
require_once('lib.php');

//$requMethod = $_SERVER['REQUEST_METHOD'];

if(isset($_POST["action"])) { $action = strtolower($_POST["action"]); }
else { $action = "";  }

/*switch ($requMethod) {
    case "GET":
        error_log("GET Method", 0);
        break;
    case "HEAD":
        error_log("HEAD Method", 0);
        break;
    case "POST":
        error_log("POST Method", 0);
        break;
    case "PUT":
        error_log("PUT Method", 0);
        break;
    case 'DELETE':
    	error_log("DELETE Method", 0); 
    	break;
  	case 'OPTIONS':
    	error_log("OPTIONS Method", 0);    
    	break;
  	default:
    	error_log("Undifined Method", 0);
    	break;
}*/

$sdidata = new SDIData();

switch ($action) {
    case "loggcallstart":
        $callID = $_POST["callID"];
        $ani = $_POST["ani"];
        $timestamp = $_POST["timestamp"];
        echo $sdidata->logCallSTart($callID, $ani, $timestamp);
        break;
  	case "logmenuchoice":
        $callID = $_POST["callID"];
        $menu = $_POST["menu"];
        $timestamp = $_POST["timestamp"];
        echo $sdidata->logMenuChoice($callID, $menu, $timestamp);
        break;
  	case "logcallend":
        $callID = $_POST["callID"];
        $status = $_POST["status"];
        $timestamp = $_POST["timestamp"];
        echo $sdidata->logCallEnd($callID, $status, $timestamp);
        break;
  	case "checkani":
        $ani = $_POST["ani"];
        echo $sdidata->checkAni($ani);
        break;
  	case "checkmatrkl":
        $callID = $_POST["callID"];
        $matrklNr = $_POST["matrklNr"];
        $pin = $_POST["pin"];
        echo $sdidata->checkMatrkl($callID, $matrklNr, $pin);
        break;
  	case "getkursinfo":
        $callID = $_POST["callID"];
        $kurs = $_POST["kurs"];
        echo $sdidata->getKursInfo($callID, $kurs);
        break;
  	case "gettelnr":
        $name = $_POST["name"];
        echo $sdidata->getTelNr($name);
        break;
  	case "getzeiten":
        $name = $_POST["name"];
        echo $sdidata->getZeiten($name);
        break;
  	case "getessen":
        $name = $_POST["name"];
        if(isset($_POST["tag"])) { $tag = strtolower($_POST["tag"]); }
        else { $tag = strtolower(date("D")); }
        echo $sdidata->getEssen($name, $tag);
        break;
  	case "getraum":
        $name = $_POST["name"];
        echo $sdidata->getRaum($name);
        break;
  	default:
    	error_log("Undifined Method", 0);
    	break;
}

/*error_log("New Session", 0);
error_log($action, 0);
error_log($_POST["callID"], 0);
error_log($_POST["timestamp"], 0);*/



/*echo date("D", strtotime("2011-01-01"));
echo date("D", strtotime("2011-01-02"));
echo date("D", strtotime("2011-01-03"));
echo date("D", strtotime("2011-01-04"));
echo date("D", strtotime("2011-01-05"));
echo date("D", strtotime("2011-01-06"));
echo date("D", strtotime("2011-01-07"));*/





//echo $sdidata->getTelNr('mensagiesen');
//echo $sdidata->checkMatrkl("", "907284", "14235");
//echo $sdidata->getEssen("eu", "fri");
//$sdidata->logCallSTart("1234", "0666", "100");
//$sdidata->logMenuChoice("1234", "menu1", "130");
//$sdidata->logMenuChoice("1234", "menu2", "160");
//$sdidata->logCallEnd("1234", "ok", "200");



?>