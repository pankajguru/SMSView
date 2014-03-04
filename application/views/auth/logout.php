<?php 
header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Expires: Wed, 4 Jul 2012 05:00:00 GMT"); // Date in the past

redirect('auth/login', 'refresh');
?>