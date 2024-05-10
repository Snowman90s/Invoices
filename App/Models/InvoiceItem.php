<?php

namespace Modules\Invoices\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($item) {
            $invoice = $item->invoice;

            $invoice->updatePriceTotal();
        });
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
