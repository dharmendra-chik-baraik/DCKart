<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $activityLogs = $this->activityLogService->getActivityLogs($request->all());
        $filterOptions = $this->activityLogService->getFilterOptions();

        return view('admin.activity-logs.index', compact('activityLogs', 'filterOptions'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        try {
            $this->activityLogService->deleteActivityLog($activityLog);
            
            return redirect()->route('admin.activity-logs.index')
                ->with('success', 'Activity log deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting activity log: ' . $e->getMessage());
        }
    }

    public function clearOldLogs(Request $request)
    {
        try {
            $days = $request->input('days', 30);
            $deletedCount = $this->activityLogService->clearOldLogs($days);
            
            return redirect()->route('admin.activity-logs.index')
                ->with('success', "Successfully cleared {$deletedCount} old activity logs (older than {$days} days).");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error clearing old logs: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        // This can be implemented later for CSV/Excel export
        return redirect()->back()
            ->with('info', 'Export feature will be implemented soon.');
    }
}