<?php
namespace TrollAndToad\Sellbrite\VariationGroups;

use TrollAndToad\Sellbrite\Core\Core;

/**
 * The Product API isn't documented enough. Will not use until it is.
 */
class DeleteVariationGroup extends Core
{
    /**
     * @param integer $page Page number
     * @param integer $limit Number of results per page
     * @param array   $skuArr An array of SKUs
     */
    public function sendRequest($sku = null)
    {
        if (is_null($sku) === true || empty($sku) === true || is_string($sku) === false)
            throw new \Exception('You failed to supply a SKU.');

        // Build the API endpoint
        $url = self::BASE_URI . 'variation_groups/' . $sku;

        // Build the API headers
        $apiHeaders = $this->baseApiHeaders;
        $apiHeaders['headers']['Content-Type'] = 'application/json';

        // Send the HTTP request to the API endpoint and get the response stream
        $response = $this->httpClient->request('DELETE', $url, $apiHeaders);

        // Get the status code returned with the response
        $statusCode = $response->getStatusCode();

        // Check status code for success or failure
        switch ($statusCode)
        {
            case 200:
                // Returning the PSR7 response object because of the Total-Pages header. Will handle the
                // total pages logic outside of this class
                return 'Succesfully deleted a variation group.';
                break;
            case 401:
                throw new \Exception("401 Unauthorized - HTTP Basic: Access denied.");
                break;
            default:
                throw new \Exception('This is the default error.');
        }
    } // End public function sendRequest
} // End class DeleteVariationGroup
