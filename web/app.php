<?php

use Symfony\Component\HttpFoundation\Request;

// prevent bot to access to old urls
//$regex_arr  = ["/catid=/", "/format=/", "/cntr=/", "/option=/", "/start=/", "/page__type=/", "/id=/", "/page_type=/", "/f=/", "/emulatemode=/"];
$regex_arr  = ["/searchword=/"];
if ($_SERVER['QUERY_STRING']) {
	foreach ($regex_arr as $regex) {
		if(preg_match($regex, $_SERVER['QUERY_STRING'], $match)) {
			header('HTTP/1.0 403 Forbidden');
			exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
		}
	}
}

require __DIR__.'/../vendor/autoload.php';
if (PHP_VERSION_ID < 70000) {
    include_once __DIR__.'/../var/bootstrap.php.cache';
}

$kernel = new AppKernel('prod', false);
if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
