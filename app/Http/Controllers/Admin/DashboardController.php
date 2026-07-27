<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CultureItem;
use App\Models\Province;
use App\Models\TeamMember;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'counts' => [
                'provinces' => Province::count(),
                'cultureItems' => CultureItem::count(),
                'teamMembers' => TeamMember::count(),
            ],
        ]);
    }
}
