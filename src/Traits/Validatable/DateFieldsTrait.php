<?php
namespace TrollAndToad\Sellbrite\Traits\Validatable;

use Carbon\Carbon;

trait DateFieldsTrait
{
    private function isDateValid($dateField)
    {
        try {
            Carbon::parse($dateField);
            return true;
        } catch (\Exception $e)
        {
            return false;
        }
    } // function validateDateField
} // End trait DateFieldsTrait
