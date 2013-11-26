<?php
error_reporting(E_ALL);
/* procedural API */
$memcache_obj = memcache_connect("localhost","11211");
$T = memcache_flush($memcache_obj);
echo $T ;
?>