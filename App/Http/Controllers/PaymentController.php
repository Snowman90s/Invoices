<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Invoices\App\Models\Invoice;
use Modules\Invoices\App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


// class InvoicesController extends Controller
// {
//     public function deletePayment(Request $request)
//     {
//         if ($request->has('deletePayment')) {
//             $request->input('selected_payments')
        

//         return redirect()->route('your.route.name');
//     }
// }