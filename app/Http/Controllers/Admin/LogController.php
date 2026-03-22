<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Inertia\Inertia;
use Inertia\Response;

class LogController extends Controller
{
    public function index(): Response
    {
        $logs = AuditLog::query()
            ->with('user:id,name,email')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Logs/Index', [
            'logs' => $logs,
        ]);
    }
}
