<?php

return [

    "web" => [

        "client_id" => env("GOOGLE_CLIENT_ID"),

        "project_id" => env("GOOGLE_PROJECT_ID", "ankeboot"),

        "auth_uri" => env("GOOGLE_AUTH_URI", "https://accounts.google.com/o/oauth2/auth"),

        "token_uri" => env("GOOGLE_TOKEN_URI", "https://oauth2.googleapis.com/token"),

        "auth_provider_x509_cert_url" => env("GOOGLE_AUTH_PROVIDER_X509_CERT_URL", "https://www.googleapis.com/oauth2/v1/certs"),

        "client_secret" => env("GOOGLE_CLIENT_SECRET"),

        "redirect_uris" => [
            "http://localhost:8000/callback",
        ],

        "javascript_origins" => [
            "https://www.ankeboot.com",
            "https://admin.ankeboot.com"
        ]

    ]

];
