<?php
namespace TrollAndToad\Sellbrite\Interfaces;

interface ApiCallInterface
{
    // All API endpoints will be built using this base URI
    const BASE_URI = 'https://api.sellbrite.com/v1/';

    // All API classes will implement their own version of this method
    public function sendRequest();
} // End interface ApiCallInterface
