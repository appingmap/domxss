<?php

include("security.php");
include("security_level_check.php");
include("functions_external.php");
include("selections.php");

$script1 = "";
$script2 = "";
$script = "";
$type = "";
$lang = "English";

if (isset($_GET['type'])) {

    $type = $_GET['type'];
    $lang = $_GET['lang'];

    if ($_COOKIE['security_level'] == '1') {
        if (stripos($lang, "<script") !== false) {
            header("location: /bWAPP/domxss.php");
            exit;
        }
    } elseif ($_COOKIE['security_level'] == '2') {
        switch ($lang) {
            case "French":
            case "English":
            case "German":
            case "Spanish":
                break;
            default:
                header("location: /bWAPP/domxss.php");
                exit;
        }
    }

    $script1 = "<form name=\"XSS\" method=\"GET\" action=\"/cookie4/domxss.php\">
    <input type=\"hidden\" name=\"type\" value=\"" . $type . "\">
    <select name=\"lang\">
    <script>
    if (document.location.href.indexOf(\"lang=\") >= 0) {";

    $script2 = "document.write(\"<option selected value='\" + lang + \"'>\" + decodeURI(lang) + \"</option>\");
    document.write(\"<option value='' disabled='disabled'>----</option>\");
}
document.write(\"<option value='English'>English</option>\");
document.write(\"<option value='French'>French</option>\");
document.write(\"<option value='Spanish'>Spanish</option>\");
document.write(\"<option value='German'>German</option>\");
</script>
</select>
<input type=\"submit\" value=\"Select\" />
</form>";

    if ($type == '1') {
        $script = "var lang = document.URL.substring(document.URL.indexOf('lang=')+5);";
        $script = $script1 . "\n" . $script . "\n" . $script2;
    } elseif ($type == '2') {
        $script = "var lang = document.documentURI.substring(document.documentURI.indexOf('lang=')+5);";
        $script = $script1 . "\n" . $script . "\n" . $script2;
    } elseif ($type == '3') {
        $script = "var lang = location.href.substring(location.href.indexOf('lang=')+5);";
        $script = $script1 . "\n" . $script . "\n" . $script2;
    } elseif ($type == '4') {
        $script = "var lang = location.search.substring(location.search.indexOf('lang=')+5);";
        $script = $script1 . "\n" . $script . "\n" . $script2;
    } elseif ($type == '5') {
        $script = "var lang = document.baseURI.substring(document.baseURI.indexOf('lang=')+5);";
        $script = $script1 . "\n" . $script . "\n" . $script2;
    } else {
        $script = "Bad Request 1.";
    }
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Awapt">
    <meta name="description" content="This is the description of the web application.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>DOM XSS | DOM XSS</title>
</head>
<body>
<div class="container">
    <div class="row">
        &nbsp;
    </div>
    <div class="row">
        <p class="display-4">DOM XSS</p>
        <div class="col-sm-12">
            <div id="security_level">
                <form action="<?php echo($_SERVER["SCRIPT_NAME"]); ?>" method="POST">
                    <label>Set your security level:</label><br/>
                    <select name="security_level">
                        <option value="0">low</option>
                        <option value="1">medium</option>
                        <option value="2">high</option>
                    </select>
                    <button type="submit" name="form_security_level" value="submit">Set</button>
                    <font size="4">Current: <b><?php echo $security_level ?></b></font>
                </form>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="btn-group">
                <button class="btn btn-warning btn-lg dropdown-toggle" type="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    DOM XSS Sources
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/cookie4/domxss.php?type=1">document.URL</a>
                    <a class="dropdown-item" href="/cookie4/domxss.php?type=2">document.documentURI</a>
                    <a class="dropdown-item" href="/cookie4/domxss.php?type=3">location.href</a>
                    <a class="dropdown-item" href="/cookie4/domxss.php?type=4">location.search</a>
                    <a class="dropdown-item" href="/cookie4/domxss.php?type=5">document.baseURI</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            &nbsp;
        </div>
        <div class="col-sm-6">
            <?php echo $script; ?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>