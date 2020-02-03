<?php
namespace TrollAndToad\Sellbrite\Traits\Validatable;

trait TextFieldsTrait
{
    /**
     * @return boolean
     */
    private function isTextFieldValid($textField)
    {
        switch ($textField)
        {
            case 'completed':
            case 'canceled':
            case 'open':
            case 'all':
            case 'partial':
            case 'none':
                return true;
            default:
                return false;
        }
    } // End public function validateField
} // End trait TextFieldsTrait
