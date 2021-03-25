<?php
function disinfect($str){
    $str = trim($str); // deletes the spaces
    $str = filter_var($str, FILTER_SANITIZE_STRING); // changes to string
    $str = strip_tags($str); // deletes every tags
    $str = htmlspecialchars($str); // html tags cant do something there like a normal string
    return $str;
}
