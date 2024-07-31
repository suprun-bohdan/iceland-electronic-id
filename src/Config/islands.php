<?php

return [
    'client_id' => env('ISLANDS_CLIENT_ID'),
    'client_secret' => env('ISLANDS_CLIENT_SECRET'),
    'redirect_uri' => env('ISLANDS_REDIRECT_URI'),
    'token_url' => env('ISLANDS_TOKEN_URL', 'https://identity-server.staging01.devland.is/connect/token'),
    'user_info_url' => env('ISLANDS_USER_INFO_URL', 'https://identity-server.staging01.devland.is/connect/userinfo'),
];
