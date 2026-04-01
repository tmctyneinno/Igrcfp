<?php

return [
    'secret'  => env('RECAPTCHA_SECRET_KEY'),
    'sitekey' => env('VITE_RECAPTCHA_SITE_KEY'),
    'options' => [
        'timeout' => 30,
    ],
];