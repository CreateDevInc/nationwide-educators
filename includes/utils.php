<?php

if (!function_exists("is_register_page")) {
    function is_register_page($url)
    {
        return preg_match('/.*register(\/$|\?|$)/', $url);
    }
}

if (!function_exists("is_account_page")) {
    function is_account_page($url)
    {
        return preg_match('/.*\/account(\/$|\?|$)/', $url);
    }
}

if (!function_exists('is_page_type')) {
    function is_page_type($type, $url)
    {
        return preg_match('/.*\/' . $type . '(\/$|\?|$)/', $url);
    }
}
