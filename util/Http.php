<?php
/**
 * Created by PhpStorm.
 * User: Dex
 * Date: 006 11.6
 * Time: 下午 22:20
 */

namespace Decent\Util;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;
use Exception;
use Psr\Http\Message\StreamInterface;

/**
 * Class Http.
 */
class Http
{
    /**
     * Http client.
     *
     * @var HttpClient
     */
    protected $client;

    /**
     * The middlewares.
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * GET request.
     *
     * @param string $url
     * @param array  $options
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     */
    public function get($url, array $options = [])
    {
        return $this->request('GET', $url, ['query' => $options]);
    }

    /**
     * POST request.
     *
     * @param string       $url
     * @param array|string $options
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     */
    public function post($url, $options = [])
    {
        $key = is_array($options) ? 'form_params' : 'body';

        return $this->request('POST', $url, [$key => $options]);
    }

    public function put($url, array $options = [])
    {
        return $this->request('PUT', $url, ['form_params' => $options]);
    }

      public function delete($url)
    {
        return $this->request('DELETE', $url);
    }

    /**
     * JSON request.
     *
     * @param string       $url
     * @param string|array $options
     * @param int          $encodeOption
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     */
    public function json($url, $options = [], $encodeOption = JSON_UNESCAPED_UNICODE)
    {
        is_array($options) && $options = json_encode($options, $encodeOption);

        return $this->request('POST', $url, ['body' => $options, 'headers' => ['content-type' => 'application/json']]);
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array  $files
     * @param array  $form
     *
     * @return ResponseInterface
     *
     * @throws HttpException
     */
    public function upload($url, array $files = [], array $form = [], array $queries = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request('POST', $url, ['query' => $queries, 'multipart' => $multipart]);
    }

    /**
     * Set GuzzleHttp\Client.
     *
     * @param \GuzzleHttp\Client $client
     *
     * @return Http
     */
    public function setClient(HttpClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Return GuzzleHttp\Client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        if (!($this->client instanceof HttpClient)) {
            $this->client = new HttpClient();
        }

        return $this->client;
    }

    /**
     * Make a request.
     *
     * @param string $url
     * @param string $method
     * @param array  $options
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function request($method, $url, $options = [])
    {
        $method = strtoupper($method);

        $options['handler'] = $this->getHandler();

        $response = $this->getClient()->request($method, $url, $options);

        return $response;
    }

    /**
     * Add a middleware.
     *
     * @param callable $middleware
     *
     * @return $this
     */
    public function addMiddleware(callable $middleware)
    {
        array_push($this->middlewares, $middleware);

        return $this;
    }

    /**
     * Build a handler.
     *
     * @return HandlerStack
     */
    protected function getHandler()
    {
        $stack = HandlerStack::create();

        foreach ($this->middlewares as $middleware) {
            $stack->push($middleware);
        }

        return $stack;
    }

    public function parseJSON($method, array $args)
    {
        $response = call_user_func_array([$this, $method], $args);

        if ($response instanceof ResponseInterface) {
            $response = $response->getBody();
        }

        $contents = json_decode($response, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new Exception('Failed to parse JSON: '.json_last_error_msg());
        }

        return $contents;
    }
}
