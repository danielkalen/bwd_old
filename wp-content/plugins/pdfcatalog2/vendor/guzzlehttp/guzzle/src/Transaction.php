<?php
namespace GuzzleHttp;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;

/**
 * Represents the relationship between a client, request, and response.
 *
 * You can access the request, response, and client using their corresponding
 * public properties.
 */
class Transaction
{
    /**
     * HTTP client used to transfer the request.
     *
     * @var ClientInterface
     */
    public $client;

    /**
     * The request that is being sent.
     *
     * @var RequestInterface
     */
    public $request;

    /**
     * The response associated with the transaction. A response will not be
     * present when a networking error occurs or an error occurs before sending
     * the request.
     *
     * @var ResponseInterface|null
     */
    public $response;

    /**
     * Exception associated with the transaction. If this exception is present
     * when processing synchronous or future commands, then it is thrown. When
     * intercepting a failed transaction, you MUST set this value to null in
     * order to prevent the exception from being thrown.
     *
     * @var \Exception
     */
    public $exception;

    /**
     * Associative array of handler specific transfer statistics and custom
     * key value pair information. When providing similar information, handlers
     * should follow the same key value pair naming conventions as PHP's
     * curl_getinfo() (http://php.net/manual/en/function.curl-getinfo.php).
     *
     * @var array
     */
    public $transferInfo = [];

    /**
     * The transaction's state.
     *
     * @var string
     */
    public $state;

    /**
     * The number of state transitions that this transactions has been through.
     *
     * @var int
     * @internal This is for internal use only. If you modify this, then you
     *           are asking for trouble.
     */
    public $_transitionCount;

    /**
     * @param ClientInterface  $client  Client that is used to send the requests
     * @param RequestInterface $request Request to send
     */
    public function __construct(
        ClientInterface $client,
        RequestInterface $request
    ) {
        $this->client = $client;
        $this->request = $request;
    }
}
