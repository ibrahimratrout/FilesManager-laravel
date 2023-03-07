<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*', // exclude all routes that start with 'api/'
        'stripe/*', // exclude all routes that start with 'stripe/'
        'http://example.com/foo', // exclude a specific URL
    ];
    
}
