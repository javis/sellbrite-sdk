<?php
namespace TrollAndToad\Sellbrite\Orders;

use TrollAndToad\Sellbrite\Core\Core;

class GetOrder extends Core
{
    /**
     * @param string $sb_order_sequence Sellbrite Order Number sequence
     *
     * @return string
     */
    public function sendRequest($sb_order_seq = null) {

        if (is_null($sb_order_seq) === true)
            throw new \Exception('You failed to supply a Sellbrite Order Sequence number.');

        // Build the API endpoint
        $url = self::BASE_URI . 'orders/' . $sb_order_seq;

        // Build the API headers
        $apiHeaders = $this->baseApiHeaders;
        $apiHeaders['headers']['Content-Type'] = 'application/json';

        // Send the HTTP request to the API endpoint and get the response stream
        $response = $this->httpClient->request('GET', $url, $apiHeaders);

        // Get the status code returned with the response
        $statusCode = $response->getStatusCode();

        // Check status code for success or failure
        switch ($statusCode)
        {
            case 200:
                return (string) $response->getBody();
                break;
            case 401:
                throw new \Exception("401 Unauthorized - HTTP Basic: Access denied.");
                break;
            default:
                throw new \Exception('Unknown error.');
        }
    } // End public function sendRequest
} // End class GetOrder
