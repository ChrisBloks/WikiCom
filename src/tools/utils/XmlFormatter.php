<?php

namespace Wiki\tools\utils;

class XmlFormatter
{
    public static function formatXML(string $rootElement, array $document): string
    {
        $xml = '';
        $xml .= "<?xml version='1.0' encoding='UTF-8'?>";
        foreach ($document as $key => $value) {
            $xml .= '<' . $rootElement . '>';
            foreach ($value as $element => $content) {
                $xml .= '<' . $element . '>' . $content . '</' . $element . '>';
            }
            $xml .= '</' . $rootElement . '>';
        }

        return $xml;
    }

}
