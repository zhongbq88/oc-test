<?php
//error_reporting(0);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

$src 	= $_GET['src'];

if(strpos($src, 'http') === false) {
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
		$src	= 'https://'. $src;
	else
		$src	= 'http://'. $src;
}

$src 	= str_replace(' ', '%20', $src);

$type = pathinfo($src, PATHINFO_EXTENSION);

$img =  openURL($src);
header("Content-type: image/$type");
echo $img;
exit;

function openURL($url, $use_include_path = false, $stream_context = null, $curl_timeout = 5)
{
    $url = str_replace('//uploaded', '//uploaded', $url);
    $url = str_replace('//tshirtecommerce', '//tshirtecommerce', $url);

    if ($stream_context == null && preg_match('/^https?:\/\//', $url)) {
        $stream_context = @stream_context_create(array('http' => array('timeout' => $curl_timeout)));
    }
    if (in_array(ini_get('allow_url_fopen'), array('On', 'on', '1')) || !preg_match('/^https?:\/\//', $url)) {
        return @file_get_contents($url, $use_include_path, $stream_context);
    } elseif (function_exists('curl_init')) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, $curl_timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        if ($stream_context != null) {
            $opts = stream_context_get_options($stream_context);
            if (isset($opts['http']['method']) && fnStrtolower($opts['http']['method']) == 'post') {
                curl_setopt($curl, CURLOPT_POST, true);
                if (isset($opts['http']['content'])) {
                    parse_str($opts['http']['content']);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, array());
                }
            }
        }
        $content = curl_exec($curl);
        curl_close($curl);
        return $content;
    } else {
        return false;
    }
}

function fnStrtolower($str)
{
    if (is_array($str)) {
        return false;
    }
    if (function_exists('mb_strtolower')) {
        return mb_strtolower($str, 'utf-8');
    }
    return strtolower($str);
}

?>	