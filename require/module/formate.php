<?php
function DATE_FORMATE($format, $date)
{
    $newdateformate = $date;
    if ($date == null  || $date == "" || $date == "0000-00-00" || $date == " ") {
        $newdateformate = "NA";
    } else {
        $newdateformate = date("$format", strtotime($date));
    }
    return $newdateformate;
}

//RequestDataTypeDate
function RequestDataTypeDate()
{
    date_default_timezone_set("Asia/Calcutta");
    $date = date('Y-m-d H:i:s');
    return $date;
}
date_default_timezone_set("Asia/Calcutta");
define("RequestDataTypeDate", date("Y-m-d H:i:s"));
define("RequestDataTypeDateTime", date("Y-m-d H:i:s"));
define("RequestDataTime", date("H:i:s"));
define("RequestDataDate", date("Y-m-d"));
define("CURRENT_DATE_TIME", RequestDataTypeDateTime);

//function get minutes from two time
function GetMinutes($time2, $time1)
{
    $diff_seconds = strtotime($time2) - strtotime($time1);
    $diff_minutes = round($diff_seconds / 60);

    // Example value
    $hours = floor($diff_minutes / 60);
    $remaining_minutes = $diff_minutes % 60;

    if ($diff_minutes == 0) {
        $diff_minute = "Time Over";
    } elseif ($diff_minutes < 0) {
        $hours = abs($hours);
        $remaining_minutes = abs($remaining_minutes);

        $diff_minute = $hours . " hr " . $remaining_minutes . " min over";
    } elseif ($diff_minutes > 0) {
        $diff_minute = $hours . " hr " . $remaining_minutes . " min left";
    }

    return $diff_minute;
}


//converts seconds into hours, minute and seconds
function GetDurations($second)
{

    if ($second == 0 || $second == null) {
        $results = "0 sec";
    } else {
        $hours = gmdate('H', $second);
        $minutes = gmdate('i', $second);
        $seconds = gmdate('s', $second);

        $results = $hours . "hr " . $minutes . "min " . $seconds . "sec";
    }
    return $results;
}
