<?php
/*
 * PHP Configuration test
 */

$output = '';

$break = isset($_SERVER['HTTP_USER_AGENT']) ? '<br />' : PHP_EOL;

/*
 * Checking PHP Version
 */
$version = explode('.', PHP_VERSION);

$iPhpVersion = $version[0] * 10000 + $version[1] * 100 + $version[2];

if ($iPhpVersion < 50000) {
    $output .= 'Your PHP version (' . PHP_VERSION . '), is too old, update to PHP 5' . $break;
} else {
    	$output .= 'Your PHP version is ' . PHP_VERSION . ' => OK' . $break;

    	/*
		 * Checking necessary packages
		 */
		//ZipArchive
		if (!phpversion('zip')) {
		    $output .= 'You have to install Zip extension for PHP' . $break;
		} else {
		    $output .= 'Your Zip extension version is ' . phpversion('zip') . ' => OK' . $break;
		}
		//XSL
		if (!phpversion('xsl')) {
		    $output .= 'You have to install XSL extension for PHP' . $break;
		} else {
		    $output .= 'Your XSL extension version is ' . phpversion('xsl') . ' => OK' . $break;
		}
		//Tidy
		if (!phpversion('tidy')) {
		    $output .= 'You need to install Tidy extension for PHP if you want to use HTML or PDF features.' . $break;
		} else {
		    $output .= 'Your Tidy extension version is ' . phpversion('tidy') . ' => OK' . $break;
		}
}


echo $output;
?>
