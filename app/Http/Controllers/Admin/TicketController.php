<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TicketStoreRequest;
use App\Http\Requests\Admin\TicketUpdateRequest;
use App\Http\Requests\Admin\TicketMessageRequest;
use App\Services\TicketService;
use App\Models\User;
use App\Models\VendorProfile;
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
                ->with('error', 'Failed to create ticket: ' . $e->getMessage())
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
                ->with('error', 'Failed to update ticket: ' . $e->getMessage());
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
                ->with('error', 'Failed to add message: ' . $e->getMessage());
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
                ->with('error', 'Failed to update ticket status: ' . $e->getMessage());
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
                ->with('error', 'Failed to delete ticket: ' . $e->getMessage());
        }
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:tickets,id',
            'action' => 'required|in:status,priority,delete',
            // REMOVED: assign from actions
        ]);

        try {
            $results = [];

            switch ($request->action) {
                case 'status':
                    $request->validate(['status' => 'required|in:open,in_progress,resolved,closed']);
                    $results = $this->ticketService->bulkUpdate($request->ticket_ids, ['status' => $request->status]);
                    break;

                case 'priority':
                    $request->validate(['priority' => 'required|in:low,medium,high,urgent']);
                    $results = $this->ticketService->bulkUpdate($request->ticket_ids, ['priority' => $request->priority]);
                    break;

                case 'delete':
                    foreach ($request->ticket_ids as $ticketId) {
                        $this->ticketService->deleteTicket($ticketId);
                    }
                    $results = ['success' => true];
                    break;
            }

            $successCount = count(array_filter($results, function($result) {
                return isset($result['status']) && $result['status'] === 'success';
            }));

            return redirect()->route('admin.tickets.index')
                ->with('success', "Successfully processed {$successCount} tickets.");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process bulk action: ' . $e->getMessage());
        }
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