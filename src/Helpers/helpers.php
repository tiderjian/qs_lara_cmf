<?php
if (!function_exists('is_value_empty')) {
    function is_value_empty($value)
    {
        return empty($value) && $value != 0;
    }
}

if(!function_exists('normalize_path')){
    function normalize_path(string $path) : string
    {
        $to = str_replace(base_path(), '', $path);

       return \League\Flysystem\Util::normalizePath($to);
    }
}


