<?php

namespace App\Interfaces;

interface TicketRepositoryInterface
{
    public function getAllTickets($filters = []);
    public function getTicketById($id);
    public function getTicketsByStatus($status);
    public function getTicketsByPriority($priority);
    public function createTicket($data);
    public function updateTicket($id, $data);
    public function deleteTicket($id);
    public function addMessage($ticketId, $data);
    public function changeStatus($id, $status);
    public function getTicketStats();
    public function getRecentTickets($limit = 10);
    public function getUserTickets($userId);
}