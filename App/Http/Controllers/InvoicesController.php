<?php
namespace Modules\Invoices\App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Invoices\App\Models\Invoice;
use Modules\Invoices\App\Models\InvoiceItem;
use Modules\Invoices\App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Modules\Clients\App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use DateTime;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public $_aimx = [
        'name' => 'Invoices', // Section name
        'title' => 'Invoices', // Header title
        'description' => 'invoice list',

        'icon' => 'ti ti-device-ipad-horizontal-dollar',
        'color' => 'purple',

        'module' => 'invoices',
        'code' => 'invoices', // List or Porfile main object
        'urlBase' => 'invoices', // URI #1
        'jsFiles' => ['/assets/js/aim-list.js'],
        'cssFiles' => [],
    ];

    public $_listPerPageDefault = 30;
    public $_listSortModeDefault = "asc";
    public function _initListModel()
    {
        $this->_listModel = new Invoice;
        
    }
  
    /**
     * Display a listing of the resource.
     */
  
    public function list(Request $request)
    {
        $auth = $this->auth($this->_aimx['module'].".access_".$this->_aimx['code']);
        if (!$auth['ok']) { return $auth['x']; }
        $this->_buildData($request); 
        $this->_listOnRun();
        
        $statusTypes = Invoice::statusTypes();

    return view($this->_aimx['module'].'::'.$this->_aimx['code'].'_list', array_merge($this->page, [
        'statusTypes' => $statusTypes, 
    ]));

    }

    public function import(Request $request)
    {
        return view($this->_aimx['module'].'::'.$this->_aimx['code'].'_import', $this->page);
    }


    public function onDeleteItem(Request $request)
    {
        $id = $request->get('id');
        $invoiceItem = InvoiceItem::find($id);
        $discountTypes = Invoice::discountTypes();
        $invoiceItems = InvoiceItem::where('invoice_id', $id)->get();
                $vatArray = [
                    '21' => []
                ];
                foreach ($invoiceItems as $item) {
                    $vatArray[$item->vat][$item->id] = [
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'discount' => $item->discount
                    ];
                }
                
                foreach ($vatArray as $key => $value) {
                    $pvnTotal = 0;
                    foreach ($value as $item) {
                        $pvnTotal += ($item['quantity'] * $item['price'] - $item['discount']) * (intval($key) * 0.01);
                    }
                    $vatArray[$key]['pvnTotal'] = $pvnTotal;
                }
                $payments = Payment::where('invoice_id', $id)->get();
                $totalPaid = $payments->sum('amount') ?? 0;
        if ($invoiceItem) {
            $clientId = $invoiceItem->client_id;
            $invoiceItem->delete();
            $invoiceItem = InvoiceItem::where('client_id', $clientId)->get();
            $discountTypes = Invoice::discountTypes();
                
            return [
                '#profile-overview' => view('invoices::invoices_profile_items', [
                    'invoiceItems' => $invoiceItem, 
                    'discountTypes' => $discountTypes,
                    'vatArray' => $vatArray,
                    'totalPaid' => $totalPaid
                    ])->render(),
                '#aim-toast' => $this->toast('InvoiceItem deleted successfully.', 'success')['#aim-toast'],
            ];
        }
    }


    public function onDeletePayment(Request $request)
    {

        $paymentId = $request->get('id');
        $payment = Payment::find($paymentId);
        if (!$payment) {
            return $this->toast('Payment not found!', 'danger');
        }
        $payment->delete();

        $payments = Payment::all(); 
        $paymentStatuses = Invoice::paymentStatuses();

        $view = view('invoices::invoices_profile_payments', [
            'payments'=> $payments,
            'paymentStatuses' => $paymentStatuses,
            'aimx' => $this->_aimx
        ])->render();
        return [ 
            "#profile-overview" => $view,
            '#aim-toast' => $this->toast('Payment deleted successfully!', 'success')['#aim-toast'],
            
        ];
    }


    public function onOpenModal(Request $request)
    {

        $paymentId = $request->input('id');
        $payment = Payment::find($paymentId);
        
        $paymentStatuses = Invoice::paymentStatuses(); 
        
        $view = view('invoices::invoices_payments_modal', [
            'payment' => $payment,
            'paymentStatuses' => $paymentStatuses,
        ])->render();
        
        return ['#aim-modal' => $view];
    }
    
    public function onUpdatePayment(Request $request)
    {

        $id = $request->input('id');
        $payment = Payment::find($id);
        if (!$payment) {
            return $this->toast('Client payment not provided.', 'error');
        }

        $validator = Validator::make($request->input(), [
            'date' => 'required|date|max:255',
            'amount' => 'required|string|max:255',
        ]);
      
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return $this->toast($error, 'error');
            }
        }

        $payment->type = $request->input('type');
        $payment->date = $request->input('date');
        $payment->amount = $request->input('amount');

        $payment->save();
        $payments = Payment::all();
        $paymentStatuses = Invoice::paymentStatuses();
        $View = view('invoices::invoices_profile_payments', [
            'payments'=> $payments,
            'paymentStatuses' => $paymentStatuses,
            'aimx' => $this->_aimx
        ])->render();
        return [ 
            "#profile-overview" => $View,
            '#aim-toast' => $this->toast('Payment Updated   Successfully.', 'success')['#aim-toast'],
            '#aim-script' => "<script>$(document).ready(function() { $('#invoices_payments_modal').modal('hide'); });</script>"
        ];
 

    }
   


    public function onOpenCreateModal(Request $request)
    {
        $paymentId = $request->input('id');
        $payment = Payment::find($paymentId);
        
        $view = view('invoices::invoices_payments_create_modal', ['payment' => $payment])->render();
        
        return ['#aim-modal' => $view];
    }
    public function profile(Request $request, $id, $tab = "overview")
    {
        $auth = $this->auth("invoices.access_invoices", function() use ($id) { return Invoice::find($id); });
        if (!$auth['ok']) { return $auth['x']; }
        $invoice = $auth['item'];
        $viewData = [
            "invoice" => $invoice
        ];
        $clients = Client::all();

        $payments = Payment::where('invoice_id', $id)->get();
        $paymentStatuses = Invoice::paymentStatuses();
        $discountTypes = Invoice::discountTypes();
        $invoiceItems = InvoiceItem::where('invoice_id', $id)->get();
        //summa
        $totalPaid = $payments->sum('amount') ?? 0;
        $totalPrice = $invoice->items()->sum('in_total') ?? 0;
        $dueAmount = $totalPrice - $totalPaid;
        $paidPercentage = $totalPrice > 0 ? ($totalPaid / $totalPrice) * 100 : 0;
        $formattedPaidPercentage = number_format($paidPercentage, 1);

        $statusTypes = Invoice::statusTypes();

        //date
        $dueDate = new DateTime($invoice->due_at);
        $currentDate = new DateTime();
        $interval = $currentDate->diff($dueDate);
        $daysLeft = $interval->format('%R%a');

            
        $tabs = ["items", "details", "payments"];
        if (!in_array($tab, $tabs)) { $tab = "items"; }
        
        if ($tab == "payments") {
            $payments = Payment::where('invoice_id', $id)->get();
            $paymentStatuses = Invoice::paymentStatuses();
        } elseif ($tab == "items") {
            $discountTypes = Invoice::discountTypes();
            $invoiceItems = InvoiceItem::where('invoice_id', $id)->get();
            $vatArray = [
                '21' => []
            ];
            foreach ($invoiceItems as $item) {
                $vatArray[$item->vat][$item->id] = [
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'discount' => $item->discount
                ];
            }
                
            foreach ($vatArray as $key => $value) {
                $pvnTotal = 0;
                foreach ($value as $item) {
                    $pvnTotal += ($item['quantity'] * $item['price'] - $item['discount']) * (intval($key) * 0.01);
                }
                $vatArray[$key]['pvnTotal'] = $pvnTotal;
            }
                
        } elseif ($tab == "details") {


        }


        $items = $invoice->items();
        
            
        $this->_aimx['title'] = $invoice->title;     

        $userList = User::select(['id', 'name'])->get();
        return view('invoices::invoices_profile',  [
            'tab' => $tab,
            'invoice' => $invoice,
            'aimx' => $this->_aimx,
            'statusTypes' => $statusTypes,
            'userList' => $userList,
            'clients' => $clients,
            'payments' => $payments,
            'items' => $items,
            'invoiceItems' => $invoiceItems,
            //summa
            'totalPaid' => $totalPaid,
            'totalPrice' => $totalPrice,
            'dueAmount' => $dueAmount,
            'paidPercentage' => $paidPercentage,
            'paymentStatuses' => $paymentStatuses,
            //discount
            'discountTypes' => $discountTypes,
            compact('tab', 'invoice', 'payments', 'items'),
            'daysLeft' => $daysLeft,

            'vatArray' => isset($vatArray) ? $vatArray : null,
        ]);
    }
    public function onCreatePayment(Request $request)
    {
        $invoiceId = $request->args[0] ?? null; 
        $id = $request->input('invoice_id'); 
        $auth = $this->auth("invoices.manage_invoices", function () use ($invoiceId) {
            return Invoice::find($invoiceId);
        });

        if (!$auth['ok']) {
            return $auth['x'];
        }    

        $invoice = $auth['item'];
        

        $payment = new Payment;
        $payment->client_id = $invoice->client_id;
        $payment->invoice_id = $invoice->id;
        $payment->type = $request->input('type');
        $payment->amount = $request->input('amount');
        $payment->date = $request->input('date');
        $payment->save();


        $payments = Payment::where('invoice_id', $id)->get();
        $paymentStatuses = Invoice::paymentStatuses();
        
        $view = view('invoices::invoices_profile_payments', [
            'payments'=> $payments,
            'paymentStatuses' => $paymentStatuses,
            'aimx' => $this->_aimx
        ])->render();

        return [ 
            "#profile-overview" => $view,
            '#aim-toast' => $this->toast('Payment created successfully!', 'success')['#aim-toast'],
            '#aim-script' => "<script>$(document).ready(function() { $('#invoices_payments_create_modal').modal('hide'); });</script>"];
    }

    public function onCreateItems(Request $request)
    {
        
            
        $invoiceId = $request->args[0] ?? null; 
        $auth = $this->auth("invoices.manage_invoices", function () use ($invoiceId) {
            return Invoice::find($invoiceId);
        });

        if (!$auth['ok']) {
            return $auth['x'];
        }

        $invoice = $auth['item'];
        $invoiceItems = InvoiceItem::where('invoice_id', $invoiceId)->get();
        $discountTypes = Invoice::discountTypes();
        $invoiceItem = new InvoiceItem;
        $invoiceItem->invoice_id = $invoiceId;
        $invoiceItem->save();
                    $vatArray = [
                        '21' => []
                    ];
                    foreach ($invoiceItems as $item) {
                        $vatArray[$item->vat][$item->id] = [
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'discount' => $item->discount
                        ];
                    }
                    
                    foreach ($vatArray as $key => $value) {
                        $pvnTotal = 0;
                        foreach ($value as $item) {
                            $pvnTotal += ($item['quantity'] * $item['price'] - $item['discount']) * (intval($key) * 0.01);
                        }
                        $vatArray[$key]['pvnTotal'] = $pvnTotal;
                    }

        $payments = Payment::where('invoice_id', $invoiceId)->get();
        $totalPaid = $payments->sum('amount') ?? 0;

        $view = view('invoices::invoices_profile_items', [
            'invoiceItems' => $invoiceItems,
            'discountTypes' => $discountTypes,
            'vatArray' => isset($vatArray) ? $vatArray : null,
            'aimx' => $this->_aimx,
            'totalPaid' => $totalPaid
        ])->render();

        return [
            "#profile-overview" => $view,
            '#aim-toast' => $this->toast('Item created Successfully.', 'success')['#aim-toast'],
            '#aim-script' => "<script>$(document).ready(function() { $('#invoiceCreateItemModal').modal('hide'); });</script>"
        ];
    }


    public function onUpdateItem(Request $request)
    {

        $id = $request->args[0] ?? null; 
        $auth = $this->auth("invoices.manage_invoices", function () use ($id) { return Invoice::find($id); });
        if (!$auth['ok']) { return $auth['x']; }

        $payments = Payment::where('invoice_id', $id)->get();
        $totalPaid = $payments->sum('amount') ?? 0;
        $discountTypes = Invoice::discountTypes();
        $invoiceItemId = $request->input('invoiceItemId');
        $input = $request->input();
        
        $invoicesItemsIds = $input['ids'];
        $prices = $input['price'];
        $quantities = $input['quantity'];
        $discounts = $input['discount'];
        $discountTypes = $input['discount_types'];
        $vats = $input['vat'];

        $invoiceItems = InvoiceItem::whereIn('id', $invoicesItemsIds)->get();


        foreach ($invoiceItems as $invoiceItem) {
            $invoiceItemId = $invoiceItem->id;

            if (isset($prices[$invoiceItemId]) && isset($quantities[$invoiceItemId]) && isset($discounts[$invoiceItemId])) {
                $price = (float) $prices[$invoiceItemId];
                $quantity = (float) $quantities[$invoiceItemId];
                $discount = (float) $discounts[$invoiceItemId];
                $vat = (float) $vats[$invoiceItemId];
                $discountType = $discountTypes[$invoiceItemId] ?? null;


                if ($discountType === 'main' || $discountType === 'percent') {
                    $inTotal = $price * $quantity * (1 - $discount / 100);
                } else if ($discountType === 'total') {
                    $inTotal = ($price * $quantity) - $discount;
                } else if ($discountType === 'item') {
                    $inTotal = ($price - $discount) * $quantity;
                } else if ($discountType === 'price') {
                    $inTotal = $discount * $quantity;
                } else {
                    $inTotal = $price * $quantity; 
                }

                $invoiceItem->price = $price;
                $invoiceItem->quantity = $quantity;
                $invoiceItem->discount = $discount;
                $invoiceItem->discount_type = $discountType;
                $invoiceItem->in_total = $inTotal;
                $invoiceItem->vat = $vat;

                $invoiceItem->save();
            }
            
        }


        $pvnRate = 21;
        $vatArray = [
            '21' => []
        ];
        foreach ($invoiceItems as $item) {
            if ($item->vat && $item->vat != $pvnRate) {
                $pvnRate = $item->vat;
            }
            $vatArray[$item->vat][$item->id] = [
                'quantity' => $item->quantity,
                'price' => $item->price,
                'discount' => $item->discount
            ];
        }
        
        foreach ($vatArray as $key => $value) {
            $pvnTotal = 0;
            foreach ($value as $item) {
                $pvnTotal += ($item['quantity'] * $item['price'] - $item['discount']) * (intval($key) * 0.01);
            }
            $vatArray[$key]['pvnTotal'] = $pvnTotal;
        }
        

        $discountTypes = Invoice::discountTypes();
        $view = view('invoices::invoices_profile_items', [
            'payments' => $payments,
            'discountTypes' => $discountTypes,
            'invoiceItems'=> $invoiceItems,
            'totalPaid' => $totalPaid,
            'vatArray' => isset($vatArray) ? $vatArray : null,
            'aimx' => $this->_aimx
        ])->render();

        return [
            "#profile-overview" => $view,
            '#aim-toast' => $this->toast('Item updated successfully.', 'success')['#aim-toast'],
            '#aim-script' => "<script>$(document).ready(function() { $('#invoiceCreateItemModal').modal('hide'); });</script>"
        ];
    }


    public function onOpenItemModal(Request $request) 
    {

        $id = $request->args[0] ?? null;
        $auth = $this->auth("invoices.access_invoices", function() use ($id) {
            return Invoice::find($id);
        });

        if (!$auth['ok']) {
            return $auth['x'];
        }

        $invoiceItemId = $request->input('id');
        $invoiceItem = InvoiceItem::find($invoiceItemId);
        $discountTypes = Invoice::discountTypes();

        $view = view('invoices::invoices_items_modal', compact('invoiceItem', 'discountTypes'))->render();
        return ['#aim-modal' => $view];
    }

    public function onOpenCreateItemModal(Request $request)
    {
        $id = $request->args[0] ?? null; 
        $auth = $this->auth("invoices.access_invoices", function() use ($id) { return Invoice::find($id); });
        if (!$auth['ok']) { return $auth['x']; }

        $invoiceItem = new InvoiceItem(); 
        $discountTypes = Invoice::discountTypes(); 

        $view = view('invoices::invoices_create_items_modal', compact('invoiceItem', 'discountTypes'))->render();
        return ['#aim-modal' => $view];
    }


    public function onSaveItem(Request $request)
    {
        $id = $request->args[0] ?? null; 
        $auth = $this->auth("invoices.manage_invoices", function() use ($id) { return Invoice::find($id); });
        if (!$auth['ok']) { return $auth['x']; }
        $invoice = $auth['item'];

        $data = $request->input('data');

        $validator = Validator::make($data, [

        ]);

        if ($validator->fails()) {
            return $this->toast("Error");
        }
        $invoice->update($data);
        return $this->toast("Done!");
       
    }


    public function onCloneInvoice(Request $request)
    {
        $id = $request->args[0] ?? null; 
        $auth = $this->auth("invoices.manage_invoices", function() use ($id) {
            return Invoice::with('items')->find($id);
        });

        if (!$auth['ok']) {
            return $auth['x'];
        }

        $invoice = $auth['item'];

        $newInvoice = $invoice->replicate();
        $newInvoice->name = $invoice->name; 
        $newInvoice->setAttribute('price_total', null);
        $newInvoice->setAttribute('discount_total', null);
        $newInvoice->setAttribute('paid', null);
        $newInvoice->save();

        foreach($invoice->items as $invoiceItem) {
            $newInvoiceItem = $invoiceItem->replicate();
            $newInvoiceItem->invoice_id = $newInvoice->id; 
            $newInvoiceItem->save();
        }

        return $this->redirect('/invoices/profile/' . $newInvoice->id );
    }

    
    public function onShowFilters()
    {
        $filters = Session::get($this->_aimx['module'].'_'.$this->_aimx['code'].'_filters', []);
        $clients = Client::all();
        $statusTypes = Invoice::statusTypes();
        $view = view($this->_aimx['module'].'::'.$this->_aimx['code'].'_list_filters', [
            'filters' => $filters,
            'clients' => $clients,
            'statusTypes' => $statusTypes,
            
        ])->render();

        return [
            '#aim-filter-body' => $view
        ];
    }

    public function onRefresh($request, $toast=null)
    {
        $auth = $this->auth($this->_aimx['module'].".access_".$this->_aimx['code']);
        if (!$auth['ok']) { return $auth['x']; }
        $post = $request->input();
        if (Arr::get($post, 'data.search_original') != Arr::get($post, 'data.search')) {
            $this->_sentToFirstPage = true;
        }
        if (Arr::get($post, 'data.page') || $this->_sentToFirstPage) {
            Session::put($this->_aimx['code'].'_list_page', $this->_sentToFirstPage ? 1 : $post['data']['page']);
        }
        $this->_buildData($request);
        $view = view('list.aim-list-page',
            [
                'user' => $auth['user'],
                'workspace' => $auth['workspace'],
                'listItems' => $this->_listItems,
                'statistics' => $this->_listStatistics,
                'data' => $this->_data,
                'filters' => $this->_filters,
                'aimx' => $this->_aimx,
                'clients' => Client::all(),
                'statusTypes' => Invoice::statusTypes(),
            ])->render();
        return [
            '#aim-list-page' => $view,
            '#aim-toast' => $toast ? $this->toast($toast)['#aim-toast'] : ''
        ];
    }

    public function onSetFilters($request)
    {
        $auth = $this->auth($this->_aimx['module'].".access_".$this->_aimx['code']);
        if (!$auth['ok']) { return $auth['x']; }

        $filters = $request->input('filters');
        $clientId = $request->input('filters.client_id');
        $statusType = $request->input('filters.status.is');
        $query = Invoice::query();
        if (!empty($clientId)) {
            $query->where('client_id', $clientId);
        }
        if (!empty($statusType)) {
            $query->where('status', $statusType);
        }
        
        $totalCount = $query->count();

        Session::put($this->_aimx['module'].'_'.$this->_aimx['code'].'_filters', $filters);
        $this->_sentToFirstPage = true;

        return $this->onRefresh($request);
    }


    public function _searchFilterScope($data, $filters)
    {
        $filterCount = 0;
        $searchFilters = [];
        
        foreach ($filters as $filterKey => $filter) {
            if (!empty($filter['like'])) {
                $searchFilters[$filterKey] = ['like', '%' . $filter['like'] . '%'];
                $filterCount++;
            } elseif (!empty($filter['is'])) {
                $searchFilters[$filterKey] = ['=', $filter['is']];
                $filterCount++;
            } elseif (!empty($filter['after'])) {
                $searchFilters[$filterKey] = ['>', $filter['after']];
                $filterCount++;
            } elseif (!empty($filter['before'])) {
                $searchFilters[$filterKey] = ['<', $filter['before']];
                $filterCount++;
            }
        }
        
        $search = $filterCount > 0 ? "" : $data['search'];
        
        return [
            "search" => $search,
            "searchFilters" => $searchFilters,
            "filters" => $filters,
            "filterCount" => $filterCount
        ];
    }


    
}