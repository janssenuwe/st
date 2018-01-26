<?php
function getStartAndEndDate($week = 0, $year = 0)
{
    if($week == 0)
        $week = date('W')-1;

    if($year == 0)
        $year = date('Y');

    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('Y-n-j', $time);
    $time += 6*24*3600;
    $return[1] = date('Y-n-j', $time);
    return $return;
}

function startsWith($haystack, $needle)
{
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function truncate($string, $length, $dots = "...")
{
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}

/**
 * Sucht nach $_POST & $_GET mit $name, wenn nicht vorhanden wird $default zurückgegeben.
 * @param mixed $name
 * @param mixed $default
 * @return mixed|string
 */
function request_var($name, $default = NULL)
{
    if(isset($_POST[$name])) return $_POST[$name];
    elseif(isset($_GET[$name])) return $_GET[$name];
    else return $default;
}