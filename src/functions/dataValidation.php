<?php
function xssSanit($input){
    return strip_tags(htmlspecialchars($input,ENT_QUOTES,'UTF-8'));
}
function checkForTag($input){
    if(strpos($input,'>') !==false || strpos($input,'<') !== false  || strpos($input,'/') !== false){
        return 1;
    }else
        return 0;
}
?>