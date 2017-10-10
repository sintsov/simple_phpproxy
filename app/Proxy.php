<?php
/**
 * Class description
 *
 * @author Sintsov Roman <romiras_spb@mail.ru>
 * @copyright Copyright (c) 2017, simple_phpproxy
 */

namespace SimplePHPProxy;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Uri;

class Proxy
{
    const LIFETIME = 20;
    /**
     * @var Client
     */
    protected $client;

    protected $cache;

    public function __construct($client, $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function forward(RequestInterface $request)
    {
        if (is_null($request)) {
            throw new \HttpRuntimeException('Request is empty');
        }

        $uri = $request->getUri();

        $key = md5(
            $uri->getScheme() .
            $uri->getHost() .
            $uri->getPath() .
            $uri->getPort() .
            $uri->getQuery()
        );

        $uri = $request->getUri();
        $target = new Uri(substr($uri->getPath() . $uri->getQuery(), 1));
        $request = $request->withUri($target);

        if (!$this->cache->has($key)) {
            $response = $this->client->send($request);
            $this->cache->put($key, \GuzzleHttp\Psr7\str($response), self::LIFETIME);
        } else {
            $response = \GuzzleHttp\Psr7\parse_response($this->cache->get($key));
        }

        return $response;
    }

    public function filter()
    {
        // TODO: filters headers & other

        return $this;
    }

}
