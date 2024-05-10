<?php

namespace Modules\Invoices\App\Classes;

use Modules\Clients\App\Models\Client;
use Modules\Invoices\App\Models\Invoice;
use Illuminate\Support\Facades\Validator;
use Modules\Invoices\App\Models\InvoiceItem;
use Modules\Invoices\App\Models\Payment;
use Modules\Scanner\App\Classes\ScannerWidgetClass;

class InvoiceScannerWidget extends ScannerWidgetClass
{

    private function getItemsProfile($widget, $invoice, $message = null)
    {
        $statusTypes = Invoice::statusTypes();
        $clients = Client::all();
        $invoiceItems = $invoice->items;

        return [
            'ok' => true,
            'alarm' => null,
            'toast' => $message,
            'content' => view(
                'scanner::invoices.scanner_invoices_widget',
                ['invoice' => $invoice, 'statusTypes' => $statusTypes, 'clients' => $clients, 'widget' => $widget]
            )->render()
                . view(
                    'scanner::invoices.scanner_invoices_header',
                    ['invoice' => $invoice, 'widget' => $widget]
                )->render()
                . view(
                    'scanner::invoices.scanner_invoices_items',
                    ['invoice' => $invoice, 'invoiceItems' => $invoiceItems, 'widget' => $widget]
                )->render(),
        ];
    }

    private function getPaymentsProfile($widget, $invoice, $message = null)
    {
        $payments = $invoice->payments;
        $statusTypes = Invoice::statusTypes();
        $clients = Client::all();

        return [
            'ok' => true,
            'alarm' => null,
            'toast' => $message,
            'content' => view(
                'scanner::invoices.scanner_invoices_widget',
                ['invoice' => $invoice, 'statusTypes' => $statusTypes, 'clients' => $clients, 'widget' => $widget]
            )->render()
                . view(
                    'scanner::invoices.scanner_invoices_header',
                    ['invoice' => $invoice, 'widget' => $widget]
                )->render()
                . view(
                    'scanner::invoices.scanner_invoices_payments',
                    ['invoice' => $invoice, 'payments' => $payments, 'widget' => $widget]
                )->render(),
        ];
    }

    public function scan($codePattern, $user, $widget)
    {
        if (!$this->hasPermission($user, 'invoices', 'access', 'invoices')) {
            return $this->error('permission');
        }

        $parts = explode('-', $codePattern);

        // Get the last part of the pattern
        $ending = array_pop($parts);

        if (strpos($ending, 'V') === 0) {
            // If the last part starts with V
            $ending = '-' . $ending;
        } else {
            // If the last part does not start with V
            array_push($parts, $ending);
            $ending = '';
        }

        $codePattern = implode('-', $parts);

        $invoice = Invoice::where('name', 'LIKE', "%$codePattern%$ending")->first();

        if (!$invoice) {
            return $this->error('not_found');
        }

        return $this->getitemsProfile($widget, $invoice);
    }

    public function action_onCloneInvoice($widget, $options)
    {
        $invoice = Invoice::find($options['request']['invoice_id']);

        if (!$invoice) {
            return $this->error('not_found');
        }

        $newInvoice = $invoice->replicate();
        $parts = explode('-', strtoupper($invoice->name));
        $parts = array_slice($parts, 0, 2);
        $parts[] = strtoupper(bin2hex(random_bytes(6)));
        $newInvoice->name = implode('-', $parts);
        $newInvoice->status = 'draft';
        $newInvoice->paid = 0;
        $newInvoice->is_paid = 0;
        $newInvoice->save();

        foreach ($invoice->items as $item) {
            $newItem = $item->replicate();
            $newItem->invoice_id = $newInvoice->id;
            $newItem->save();
        }

        return $this->getItemsProfile($widget, $invoice, "Invoice cloned successfully");
    }

    public function action_onDelete($widget, $options)
    {
        $invoice = Invoice::find($options['request']['invoice_id']);

        if (!$invoice) {
            return $this->error('not_found');
        }

        foreach ($invoice->items as $item) {
            $item->delete();
        }

        foreach ($invoice->payments as $payment) {
            $payment->delete();
        }

        $invoice->delete();

        return [
            'ok' => true,
            'alarm' => null,
            'toast' => "Invoice deleted successfully",
            'content' => view('scanner::scanner_before_scan_widget')->render(),
        ];
    }

    public function action_onSaveInvoice($widget, $options)
    {

        $invoice = Invoice::find($options['request']['invoice_id']);

        if (!$invoice) {
            return $this->error('not_found');
        }

        if ($options['request']['data']['client_id'] == "") {
            return $this->error('validation');
        }

        $validator = Validator::make($options['request']['data'], [
            'client_id' => 'required',
            'status' => 'required',
        ]);

        $invoice->update([
            'status' => $validator->validated()['status'],
            'client_id' => $validator->validated()['client_id'],
        ]);

        return $this->getItemsProfile($widget, $invoice, "Invoice saved successfully");
    }

    public function action_onShowItems($widget, $options)
    {
        $invoice = Invoice::find($options['request']['invoice_id']);

        if (!$invoice) {
            return $this->error('not_found');
        }

        return $this->getItemsProfile($widget, $invoice);
    }

    public function action_onOpenCreateItemModal($widget, $options)
    {
        $invoice = Invoice::find($options['request']['invoice_id']);

        if (!$invoice) {
            return $this->error('not_found');
        }

        return [
            'ok' => true,
            'alarm' => null,
            'toast' => null,
            'aim-modal' => view(
                'invoices::invoices_create_items_modal',
                ['invoice' => $invoice, 'widget' => $widget]
            )->render(),
            'content' => null,
        ];
    }

    public function action_onCreateItems($widget, $options)
    {
        $invoice = Invoice::find($options['request']['invoice_id']);

        if (!$invoice) {
            return $this->error('not_found');
        }

        $validator = Validator::make($options['request'], [
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'price_total' => 'required',
            'in_total' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error('validation');
        }

        $invoiceItem = new InvoiceItem;
        $invoiceItem->client_id = $invoice->client_id;
        $invoiceItem->invoice_id = $invoice->id;
        $invoiceItem->name = $options['request']['name'];
        $invoiceItem->price = $options['request']['price'];
        $invoiceItem->price_total = $options['request']['price_total'];
        $invoiceItem->in_total = $options['request']['in_total'];
        $invoiceItem->quantity = $options['request']['quantity'];
        $invoiceItem->save();

        return $this->getItemsProfile($widget, $invoice, "Item created successfully");
    }

    public function action_onDeleteItem($widget, $options)
    {
        $invoiceItem = InvoiceItem::find($options['request']['item_id']);

        if (!$invoiceItem) {
            return $this->error('not_found');
        }

        $invoice = $invoiceItem->invoice;
        $invoiceItem->delete();

        return $this->getItemsProfile($widget, $invoice, "Item deleted successfully");
    }

    public function action_onOpenItemModal($widget, $options)
    {
        $invoiceItem = InvoiceItem::find($options['request']['invoiceItemId']);

        if (!$invoiceItem) {
            return $this->error('not_found');
        }

        return [
            'ok' => true,
            'alarm' => null,
            'toast' => null,
            'aim-modal' => view(
                'invoices::invoices_items_modal',
                ['invoiceItem' => $invoiceItem, 'widget' => $widget]
            )->render(),
            'content' => null,
        ];
    }

    public function action_onCreateItem($widget, $options)
    {
        $invoiceItem = InvoiceItem::find($options['request']['invoiceItemId']);

        if (!$invoiceItem) {
            return $this->error('not_found');
        }

        $validator = Validator::make($options['request'], [
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'price_total' => 'required',
            'in_total' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error('validation');
        }

        $invoiceItem->name = $options['request']['name'];
        $invoiceItem->price = $options['request']['price'];
        $invoiceItem->price_total = $options['request']['price_total'];
        $invoiceItem->in_total = $options['request']['in_total'];
        $invoiceItem->quantity = $options['request']['quantity'];
        $invoiceItem->save();

        return $this->getItemsProfile($widget, $invoiceItem->invoice, "Item updated successfully");
    }

    public function action_onShowPayments($widget, $options)
    {
        $invoice = Invoice::find($options['request']['invoice_id']);

        if (!$invoice) {
            return $this->error('not_found');
        }

        return $this->getPaymentsProfile($widget, $invoice);
    }

    public function action_onOpenCreatePaymentModal($widget, $options)
    {
        $invoice = Invoice::find($options['request']['invoice_id']);

        if (!$invoice) {
            return $this->error('not_found');
        }

        return [
            'ok' => true,
            'alarm' => null,
            'toast' => null,
            'aim-modal' => view(
                'invoices::invoices_payments_create_modal',
                ['invoice' => $invoice, 'widget' => $widget]
            )->render(),
            'content' => null,
        ];
    }

    public function action_onCreatePayment($widget, $options)
    {

        $invoice = Invoice::find($options['request']['invoice_id']);

        if (!$invoice) {
            return $this->error('not_found');
        }

        $validator = Validator::make($options['request'], [
            'amount' => 'required',
            'date' => 'required',
        ]);


        if ($validator->fails()) {
            return $this->error('validation');
        }

        $payment = new Payment;
        $payment->client_id = $invoice->client_id;
        $payment->invoice_id = $invoice->id;
        $payment->type = $options['request']['type'];
        $payment->date = $options['request']['date'];
        $payment->amount = $options['request']['amount'];
        $payment->save();

        return $this->getPaymentsProfile($widget, $invoice, "Payment created successfully");
    }

    public function action_onDeletePayment($widget, $options)
    {
        $payment = Payment::find($options['request']['payment_id']);

        if (!$payment) {
            return $this->error('not_found');
        }

        $invoice = $payment->invoice;
        $payment->delete();

        return $this->getPaymentsProfile($widget, $invoice, "Payment deleted successfully");
    }

    public function action_onOpenPaymentModal($widget, $options)
    {
        $payment = Payment::find($options['request']['payment_id']);

        if (!$payment) {
            return $this->error('not_found');
        }

        $paymentStatuses = [
            'bank',
            'card',
            'cash',
            'debit',
            'credit'
        ];

        return [
            'ok' => true,
            'alarm' => null,
            'toast' => null,
            'aim-modal' => view(
                'invoices::invoices_payments_modal',
                ['payment' => $payment, 'paymentStatuses' => $paymentStatuses, 'widget' => $widget]
            )->render(),
            'content' => null,
        ];
    }

    public function action_onUpdatePayment($widget, $options)
    {
        $payment = Payment::find($options['request']['id']);

        if (!$payment) {
            return $this->error('not_found');
        }

        $validator = Validator::make($options['request'], [
            'amount' => 'required',
            'date' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error('validation');
        }

        $payment->type = $options['request']['type'];
        $payment->date = $options['request']['date'];
        $payment->amount = $options['request']['amount'];
        $payment->save();

        $invoice = $payment->invoice;

        return $this->getPaymentsProfile($widget, $invoice, "Payment updated successfully");
    }
}
