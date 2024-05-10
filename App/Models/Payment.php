<?php

namespace Modules\Invoices\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use modules\Clients\App\Models\Client;

class Payment extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($payment) {
            $invoice = $payment->invoice;

            $invoice->updateAmountPaid();
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
