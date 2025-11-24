<?php

namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\ContactRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactRepository implements ContactRepositoryInterface
{
    public function storeContact($data)
    {
        // Here you can store contact form data in database if needed
        // For now, we'll just send email
        
        // Send email to admin
        Mail::to(config('mail.admin_email', 'admin@dckart.com'))->send(new ContactFormMail($data));
        
        return true;
    }
}