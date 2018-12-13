<?php
if (!function_exists('is_value_empty')) {
    /**
     * @param $value
     * @return bool
     */
    function is_value_empty($value)
    {
        if ($value === '0') {
            $value = (int)$value;
        }
        return empty($value) && $value !== 0;
    }
}

if (!function_exists('normalize_path')) {
    /**
     * @param string $path
     * @return string
     */
    function normalize_path(string $path) : string
    {
        $to = str_replace(base_path(), '', $path);

        return \League\Flysystem\Util::normalizePath($to);
    }
}
