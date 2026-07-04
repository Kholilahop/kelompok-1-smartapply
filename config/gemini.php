<?php

return [
    'api_key' => env('GEMINI_API_KEY', ''),
    'model' => env('GEMINI_MODEL', 'gemini-pro'),
    'timeout' => env('GEMINI_TIMEOUT', 60),
    'temperature' => env('GEMINI_TEMPERATURE', 0.7),
    'max_tokens' => env('GEMINI_MAX_OUTPUT_TOKENS', 2048),
];