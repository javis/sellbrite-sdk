<?php
namespace TrollAndToad\Sellbrite\Products;

use TrollAndToad\Sellbrite\Core\Core;

/**
 * The Product API isn't documented enough. Will not use until it is.
 */
class GetProduct extends Core
{
    /**
     * @param integer $page Page number
     * @param integer $limit Number of results per page
     * @param array   $skuArr An array of SKUs
     */
    public function sendRequest(
        $page = null,
        $limit = null,
        array $skuArr = []
    ) {
        // Build the API endpoint
        $url = self::BASE_URI . 'products';

        // Build the API headers
        $apiHeaders = $this->baseApiHeaders;
        $apiHeaders['headers']['Content-Type'] = 'application/json';

        // Add the page query string if necessary
        if (is_null($page) === false && intval($page) > 0)
        {
            $apiHeaders['query']['page'] = $page;
        }

        // Add the limit query string is necessary. NOTE: The API limits returned order count
        // to 100. The API will -not- allow more to be fetched by a single request.
        if (is_null($limit) === false)
        {
            if (intval($limit) > 100) {
                $apiHeaders['query']['limit'] = 100;
            } else {
                $apiHeaders['query']['limit'] = $limit;
            }
        }

        if (is_array($skuArr) && empty($skuArr) === false) {
            $apiHeaders['query']['skus'] = $skuArr;
        }

        if (isset($apiHeaders['query'])) {
            $apiHeaders['query'] = http_build_query($apiHeaders['query']);
            $apiHeaders['query'] = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $apiHeaders['query']);
        }

        // Send the HTTP request to the API endpoint and get the response stream
        $response = $this->httpClient->request('GET', $url, $apiHeaders);

        // Get the status code returned with the response
        $statusCode = $response->getStatusCode();

        // Check status code for success or failure
        switch ($statusCode)
        {
            case 200:
                // Returning the PSR7 response object because of the Total-Pages header. Will handle the
                // total pages logic outside of this class
                return $response;
                break;
            case 401:
                throw new \Exception("401 Unauthorized - HTTP Basic: Access denied.");
                break;
            default:
                throw new \Exception('This is the default error.');
        }
    } // End public function sendRequest
} // End class GetProduct
