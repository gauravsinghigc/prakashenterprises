<?php

//if request
function IfRequested($method = "GET", $name, $default, $sec = null)
{

    //check request and return values via get
    if ($method == "GET") {
        if (isset($_GET["$name"])) {
            $RequestedValue = $_GET["$name"];
        } else {
            $RequestedValue = $default;
        }

        // check request and return values vai post
    } elseif ($method == "POST") {
        if (isset($_POST["$name"])) {
            $RequestedValue = $_POST["$name"];
        } else {
            $RequestedValue = $default;
        }

        //check request and return values via any request
    } elseif ($method == "REQUEST") {
        if (isset($_POST["$name"])) {
            $RequestedValue = $_REQUEST["$name"];
        } else {
            $RequestedValue = $default;
        }

        //with no request 
    } else {
        $RequestedValue = $default;
    }

    //securiyt or enct
    if ($sec == true) {
        $RequestedValue = SECURE($RequestedValue, "e");
    } elseif ($sec == null) {
        $RequestedValue = $RequestedValue;
    } else {
        $RequestedValue = $RequestedValue;
    }

    //store value in session
    $_SESSION["$name"] = $RequestedValue;

    //return value
    return $RequestedValue;
}

//POST DATA
function POST($data)
{
    $results = SECURE(htmlentities($_POST["$data"]), "e");
    if ($_POST["$data"] == null or $_POST["$data"] == "") {
        return null;
    } else {
        return $results;
    }
}


//GET DATA
function GET($data)
{
    $results = SECURE(htmlentities($_GET["$data"]), "e");
    if ($_GET["$data"] == null or $_GET["$data"] == "") {
        return null;
    } else {
        return $results;
    }
}

//Request DATA
function REQUEST($data)
{
    $results = SECURE(htmlentities($_REQUEST["$data"]), "e");
    if ($_REQUEST["$data"] == null or $_REQUEST["$data"] == "") {
        return null;
    } else {
        return $results;
    }
}


//handle form requests
function RequestHandler($SubmittedData = null, array $feedback = [false])
{
    return RESPONSE($SubmittedData, $feedback['true'], $feedback['false']);
}

//Handler Delete Requests
function DeleteReqHandler($valid, array $Requestings, array $feedback = [false])
{
    $access_url = SECURE($_GET['access_url'], "d");
    $CheckStatus = SECURE($_GET["$valid"], "d");

    if ($CheckStatus == true) {
        foreach ($Requestings as $key => $value) {
            $Response = DELETE_FROM("$key", "$value");
        }
    } else {
        $Response = false;
    }
    return RESPONSE($Response, $feedback['true'], $feedback['false']);
}

//function HandleInvalidData()
function HandleInvalidData($Data, $redirectto)
{
    if ($Data == null || $Data == '' || $Data == false || $Data == " ") {
        header("location: $redirectto");
    }
}


//Form Requests Response
function FormRequests(array $Request, $method = "post", $enc = null)
{
    foreach ($Request as $key => $Req) {

        //post requests
        if ($method == "post" || $method == "POST" || $method == "p") {

            if ($enc == null) {
                global $$Req;
                $$Req = $_POST["$Req"];
                echo "<b>$Req-></b>" . $$Req . "<br>";

                //
            } else if ($enc == "e" || $enc == "enc" || $enc == "en") {
                global $$Req;
                $$Req = SECURE($_POST["$Req"], "e");
                echo "<b>$Req-></b>" . $$Req . "<br>";

                //
            } else if ($enc == "d" || $enc == "dec" || $enc == "de") {
                $$Req = SECURE($_POST["$Req"], "d");
                global $$Req;
                echo "<b>$Req-></b>" . $$Req . "<br>";

                //
            } else {
                $$Req = $_POST["$Req"];
                global $$Req;
                echo "<b>$Req-></b>" . $$Req . "<br>";
            }

            //get requests
        } else if ($method == "GET" || $method == "get" || $method == "g") {
            if ($enc == null) {
                global $$Req;
                $$Req = $_GET["$Req"];
                echo "<b>$Req-></b>" . $$Req . "<br>";

                //
            } else if ($enc == "e" || $enc == "enc" || $enc == "en") {
                global $$Req;
                $$Req = SECURE($_GET["$Req"], "e");
                echo "<b>$Req-></b>" . $$Req . "<br>";

                //
            } else if ($enc == "d" || $enc == "dec" || $enc == "de") {
                global $$Req;
                $$Req = SECURE($_GET["$Req"], "d");
                echo "<b>$Req-></b>" . $$Req . "<br>";

                //
            } else {
                global $$Req;
                $$Req = $_GET["$Req"];
                echo "<b>$Req-></b>" . $$Req . "<br>";
            }

            //request requests
        } else if ($method == "REQUEST" || $method == "request" || $method == "r" || $method == "Request") {

            if ($enc == null) {
                global $$Req;
                $$Req = $_REQUEST["$Req"];
                echo "<b>$Req-></b>" . $$Req . "<br>";

                //
            } else if ($enc == "e" || $enc == "enc" || $enc == "en") {
                global $$Req;
                $$Req = SECURE($_REQUEST["$Req"], "e");
                echo "<b>$Req-></b>" . $$Req . "<br>";

                //
            } else if ($enc == "d" || $enc == "dec" || $enc == "de") {
                global $$Req;
                $$Req = SECURE($_REQUEST["$Req"], "d");
                echo "<b>$Req-></b>" . $$Req . "<br>";

                //
            } else {
                global $$Req;
                $$Req = $_REQUEST["$Req"];
                echo "<b>$Req-></b>" . $$Req . "<br>";
            }

            //no valid requests
        } else {
            echo "No Valid Request Found for -> <b>$Req</b><br>";
        }
    }
}
