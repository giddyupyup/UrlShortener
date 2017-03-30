<?php
require_once "include/config.php";
require_once "include/UrlShortener.php";

if (!empty($_GET["sc"])) {
    $code = $_GET["sc"];
    $db = pg_connect("host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=". DB_PASSWORD);
    
    $shortUrl = new UrlShortener($db);
    
    try{
        $url = $shortUrl->shortCodeToUrl($code);
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $url);
    }catch(\Exception $e){
        header("Location: error.html");
        exit;
    }    
}

?>