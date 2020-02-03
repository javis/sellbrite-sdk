<?php
namespace TrollAndToad\Sellbrite\Warehouses;

use TrollAndToad\Sellbrite\Core\Core;

class PostWarehouse extends Core
{
    /**
     * @param array $warehouseInfoArr Array that holds all the information for the associated warehouse
     */
    public function sendRequest(array $warehouseInfoArr = null)
    {
        if (is_null($warehouseInfoArr) === true || empty($warehouseInfoArr) === true)
            throw new \Exception('You have to supply an appropriate warehouse information array.');

        // Build the API endpoint
        $url = self::BASE_URI . 'warehouses';

        // Build the API headers
        $apiHeaders = $this->baseApiHeaders;
        $apiHeaders['headers']['Content-Type'] = 'application/json';

        // Add the body params
        $apiHeaders['body'] = json_encode($warehouseInfoArr);

        // Send the HTTP request to the API endpoint and get the response stream
        $response = $this->httpClient->request('POST', $url, $apiHeaders);

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
            case 422:
                throw new \Exception("422 Unprocessable Entity - " . (string) $response->getBody());
                break;
            default:
                throw new \Exception('This is the default error.');
        }
    } // End public function sendRequest
} // End class PostWarehouse
