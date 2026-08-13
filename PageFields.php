<?php

class PageFields
{
    public function getFieldsForPage(string $page): array
    {
        switch ($page) {

            case 'contact':
                return [
                    'form_info' => [
                        "action"         => "contact.php",
                        "method"         => "POST",
                        "submit_caption" => "Send message"
                    ]
                ];

            case 'login':
                return [
                    'form_info' => [
                        "action"         => "login.php",
                        "method"         => "POST",
                        "submit_caption" => "Log in"
                    ]
                ];

            case 'register':
                return [
                    'form_info' => [
                        "action"         => "login.php",
                        "method"         => "POST",
                        "submit_caption" => "Log in"
                    ]
                ];
            case 'search':
                return [
                    'form_info' =>[
                    "action"         => "articles.php",
                    "method"         => "GET",
                    "submit_caption" => "Filter"
                ]
                ];



            default:
                throw new \LogicException("No form fields defined for page: '$page'");
        }
    }
}
