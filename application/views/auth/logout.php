<?php 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Wed, 4 Jul 2012 05:00:00 GMT"); // Date in the past
redirect('auth/login', 'refresh');
?>