<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Response;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Returns the route, called in dashboard GET.
     */
    public function create(): Response
    {
        return Inertia::render('Dashboard');
    }
}
