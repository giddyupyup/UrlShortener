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
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="public/css/styles.css">
    </head>
    <body>
        <div class="container">
            <h1><span>URL Shortener</span></h1>
            <div class="shortener_container">
                <div class="form_container">
                    <form action="http://localhost/UrlShortener" method="post">
                        <div class="input-group">
                            <input type="text" name="url" id="url" class="form-control" placeholder="Enter URL...">
                            <span class="input-group-btn">
                                <input type="submit" value="Shorten" class="btn btn-secondary shorten">
                            </span>
                        </div>
                    </form>
                </div>
                <div>
                    <?php
                    
                    if()
                    
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
