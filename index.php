<?php
require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$apiKey = getenv('API_KEY');

$weather = '';
$error = '';
if (isset($_GET['city'])) {
    $urlContents = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q=' . urlencode($_GET["city"]). '&appid='.$apiKey );

    $weatherArray = json_decode($urlContents, true);
//    print_r($weatherArray);
    $file_headers = @get_headers('http://api.openweathermap.org/data/2.5/weather?q=' . urlencode($_GET["city"]) . '&appid='.$apiKey);
    if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
        $error .= "That city could not be found.";
    } else if (isset($weatherArray["cod"]) == 200) {
        if ($weatherArray["cod"] == 200) {
            $weather = "The weather in " . $_GET['city'] . " is currently " . $weatherArray['weather'][0]['description'];
            $tempInCel = intval($weatherArray['main']['temp'] - 273);
            $weather .= " with a temperature of " . $tempInCel . "&deg;C. Wind speeds are " . $weatherArray['wind']['speed'] . " m/s.";
        }
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <title>Weather App</title>
        <style>
            html {
                background: url("myBackground.jpg");
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                height: 100%;
                margin: 0 auto;

            }
            body {
                background: none;
            }
            .container {
                display: flex;
                flex-flow: column;
                justify-content: center;
                text-align: center;
                margin: 100px auto;
                max-width: 450px;
            }
            .alert {
                margin: 1rem;
            }
            .alert-primary {
                background-color: white;
                color: #212529;
            }
        </style>
    </head>
    <body>
        <div class="container">
        <!-- <?php echo var_dump($_SERVER); ?> -->
            <h1>What's the weather?</h1>
            <form>
                <div class="form-group">
                    <label for="city">Enter the name of a city.</label>
                    <input id="city" 
                           name="city" 
                           type="text" 
                           class="form-control" 
                           aria-describedby="city" 
                           placeholder="e.g. Glasgow, Tokyo" 
                           value="<?php
                           if (isset($_GET['city'])) {
                               echo $_GET['city'];
                           }
                           ?>">
                    <small id="weatherComment" class="form-text text-muted">Hopefully good.</small>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            <div id="weather"><?php
                if ($weather != '') {
                    echo '<div class="alert alert-primary" role="alert">' .
                    $weather .
                    '</div>';
                } else if ($error != '') {
                    echo '<div class="alert alert-danger" role="alert">' .
                    $error .
                    '</div>';
                }
                ?></div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>