<?php
namespace TrollAndToad\Sellbrite\VariationGroups;

use TrollAndToad\Sellbrite\Core\Core;

/**
 * The Product API isn't documented enough. Will not use until it is.
 */
class PutVariationGroup extends Core
{
    /**
     * @param integer $page Page number - required
     * @param integer $limit Number of results per page - required
     * @param array   $skuArr An array of SKUs - required
     * @param array   $variationAttr An array of attributes that child SKUs vary on - required
     * @param string  $description Group description
     * @param array   $images Array of image URLs
     */
    public function sendRequest(
        $sku = null,
        $name = null,
        array $childSKUs = array(),
        array $variationAttr = array(),
        $description = null,
        array $images = array()
    ) {
        if (is_null($sku) === true || empty($sku) === true || is_string($sku) === false)
            throw new \Exception('You failed to supply a SKU.');

        if (is_null($name) === true || empty($name) === true || $name === '')
            throw new \Exception('You have to provide a name for the variation group.');

        if (is_null($childSKUs) === true || (is_array($childSKUs) === true && empty($childSKUs) === true))
            throw new \Exception('You have to provide SKUs of the products you want to be in this variation group.');

        if (is_null($variationAttr) === true || (is_array($variationAttr) === true &&
            empty($variationAttr) === true))
            throw new \Exception('Include the product attributes that the child SKUs vary on');

        // Build the API endpoint
        $url = self::BASE_URI . 'variation_groups/' . $sku;

        // Build the API headers
        $apiHeaders = $this->baseApiHeaders;
        $apiHeaders['headers']['Content-Type'] = 'application/json';

        // Set the body parameters
        $apiHeaders['body']['name'] = $name;
        $apiHeaders['body']['child_skus'] = $childSKUs;
        $apiHeaders['body']['variation_attributes'] = $variationAttr;

        if (is_null($description) === false) {
            $apiHeaders['body']['description'] = $description;
        }

        if (is_array($images) && empty($images) === false) {
            $apiHeaders['body']['images'] = $images;
        }

        // Send the HTTP request to the API endpoint and get the response stream
        $response = $this->httpClient->request('PUT', $url, $apiHeaders);

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
} // End class PutVariationGroup
