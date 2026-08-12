<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Lead;

class LeadObserver
{
    /**
     * Dipanggil setiap kali model Lead di-update.
     */
    public function updated(Lead $lead): void
    {
        // Cek jika status berubah jadi 'won' dan belum punya customer
        if ($lead->isDirty('status') && $lead->status === 'won' && ! $lead->customer) {
            Customer::create([
                'user_id' => $lead->user_id,
                'lead_id' => $lead->id,
                'company_name' => $lead->company_name,
                'contact_name' => $lead->contact_name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'status' => 'active',
                'total_lifetime_value' => $lead->opportunity_value,
            ]);
        }
    }
}
