<?php
namespace TrollAndToad\Sellbrite\Inventory;

use TrollAndToad\Sellbrite\Core\Core;
use TrollAndToad\Sellbrite\Traits\Validatable\DateFieldsTrait;

class GetAllInventory extends Core
{
    use DateFieldsTrait;

    /**
     * @param integer $page Page number
     * @param integer $limit Number of results per page
     * @param string $warehouse_uuid Filter by warehouse identifier
     * @param string $sku Filter by sku
     * @param string $created_at_min Filters inventory ordered after create date (ISO 8601)
     * @param string $created_at_max Filters inventory ordered before create date (ISO 8601)
     * @param string $updated_at_min Filters inventory ordered after update date (ISO 8601)
     * @param string $updated_at_max Filters inventory ordered before update date (ISO 8601)
     *
     * @return object|string
     */
    public function sendRequest(
        $page = null,
        $limit = null,
        $warehouse_uuid = null,
        $sku = null,
        $created_at_min = null,
        $created_at_max = null,
        $updated_at_min = null,
        $updated_at_max = null
    ) {
        // Build the API endpoint
        $url = self::BASE_URI . 'inventory';

        // Build the API headers
        $apiHeaders = $this->baseApiHeaders;
        $apiHeaders['headers']['Content-Type'] = 'application/json';

        $uuid_pattern = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i';

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

        // Only add the warehouse uuid if it matches the official UUID pattern
        if (is_null($warehouse_uuid) === false && preg_match($uuid_pattern, $warehouse_uuid) === 1)
        {
            $apiHeaders['query']['warehouse_uuid'] = $warehouse_uuid;
        }

        // Add the sku if the value isn't empty
        if (is_null($sku) === false)
        {
            $apiHeaders['query']['sku'] = $sku;
        }

        // Add the created_at_min datetime query string (ISO 8601)
        if (is_null($created_at_min) === false && $this->isDateValid($created_at_min))
        {
            $apiHeaders['query']['created_at_min'] = $created_at_min;
        }

        // Add the created_at_max datetime query string (ISO 8601)
        if (is_null($created_at_max) === false && $this->isDateValid($created_at_max))
        {
            $apiHeaders['query']['created_at_max'] = $created_at_max;
        }

        // Add the upated_at_min datetime query string (ISO 8601)
        if (is_null($updated_at_min) === false && $this->isDateValid($updated_at_min))
        {
            $apiHeaders['query']['updated_at_min'] = $updated_at_min;
        }

        // Add the updated_at_max datetime query string (ISO 8601)
        if (is_null($updated_at_max) === false && $this->isDateValid($updated_at_max))
        {
            $apiHeaders['query']['updated_at_max'] = $updated_at_max;
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
} // End class GetAllInventory
