<?php
header ('HTTP/1.0 503 Service Temporarily Unavailable');
readfile(dirname(__FILE__) . '/index.html');
?>
