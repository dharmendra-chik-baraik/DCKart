<?php

namespace App\Services\Frontend;

use App\Interfaces\Frontend\ContactRepositoryInterface;

class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function submitContactForm($data)
    {
        // Validate data (validation should be done in controller)
        return $this->contactRepository->storeContact($data);
    }
}