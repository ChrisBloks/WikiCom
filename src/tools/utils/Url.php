<?php

namespace Wiki\tools\utils;

class Url
{

    // method for building a url
    public static function buildUrl(array $params = []): string
    {
        return '?' . http_build_query($params);
    }
}
