<?php

return [
    'client_id' => env('ISLANDS_CLIENT_ID'),
    'client_secret' => env('ISLANDS_CLIENT_SECRET'),
    'redirect' => env('ISLANDS_REDIRECT_URI'),
    'base_url' => env('ISLANDS_BASE_URL', 'https://identity-server.staging01.devland.is'),
];
