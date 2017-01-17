<?php

namespace AutoDeploy\Origin;

/**
 * Request class.
 */
class Request
{
    private $headers;
    private $body;

    /**
     * Build request data.
     */
    final public function __construct()
    {
        /*
         * Register headers
         */

        $this->headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $this->headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        /*
         * Register body
         */

        if (isset($_POST['payload'])) {
            $this->body = $_POST['payload'];
        } else {
            $this->body = json_decode(file_get_contents('php://input'));
        }
    }

    /**
     * Get request parameter.
     *
     * @param string $key Parameter name
     *
     * @return any Parameter value
     */
    final public function __get($key)
    {
        return (isset($this->body->$key)) ? $this->body->$key : null;
    }

    /**
     * Check for header.
     *
     * @param string $key Header name
     *
     * @return bool
     */
    final public function hasHeader($key)
    {
        return isset($this->headers[$key]);
    }

    /**
     * Get for header.
     *
     * @param string $key Header name
     *
     * @return bool
     */
    final public function getHeader($key)
    {
        return (isset($this->headers[$key])) ? $this->headers[$key] : null;
    }
}
