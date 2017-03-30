<?php
require_once "include/config.php";
require_once "include/UrlShortener.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Url Shortener</title>
        <link rel="icon" type="image/png" href="public/icon.png">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.6.0/clipboard.min.js"></script>
        <link rel="stylesheet" href="public/css/styles.css">
        <script src="public/scripts/script.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="container">
            <h1><span>URL Shortener</span></h1>
            <div class="shortener_container">
                <div class="form_container">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="input-group">
                            <input type="text" name="url" id="url" class="form-control" placeholder="Enter URL...">
                            <span class="input-group-btn">
                                <input type="submit" value="Shorten" class="btn btn-secondary shorten">
                            </span>
                        </div>
                    </form>
                </div>
                
                <?php
                    
                if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["url"])) {
                    $db = pg_connect("host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=". DB_PASSWORD);
                    
                    $shortUrl = new UrlShortener($db);
                    
                    try{
                        $code = $shortUrl->urlToShortCode($_POST["url"]);
                    }catch(\Exception $e){
                        header("Location: error.html");
                        exit;                        
                    }
                    
                    $loc = (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) ? "https://" : "http://";
                    
                    $loc .= SHORTURL_PREFIX;
                    
                    $url = $loc . $code;
                    
                    $text = "";
                    $text .= "<div id='background' style='position:fixed;top:0;bottom:0;left:0;right:0;background: #000;z-index: 1000;opacity: 0.5'></div>";
                    $text .= "<div id='show_shortcode'>";
                    $text .= "<div style='padding: 20px;'>";
                    $text .= "<div style='font-weight: bold;font-size: 16px;padding-bottom: 20px;'>";
                    $text .= "Short URL:";
                    $text .= "</div>";
                    $text .= "<div style='padding-bottom: 60px;'>";
                    $text .= "<a href='" . $url . "' target='_blank' id='shortcode'>" . $url . "</a>";
                    $text .= "</div>";
                    $text .= "<div class='ebuttons'>";
                    $text .= "<span style='position: absolute; display: inline-block; padding: 5px;top: -35px;' class='btn btn-info' data-clipboard-target='#shortcode' data-clipboard-action='copy' id='copytoclip'>";
                    $text .= "Copy to clipboard";
                    $text .= "</span>";
                    $text .= "<span id='done_btn' style='position: absolute;right: 0px;display: inline-block;padding: 5px;top: -35px;' class='btn btn-info'>";
                    $text .= "Done";
                    $text .= "</span>";
                    $text .= "</div>";
                    $text .= "</div>";
                    $text .= "</div>";
                    
                    echo $text;
                    
                }    

                ?>
                
                
            </div>
        </div>
    </body>
</html>
