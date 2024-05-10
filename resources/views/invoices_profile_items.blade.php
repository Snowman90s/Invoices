<div id="profile-overview" class="card">
  <div class="card-body">
       <div x-data="{ item: {}}" >
            <div class="row">
                <form data-request="{{ isset($widget) ? 'onAction' : 'onCreateItems' }}">
                    <div class="col-md-12 mb-1 text-end">
                        <button type="submit" class="btn bg-primary-subtle me-3" >
                            <i class="ti ti-plus"></i>
                            Create Item
                        </button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
            <form data-request="{{ isset($widget) ? 'onAction' : 'onUpdateItem' }}">
                <table class="table table-nowrap table-hover table-align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th class="align-middle" style="width: 30px">
                                <input type="checkbox" class="form-check-input" id="list-select-all">
                            </th>
                            <th >
                                {{ 'Name' }}
                            </th>
                            <th width="150px">
                                {{ 'Price' }}
                            </th>
                            <th width="150px">
                                {{ 'Quantity' }}
                            </th> 
                            <th width="150px">
                                {{ 'Discount Type' }}
                            </th>
                            <th width="40px">
                                {{ 'Discount' }}
                            </th>
                            <th width="25px">
                                {{ 'VAT' }}
                            </th>
                            <th width="150px">
                                {{ 'Total' }}
                            </th>
                            <th class="text-end" width="20px">
                                {{ 'Options' }}
                            </th>
                        </tr>
                    </thead>
                
                    <tbody class="">
                        @foreach ($invoiceItems as $invoiceItem)
                        <tr x-data="{ item:{{ $invoiceItem }}}" id="list-row-{{ $invoiceItem->id }}" data-request-data="invoiceItem: {{ $invoiceItem->id }}"
                            class="align-middle">
                            <td class="align-middle">
                                <input type="checkbox" class="form-check-input" id="check-{{ $invoiceItem->id }} ">
                                <input type="text" name="ids[{{ $invoiceItem->id }}]" value="{{ $invoiceItem->id }}" hidden>
                            </td>
                            <td><b>{{ $invoiceItem->name }}</b></td>
                            <td>
                                <input type="text" class="form-control p-1" placeholder="Price" name="price[{{$invoiceItem->id}}]" value="{{ $invoiceItem->price }}" x-model="item.price">
                            </td>
                            <td>
                                <input type="text" class="form-control p-1" placeholder="Quantity" name="quantity[{{$invoiceItem->id}}]" value="{{ $invoiceItem->quantity }}" x-model="item.quantity">
                            </td>
                            <td>
                                <select class="form-select p-1" name="discount_types[{{ $invoiceItem->id }}]" x-model="item.discount_type">
                                    @foreach ($discountTypes as $value => $discountType)
                                        <option value="{{ $value }}" :selected="item.discount_type === '{{ $value }}'" >
                                            {{ $discountType['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control p-1" placeholder="Discount" name="discount[{{$invoiceItem->id}}]" value="{{ $invoiceItem->discount }}" x-model="item.discount">
                            </td>
                            <td>
                            <input type="text" class="form-control p-1" placeholder="Vat" name="vat[{{$invoiceItem->id}}]" value="{{ $invoiceItem->vat }}" x-model="item.vat">
                            </td>
                            <td>
                                
                                    <b x-model="item.in_total" x-text="
                                (function(item) {
                                    if (item.discount_type === 'main' || item.discount_type === 'percent') {
                                        return item.price * item.quantity * (1 - item.discount / 100);
                                    } else if (item.discount_type === 'total') {
                                        return (item.price * item.quantity) - item.discount;
                                    } else if (item.discount_type === 'item') {
                                        return (item.price - item.discount) * item.quantity;
                                    } else if (item.discount_type === 'price') {
                                        return item.discount * item.quantity;
                                    } else {
                                        return item.price * item.quantity; // No discount
                                    }
                                })(item)
                            "></b>

                            </td>

                            <td class="text-center">
                                <a href="#" class="btn bg-danger-subtle text-danger btn-sm" data-request="onDeleteItem"
                                    data-request-confirm="Are you sure you want to delete this Item?"
                                    data-request-data="id: {{ $invoiceItem->id }}" data-bs-toggle="tooltip" title="Delete Item">
                                    <i class="ti ti-trash "></i>
                                </a>
                        </tr>
                    </tbody>
                    {{-- For scanner --}}
                    @if (isset($widget))
                            <input id="widget_id" name="widget_id" value="{{ $widget->id }}" type="text"hidden>
                            <input id="action" name="action" value="onCreateItems" type="text" hidden>
                    @endif
                
                    @endforeach
                    
                  
                </table>
                
         
            </div>

            <div class="row col-md-12">
                <div class="col-md-6">
                    <div class="row">
                        
                        <div class="note-placeholder">
                            <div class="mb-1">
                                Note
                            </div>
                            <textarea class="form-control" rows="4" placeholder="Write your note here..."></textarea>
                        </div>
                    </div>
                </div>
            

                <div class="col-md-6">
                <div>
                <div class="row p-1">
                    <div class="col-md-7 text-end">Total</div>
                    <div class="col-md-2 text-end"></div>
                    <div class="col-md-3 text-end">
                        <b x-text="'{{ $invoiceItems->sum(function($item) { return $item->quantity * $item->price; }) }}€'"></b>
                    </div>
                </div>
                <div class="row p-1">
                    <div class="col-md-7 text-end">Discount</div>
                    <div class="col-md-2 text-end"></div>
                    <div class="col-md-3 text-end">
                    <div x-text="'{{ $invoiceItems->reduce(function($totalDiscount, $item) {
                if ($item->discount_type === 'main' || $item->discount_type === 'percent') {
                    $totalDiscount += ($item->price * $item->quantity * ($item->discount / 100));
                } else if ($item->discount_type === 'total') {

                } else if ($item->discount_type === 'item') {
                    $totalDiscount += $item->discount * $item->quantity;
                } else if ($item->discount_type === 'price') {

                } else {

                }
                return $totalDiscount;
            }, 0) }}€'"></div>
                    </div>
                </div>
                <div class="row p-1">
                    <div class="col-md-7 text-end">In total</div>
                    <div class="col-md-2 text-end"></div>
                    <div class="col-md-3 text-end">
                    <div class="text-dark" x-text="'{{ $invoiceItems->sum(function($item) {
                if ($item->discount_type === 'main' || $item->discount_type === 'percent') {
                    return ($item->price * $item->quantity) * (1 - ($item->discount / 100));
                } else if ($item->discount_type === 'total') {
                    return $item->price * $item->quantity - $item->discount;
                } else if ($item->discount_type === 'item') {
                    return ($item->price - $item->discount) * $item->quantity;
                } else if ($item->discount_type === 'price') {
                    return $item->discount * $item->quantity;
                } else {
                    return $item->price * $item->quantity; 
                }
            }) }}€'"></div>
                    </div>
                </div>
                <div class="row p-1 mb-3">
                <div class="col-md-7 text-end">PVN</div>
                <div class="col-md-2 text-end">
                    @foreach ($vatArray as $vatKey => $vatValue)
                        <div x-text="'{{ $vatKey }}%'"></div>
                    @endforeach
                </div>
                <div class="col-md-3 text-end"> 
                    @foreach($vatArray as $vatKey => $vatValue)
                        
                        <div x-text="'{{ number_format($vatValue['pvnTotal'], 2) }}€'"></div>
                        
                    @endforeach
                    </div>
                </div>
            </div>


            
                    <div class="row p-1">
                        <div class="col-md-7 text-end">
                            <b>Kopā apmaksai</b>
                        </div>
                        <div class="col-md-2 text-end"></div>
                        <div class="col-md-3 text-end">
                        <div x-text="'{{ $invoiceItems->reduce(function($totalInTotal, $item) use ($vatArray) {
                            if ($item->discount_type === 'main' || $item->discount_type === 'percent') {
                                $totalInTotal += ($item->price * $item->quantity * (1 - ($item->discount / 100)));
                            } else if ($item->discount_type === 'total') {
                                $totalInTotal += ($item->price * $item->quantity) - $item->discount;
                            } else if ($item->discount_type === 'item') {
                                $totalInTotal += ($item->price - $item->discount) * $item->quantity;
                            } else if ($item->discount_type === 'price') {

                            } else {
                                $totalInTotal += $item->price * $item->quantity; 
                            }
                            return $totalInTotal;
                        }, 0) + $vatArray['21']['pvnTotal'] }}€'"></div>


                        </div>    
                    </div>
                    <div class="row p-1">
                        <div class="col-md-7 text-end">
                            Apmaksats
                        </div>
                        <div class="col-md-2 text-end"></div>
                        <div class="col-md-3 text-end">
                            <div x-text="'{{ $totalPaid }}€'"></div>
                        </div>  
                    </div>
                    <div class="row p-1">
                        <div class="col-md-7 text-end">
                            Vel apmaksat
                        </div>
                        <div class="col-md-2 text-end"></div>
                        <div class="col-md-3 text-end">
                        <div x-text="'{{ $invoiceItems->reduce(function($totalInTotal, $item) use ($vatArray) {
                            if ($item->discount_type === 'main' || $item->discount_type === 'percent') {
                                $totalInTotal += ($item->price * $item->quantity * (1 - ($item->discount / 100)));
                            } else if ($item->discount_type === 'total') {
                                $totalInTotal += ($item->price * $item->quantity) - $item->discount;
                            } else if ($item->discount_type === 'item') {
                                $totalInTotal += ($item->price - $item->discount) * $item->quantity;
                            } else if ($item->discount_type === 'price') {

                            } else {
                                $totalInTotal += $item->price * $item->quantity; 
                            }
                            return $totalInTotal;
                        }, 0) + $vatArray['21']['pvnTotal'] - $totalPaid}}€'"></div>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="row col-md-12">
                        <div class=" text-center">
                            <button type="submit" class="btn btn-primary my-4">
                                Save
                            </button>
                        </div>
        </form>
                    </div>
                       
        </div>
        </div>
    </div>
</div>