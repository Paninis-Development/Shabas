<?php
require_once('connection.php');

function getSite($site)
{
    if(isset($_GET['site'])){
        include_once('scripts/'.$_GET['site'].'.php');
    } else{
        include_once('scripts/'.$site.'.php');
    }
}
?>