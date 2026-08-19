<?php

class Url
{

    public static function buildUrl(array $params = []): string
    {
        return '?' . http_build_query($params);
    }
}