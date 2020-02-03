<?php
namespace TrollAndToad\Sellbrite\Inventory;

use TrollAndToad\Sellbrite\Core\Core;

/**
 * This POST endpoint creates an inventory record only if it does not exist. If the inventory
 * does exist it will error with an HTTP CONFLICT error. If you would like to ignore errors
 * and upsert inventories see the PUT /inventories endpoint.
 */
class PatchInventory extends Core
{
   /**
    * @param array|null $invArr Array of inventory to create at sellbrite
    *
    * @return mixed
    * @throws \Exception
    * @throws \GuzzleHttp\Exception\GuzzleException
    */
    public function sendRequest(array $invArr = null)
    {
        if (count($invArr['inventory']) > 50)
            throw new \Exception("You may only PATCH up to 50 individual inventories at a time.");

        // Build the API endpoint
        $url = self::BASE_URI . 'inventory';

        // Build the API headers
        $apiHeaders = $this->baseApiHeaders;
        $apiHeaders['headers']['Content-Type'] = 'application/json';

        // Create the body
        $apiHeaders['body'] = json_encode($invArr);

        // Send the HTTP request to the API endpoint and get the response stream
        $response = $this->httpClient->request('PATCH', $url, $apiHeaders);

        // Get the status code returned with the response
        $statusCode = $response->getStatusCode();

        // Get the response body
        $messageArr = json_decode((string) $response->getBody(), true);

        // Check status code for success or failure
        switch ($statusCode)
        {
            case 200:
                return $response;
            case 401:
                throw new \Exception("401 Unauthorized - HTTP Basic: Access denied.");
                break;
            case 404:
                throw new \Exception('404 Not Found - ' . $messageArr['error']);
                break;
            default:
                throw new \Exception('This is the default error.');
        }
    } // End public function sendRequest
} // End class PatchInventory
