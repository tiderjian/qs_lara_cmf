<?php
if (!function_exists('is_value_empty')) {
    function is_value_empty($value)
    {
        return empty($value) && $value != 0;
    }
}