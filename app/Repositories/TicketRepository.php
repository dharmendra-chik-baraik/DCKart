<?php

namespace App\Repositories;

use App\Interfaces\TicketRepositoryInterface;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketRepository implements TicketRepositoryInterface
{
    public function getAllTickets($filters = [])
    {
        $query = Ticket::with([
            'user:id,name,email,role',
            'vendor:id,shop_name,user_id',
            'vendor.user:id,name,email',
            'messages'
        ])->orderBy('created_at', 'desc');

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority']) && $filters['priority'] !== '') {
            $query->where('priority', $filters['priority']);
        }

        // REMOVED: assigned_to filter

        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('vendor', function($q) use ($search) {
                      $q->where('shop_name', 'like', "%{$search}%");
                  });
            });
        }

        if (isset($filters['date_from']) && $filters['date_from'] !== '') {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to'] !== '') {
            $query->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }

        return $query->paginate(20);
    }

    public function getTicketById($id)
    {
        return Ticket::with(['user', 'vendor', 'messages', 'messages.sender'])
            ->findOrFail($id);
    }

    public function getTicketsByStatus($status)
    {
        return Ticket::with(['user', 'vendor'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function getTicketsByPriority($priority)
    {
        return Ticket::with(['user', 'vendor'])
            ->where('priority', $priority)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function createTicket($data)
    {
        return Ticket::create($data);
    }

    public function updateTicket($id, $data)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($data);
        return $ticket;
    }

    public function deleteTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return $ticket;
    }

    public function addMessage($ticketId, $data)
    {
        $ticket = Ticket::findOrFail($ticketId);
        
        $message = TicketMessage::create([
            'ticket_id' => $ticketId,
            'sender_id' => $data['sender_id'],
            'message' => $data['message'],
            'attachment' => $data['attachment'] ?? null,
        ]);

        // Update ticket updated_at timestamp
        $ticket->touch();

        return $message;
    }

    public function changeStatus($id, $status)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update(['status' => $status]);
        return $ticket;
    }

    // REMOVED: assignToUser method

    public function getTicketStats()
    {
        return [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
            'urgent' => Ticket::where('priority', 'urgent')->count(),
            'high' => Ticket::where('priority', 'high')->count(),
            // REMOVED: unassigned count
        ];
    }

    public function getRecentTickets($limit = 10)
    {
        return Ticket::with(['user', 'vendor'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getUserTickets($userId)
    {
        return Ticket::with(['user', 'vendor'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
}