<?php

return [
    'dsn' => env('SENTRY_DSN'),
    'public_dsn' => env('PUBLIC_SENTRY_DSN'),

    // capture release as git sha
    'release' => trim(exec('git describe --tags --abbrev=0')),

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,

    // Capture default user context
    'user_context' => true,
];
