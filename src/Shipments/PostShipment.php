<?php
namespace TrollAndToad\Sellbrite\Shipments;

use TrollAndToad\Sellbrite\Core\Core;

class PostShipment extends Core
{
    /**
     *
     */
    public function sendRequest(array $shipmentArray = null)
    {
        // Build the API endpoint
        $url = self::BASE_URI . 'shipments';

        // Build the API headers
        $apiHeaders = $this->baseApiHeaders;
        $apiHeaders['headers']['Content-Type'] = 'application/json';

        // Create the body
        $apiHeaders['body'] = json_encode($shipmentArray);

        // Send the HTTP request to the API endpoint and get the response stream
        $response = $this->httpClient->request('POST', $url, $apiHeaders);

        // Get the status code returned with the response
        $statusCode = $response->getStatusCode();

        // Get the response body
        $messageArr = json_decode((string) $response->getBody(), true);

        // Check status code for success or failure
        switch ($statusCode)
        {
            case 200:
            case 201:
                return $messageArr['body'];
            case 400:
                throw new \Exception('400 Bad Request - ' . $messageArr['error']);
                break;
            case 401:
                throw new \Exception("401 Unauthorized - HTTP Basic: Access denied.");
                break;
            case 404:
                throw new \Exception('404 Not Found - ' . $messageArr['error']);
                break;
            case 422:
                throw new \Exception('404 Unprocessable Entity - ' . $messageArr['error']);
                break;
            default:
                throw new \Exception('This is the default error.');
        }
    } // End public function sendRequest
} // End class PostShipment
