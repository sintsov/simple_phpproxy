<?php
/**
 * Class description
 *
 * @author Sintsov Roman <romiras_spb@mail.ru>
 * @copyright Copyright (c) 2017, simple_phpproxy
 */
require __DIR__ . '/../vendor/autoload.php';

use SimplePHPProxy\Di;
use GuzzleHttp\Psr7\ServerRequest;

$request = ServerRequest::fromGlobals();

$di = (new Di())->get();
$response = $di->get('Proxy')->forward($request);

header_remove();

// Print all the headers except for "Transfer-Encoding" because chunked responses will end up failing.
foreach ($response->getHeaders() as $key => $value) {
    if ($key != "Transfer-Encoding") {
        header("$key: $value[0]");
    }
}

http_response_code($response->getStatusCode());

echo $response->getBody();
