<?php

return [
    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
        'verify' => true, // Keep this as true
    ],

];