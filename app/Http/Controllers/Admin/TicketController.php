<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TicketMessageRequest;
use App\Http\Requests\Admin\TicketStoreRequest;
use App\Http\Requests\Admin\TicketUpdateRequest;
use App\Models\User;
use App\Models\VendorProfile;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'priority' => $request->get('priority'),
            'search' => $request->get('search'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $tickets = $this->ticketService->getAllTickets($filters);
        $stats = $this->ticketService->getTicketStats();
        // REMOVED: adminUsers

        return view('admin.tickets.index', compact('tickets', 'stats', 'filters'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['customer', 'vendor'])->get();
        $vendors = VendorProfile::where('status', 'approved')->get();
        // REMOVED: adminUsers

        return view('admin.tickets.create', compact('users', 'vendors'));
    }

    public function store(TicketStoreRequest $request)
    {
        try {
            $ticketData = $request->validated();

            // Remove assigned_to from data if present
            if (isset($ticketData['assigned_to'])) {
                unset($ticketData['assigned_to']);
            }

            $ticket = $this->ticketService->createTicket($ticketData);

            // Add the initial message
            $this->ticketService->addMessage($ticket->id, [
                'sender_id' => auth()->id(),
                'message' => $request->message,
            ], $request->file('attachment'));

            return redirect()->route('admin.tickets.show', $ticket->id)
                ->with('success', 'Ticket created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create ticket: '.$e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $ticket = $this->ticketService->getTicket($id);
        $responseTime = $this->ticketService->getTicketResponseTime($id);

        return view('admin.tickets.show', compact('ticket', 'responseTime'));
    }

    public function update(TicketUpdateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            // Remove assigned_to from data if present
            if (isset($data['assigned_to'])) {
                unset($data['assigned_to']);
            }

            $ticket = $this->ticketService->updateTicket($id, $data);

            return redirect()->route('admin.tickets.show', $id)
                ->with('success', 'Ticket updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update ticket: '.$e->getMessage());
        }
    }

    public function addMessage(TicketMessageRequest $request, $id)
    {
        try {
            $message = $this->ticketService->addMessage(
                $id,
                [
                    'sender_id' => auth()->id(),
                    'message' => $request->message,
                ],
                $request->file('attachment')
            );

            return redirect()->route('admin.tickets.show', $id)
                ->with('success', 'Message added successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add message: '.$e->getMessage());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        try {
            $ticket = $this->ticketService->changeStatus($id, $request->status);

            return redirect()->route('admin.tickets.show', $id)
                ->with('success', 'Ticket status updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update ticket status: '.$e->getMessage());
        }
    }

    // REMOVED: assign method

    public function destroy($id)
    {
        try {
            $this->ticketService->deleteTicket($id);

            return redirect()->route('admin.tickets.index')
                ->with('success', 'Ticket deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete ticket: '.$e->getMessage());
        }
    }

    public function bulkUpdate(Request $request)
    {
        // Check if it's an AJAX/JSON request
        if ($request->expectsJson() || $request->isJson()) {
            try {
                \Log::info('Bulk update JSON request received', $request->all());

                $validated = $request->validate([
                    'ticket_ids' => 'required|array',
                    'ticket_ids.*' => 'exists:tickets,id',
                    'action' => 'required|in:status,priority,delete',
                ]);

                $successCount = 0;
                $results = [];

                switch ($request->action) {
                    case 'status':
                        $statusValidated = $request->validate([
                            'status' => 'required|in:open,in_progress,resolved,closed',
                        ]);

                        foreach ($request->ticket_ids as $ticketId) {
                            try {
                                $this->ticketService->changeStatus($ticketId, $request->status);
                                $successCount++;
                            } catch (\Exception $e) {
                                \Log::error("Failed to update ticket {$ticketId}: ".$e->getMessage());
                            }
                        }
                        break;

                    case 'priority':
                        $priorityValidated = $request->validate([
                            'priority' => 'required|in:low,medium,high,urgent',
                        ]);

                        foreach ($request->ticket_ids as $ticketId) {
                            try {
                                $this->ticketService->updateTicket($ticketId, ['priority' => $request->priority]);
                                $successCount++;
                            } catch (\Exception $e) {
                                \Log::error("Failed to update ticket {$ticketId}: ".$e->getMessage());
                            }
                        }
                        break;

                    case 'delete':
                        foreach ($request->ticket_ids as $ticketId) {
                            try {
                                $this->ticketService->deleteTicket($ticketId);
                                $successCount++;
                            } catch (\Exception $e) {
                                \Log::error("Failed to delete ticket {$ticketId}: ".$e->getMessage());
                            }
                        }
                        break;
                }

                return response()->json([
                    'success' => true,
                    'message' => "Successfully processed {$successCount} tickets",
                    'processed_count' => $successCount,
                ]);

            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Server error: '.$e->getMessage(),
                ], 500);
            }
        }

        return redirect()->back()->with('error', 'Invalid request format');
    }

    public function downloadAttachment($filename)
    {
        try {
            $path = Storage::disk('public')->path($filename);

            return response()->download($path);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'File not found.');
        }
    }
}
