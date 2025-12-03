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
           'bookings',
            'reviews',
                'admin/services/bookings/*/approve',
    'admin/services/bookings/*/reject',
    ];
}
