<?php

class Url
{

    public static function to(array $params = []): string
    {
        return '?' . http_build_query($params);
    }
}