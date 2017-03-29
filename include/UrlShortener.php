<?php

/*
 * This class provides all the functionality needed to create (encode) and 
 * decode shortened URLs
 
 * @author Gideon Arces Jr - gid.arces07@gmail.com
*/

define("MAX_CODE_LENGTH", 6);

class UrlShortener {
    
    private static $chars = "123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private static $table = "short_urls";
    private static $checkUrlExists = true;
    private $conn;
    private $timestamp;
    
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    
    /**
     * Create a short code from the long URL provided.
     *
     * @param string $url: the long URL to be shortened
     * @return string short code on success
     * @throws Exception if there's an error
     */
    public function urlToShortCode($url) {
        if (empty($url)) {
            throw new \Exception("No URL was supplied.");
        }
        
        if ($this->validateUrlFormat($url) == false) {
            throw new \Exception("URL does not have a valid format.");
        }
        
        if (self::$checkUrlExists) {
            if (!$this->verifyUrlExists($url)) {
                throw new \Exception("URL does not appear to exist.");
            }
        }
        
        $shortCode = $this->urlExistInDb($url);
        if ($shortCode == false) {
            $shortCode = $this->createShortCode($url);
        }
        
        return $shortCode;
    }
    
    /**
     * Retrieves the long URL from a short code
     * 
     * @param string $code: short code for the long URL
     * @return string for the long URL
     * @throws Exception if there's an error
     */
    public function shortCodeToUrl($code) {
        if (empty($code)) {
            throw new \Exception("No short code was supplied.");
        }
        
        if ($this->validateShortCode($code) == false) {
            throw new \Exception("Short code does not have a valid format.");
        }
        
        $longUrl = $this->getUrlFromDb($code);
        
        if ($longUrl == false) {
            throw new \Exception("Short code does not appear to exist.");
        }
        
        return $longUrl;
    }
    
    //Method to validate the URL Format
    private function validateUrlFormat($url) {
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }
    
    //Method to check if the URL exists on the Web
    private function verifyUrlExists($url) {
        $cUrl = curl_init();
        curl_setopt($cUrl, CURLOPT_URL, $url);
        curl_setopt($cUrl, CURLOPT_NOBODY, 1);
        curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($cUrl);
        
        $resp = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);
        curl_close($cUrl);
        
        return (!empty($resp) && $response != 404);
    }
    
    //Method to check if the URL exist in DB
    private function urlExistInDb($url) {
        $query = "SELECT short_code FROM " . self::$table .
            " WHERE long_url = '" . $url . "' LIMIT 1";
        
        $result = pg_query($this->conn, $query);
        
        $getCode = pg_fetch_object($result);
        
        return (empty($getCode)) ? false : $getCode->short_code;
    }
    
    /*
     * Create the short code of the URL
     * 
     * This methos does the actual action in which the URL is being inserted in DB.
     * Also, the short code is being created.
     * 
     * @param string $url the long URL to create a short code
     * @return $shortCode the short code for the long URL
     */
    private function createShortCode($url) {
        $id = $this->insertUrlInDb($url);
        $shortCode = $this->createTheShortCode();
        $this->insertShortCodeInDb($id,$shortCode);
        return $shortCode;
    }
    
    //Method to insert the long URL on DB
    private function insertUrlInDb($url) {
        $query = "INSERT INTO " . self::$table . 
            " (long_url) VALUES ('" . $url . "')";
        
        pg_query($this->conn, $query);
        
        $query = "SELECT id FROM " . self::$table . 
            " WHERE long_url='" . $url . 
            "' LIMIT 1";
        
        $result = pg_query($this->conn, $query);
        
        $id = pg_fetch_object($result);
        
        return $id->id;
    }
    
    //Method to create the short code
    private function createTheShortCode() {
        $length = strlen(self::$chars);
        
        $code = "";
        for ( $i = 0; $i < MAX_CODE_LENGTH; $i++) {
            $code .= self::$chars[rand(0,$length-1)];
        }
        
        return $code;
    }
    
    //Method to update the DB for the short code
    private function insertShortCodeInDb($id, $code){
        if (empty($id) || empty($code)) {
            throw new \Exception("Input parameter(s) invalid.");
        }
        
        $query = "UPDATE " . self::$table . 
            " SET short_code = '" . $code . 
            "' WHERE id = ". $id;
        
        pg_query($this->conn, $query);
        
        $query = "SELECT short_code FROM " . self::$table . 
            " WHERE id=" . $id . 
            " LIMIT 1";
        
        $result = pg_query($this->conn, $query);
        
        $shortCode = pg_fetch_object($result);
        
        if (empty($shortCode->short_code)) {
            throw new \Exception("Row was not updated with short code.");
        }
        
        return true;
    }
    
    //Method to validate the short code.
    private function validateShortCode($code) {
        return preg_match("|[" . self::$chars . "]+|", $code);
    }
    
    //Method to retrieve the long url from DB using the short code.
    private function getUrlFromDb($code) {
        $query = "SELECT id, long_url FROM " . self::$table .
            " WHERE short_code = '" . $code . "' LIMIT 1";
        
        $result  = pg_query($this->conn, $query);
        
        $longUrl = pg_fetch_object($result);
        
        return (empty($longUrl)) ? false : $longUrl->long_url;
    }
    
    
}

?>