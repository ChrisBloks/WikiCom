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
                    ],
                    'form_fields' => [
                        [
                            "type"  => "text",
                            "name"  => "name",
                            "class" => "text-input",
                            "label" => "Your name"
                        ],
                        [
                            "type"  => "email",
                            "name"  => "email",
                            "class" => "text-input",
                            "label" => "Your email"
                        ],
                        [
                            "type"  => "textarea",
                            "name"  => "message",
                            "class" => "text-input",
                            "label" => "Your message"
                        ]
                    ]
                ];

            case 'login':
                return [
                    'form_info' => [
                        "action"         => "login.php",
                        "method"         => "POST",
                        "submit_caption" => "Log in"
                    ],
                    'form_fields' => [
                        [
                            "type"  => "text",
                            "name"  => "username",
                            "class" => "text-input",
                            "label" => "Username"
                        ],
                        [
                            "type"  => "password",
                            "name"  => "password",
                            "class" => "text-input",
                            "label" => "Password"
                        ]
                    ]
                ];

            case 'register':
                return [
                    'form_info' => [
                        "action"         => "login.php",
                        "method"         => "POST",
                        "submit_caption" => "Log in"
                    ],
                    'form_fields' => [
                        [
                            "type"  => "text",
                            "name"  => "username",
                            "class" => "text-input",
                            "label" => "Username"
                        ],
                        [
                            "type"  => "email",
                            "name"  => "email",
                            "class" => "text-input",
                            "label" => "Password"
                        ],
                        [
                            "type"  => "password",
                            "name"  => "password",
                            "class" => "text-input",
                            "label" => "Password"
                        ],
                        [
                            "type"  => "password",
                            "name"  => "verifypassword",
                            "class" => "text-input",
                            "label" => "verifyPassword"
                        ]
                    ]
                ];
            case 'search':
                return [
                    'form_info' =>[
                    "action"         => "articles.php",
                    "method"         => "GET",
                    "submit_caption" => "Filter"
                ],

                'form_fields' => [
                    [
                        "type"    => "checkboxgroup",
                        "name"    => "tags",
                        "class"   => "filter-tags",
                        "label"   => "Filter by tag",
                        "options" => [
                            "php"  => "PHP",
                            "oop"  => "OOP",
                            "wiki" => "Wiki",
                            // eventually pulled from a DB query of distinct tags
                        ]
                    ],
                    [
                        "type"    => "select",
                        "name"    => "author",
                        "class"   => "filter-author",
                        "label"   => "Filter by author",
                        "options" => [
                            ""      => "Any author",
                            "jdoe"  => "J. Doe",
                            "asmith" => "A. Smith",
                            // eventually pulled from a DB query of distinct authors
                        ]
                    ]
                ]
                ];



            default:
                throw new \LogicException("No form fields defined for page: '$page'");
        }
    }
}
