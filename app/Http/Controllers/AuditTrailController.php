<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditTrailController extends Controller
{
    /**
     * Display a searchable and filterable list of system audit logs.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')->latest();

        // 1. Keyword search (Action or Description)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // 2. Action specific filter
        if ($action = $request->input('action_filter')) {
            $query->where('action', $action);
        }

        // 3. Date range filter
        if ($startDate = $request->input('start_date')) {
            $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if ($endDate = $request->input('end_date')) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        // 4. Paginate results
        $logs = $query->paginate(20)->appends($request->query());

        // Retrieve distinct actions for the dropdown filter
        $distinctActions = AuditLog::select('action')->distinct()->pluck('action');

        // Check which view file exists so it works regardless of where the file is saved
        $viewName = view()->exists('audit.index')
            ? 'audit.index'
            : 'layouts.auth.audit.index';

        return view($viewName, [
            'logs' => $logs,
            'distinctActions' => $distinctActions,
            'search' => $search,
            'actionFilter' => $action,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
