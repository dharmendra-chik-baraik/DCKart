<?php

namespace App\Services;

use App\Interfaces\TicketRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    protected $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function getAllTickets($filters = [])
    {
        return $this->ticketRepository->getAllTickets($filters);
    }

    public function getTicket($id)
    {
        return $this->ticketRepository->getTicketById($id);
    }

    public function createTicket($data)
    {
        return $this->ticketRepository->createTicket($data);
    }

    public function updateTicket($id, $data)
    {
        return $this->ticketRepository->updateTicket($id, $data);
    }

    public function deleteTicket($id)
    {
        return $this->ticketRepository->deleteTicket($id);
    }

    public function addMessage($ticketId, $data, $attachment = null)
    {
        $messageData = [
            'sender_id' => $data['sender_id'],
            'message' => $data['message'],
        ];

        if ($attachment) {
            $messageData['attachment'] = $this->storeAttachment($attachment);
        }

        return $this->ticketRepository->addMessage($ticketId, $messageData);
    }

    public function changeStatus($id, $status)
    {
        return $this->ticketRepository->changeStatus($id, $status);
    }

    // REMOVED: assignToUser method

    public function getTicketStats()
    {
        return $this->ticketRepository->getTicketStats();
    }

    public function getRecentTickets($limit = 10)
    {
        return $this->ticketRepository->getRecentTickets($limit);
    }

    // REMOVED: getAdminUsers method

    public function bulkUpdate($ticketIds, $data)
    {
        $results = [];
        foreach ($ticketIds as $ticketId) {
            try {
                $ticket = $this->ticketRepository->updateTicket($ticketId, $data);
                $results[] = [
                    'id' => $ticketId,
                    'status' => 'success',
                    'ticket' => $ticket
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'id' => $ticketId,
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        }
        return $results;
    }

    private function storeAttachment($file)
    {
        return $file->store('ticket-attachments', 'public');
    }

    public function getAttachmentUrl($filename)
    {
        return Storage::url($filename);
    }

    public function getTicketResponseTime($ticketId)
    {
        $ticket = $this->ticketRepository->getTicketById($ticketId);
        
        if ($ticket->messages->count() > 1) {
            $firstUserMessage = $ticket->messages->first();
            $firstAdminResponse = $ticket->messages->where('sender_id', '!=', $ticket->user_id)->first();
            
            if ($firstAdminResponse) {
                return $firstUserMessage->created_at->diffInHours($firstAdminResponse->created_at);
            }
        }
        
        return null;
    }
}