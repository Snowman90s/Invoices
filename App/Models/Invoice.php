<?php

namespace Modules\Invoices\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Clients\App\Models\Client;
use App\Classes\AimModel;


class Invoice extends Model
{
    use HasFactory, AimModel;


    protected $fillable = [
        'name',
        'status',
        'price_total',
        'paid',
        'currency',
        'issued_at',
        'due_at',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updatePriceTotal()
    {
        $this->price_total = $this->items()->sum('in_total') ?? 0;
        $this->save();
    }

    public function updateAmountPaid()
    {
        $this->paid = $this->payments()->sum('amount') ?? 0;
        $this->save();
    }


    public static function paymentStatuses()
    {
        return  [
            'bank' => ['type' => 'Bank', 'icon' => 'ti ti-building-bank', 'color' => 'light'],
            'card' => ['type' => 'Card', 'icon' => 'ti ti-credit-card', 'color' => 'primary'],
            'cash' => ['type' => 'Cash', 'icon' => 'ti ti-cash-banknote', 'color' => 'primary'],
            'debit' => ['type' => 'Debit', 'icon' => 'ti ti-credit-card', 'color' => 'primary'],
            'credit' => ['type' => 'Credit', 'icon' => 'ti ti-credit-card', 'color' => 'primary'],
        ];
    }

    public static function discountTypes()
    {
        return [
            'none' => ['name' => 'None'],
            'main' => ['name' => 'Main'],
            'percent' => ['name' => 'Percent'],
            'total' => ['name' => 'Total'],
            'item' => ['name' => 'Item'], 
            'price' => ['name' => 'Price'],
        ];
    }

    public static function statusTypes()
    {
        return [
            'draft' => ['title' => 'Draft', 'icon' => 'ti ti-user', 'color' => 'secondary-subtle', 'text-color' => 'muted'],
            'sent' => ['title' => 'Sent', 'icon' => 'ti ti-send', 'color' => 'light', 'text-color' => 'primary'],
            'viewed' => ['title' => 'Viewed', 'icon' => 'ti ti-eye', 'color' => 'light', 'text-color' => 'primary'],
            'partlyPaid' => ['title' => 'Partly paid', 'icon' => 'ti ti-cash-banknote', 'color' => 'success-subtle', 'text-color' => 'success'],
            'paid' => ['title' => 'Paid', 'icon' => 'ti ti-check', 'color' => 'success-subtle', 'text-color' => 'success'],
            'canceled' => ['title' => 'Canceled', 'icon' => 'ti ti-x', 'color' => 'danger-subtle', 'text-color' => 'danger'],
            'partlyRefunded' => ['title' => 'Partly refunded', 'icon' => 'ti ti-receipt-refund', 'color' => 'warning-subtle', 'text-color' => 'warning'],
            'refunded' => ['title' => 'Refunded', 'icon' => 'ti ti-receipt-refund', 'color' => 'warning-subtle', 'text-color' => 'warning'],
        ];
    }
}

