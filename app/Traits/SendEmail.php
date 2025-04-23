<?php

namespace App\Traits;

use App\Mail\RestaurantRegistrationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

trait SendEmail
{
    public function sendEmail($details): void
    {
        Mail::to($details['email'])->send(new RestaurantRegistrationMail($details));
    }
}
