<?php
return [
  "assets_enabled" => env("MINIFY_ASSETS_ENABLED", false),
  "assets_storage" => env("MINIFY_ASSETS_STORAGE", '/'),
  "ignore" => [
      '/astroadmin'
  ],
];
