<div >
    <div x-data="{ item: {}}">

    <div class="row">
            <div class="col-md-3">
                <div class="card ">
                    <div class="card-body d-flex p-4 align-items-center">
                        <span class="rounded-circle bg-primary py-2 px-2 text-white me-2 d-inline-flex align-items-center justify-content-center">
                            <i class="ti ti-number fs-4"></i>
                        </span>
                        <div class="d-flex flex-column align-items-start">
                            <h6 class=" fw-semibold mb-0">{{ $invoice->name }}</h6>
                            <p class="secondary mb-0 fs-2">Invoice ID: {{ $invoice->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex p-4 align-items-center">
                        <span class="rounded-circle bg-secondary py-2 px-2 text-white me-2 d-inline-flex align-items-center justify-content-center">
                            <i class="ti ti-receipt-dollar fs-4"></i>
                        </span>
                    
                        <div class="flex-column align-items-start">
                            <h6 class="mb-0 fw-semibold">{{ $totalPaid }} / {{ $totalPrice }} $</h6>

                            @if ($paidPercentage > 0)
                                <p class="mb-0 fs-2">{{ $dueAmount }} $ left</p>
                            @else
                                <h4 class="mb-0 fs-2 fw-semibold">Not paid</h4>
                            @endif
                        </div>

                    </div>
                </div>     
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex p-4 align-items-center">
                        <span class="rounded-circle bg-success py-2 px-2 text-white me-2 d-inline-flex align-items-center justify-content-center">
                            <i class="ti ti-discount-2 fs-4"></i>
                        </span>
                        <div>

                        <h6 class="mb-0 fw-semibold" x-text="'{{ number_format($invoiceItems->reduce(function($totalDiscount, $item) {
                            if ($item->discount_type === 'main' || $item->discount_type === 'percent') {
                                $totalDiscount += ($item->price * $item->quantity * ($item->discount / 100));
                            } else if ($item->discount_type === 'total') {

                            } else if ($item->discount_type === 'item') {
                                $totalDiscount += $item->discount * $item->quantity;
                            } else if ($item->discount_type === 'price') {

                            } else {

                            }
                            return $totalDiscount;
                        }, 0) / $invoiceItems->sum(function($item) {
                            return ($item->quantity * $item->price);
                        }) * 100, 1) }}% discount'"></h6>

                            <p class="mb-0 secondary fs-2 " x-text="'{{ $invoiceItems->reduce(function($totalDiscount, $item) {
                if ($item->discount_type === 'main' || $item->discount_type === 'percent') {
                    $totalDiscount += ($item->price * $item->quantity * ($item->discount / 100));
                } else if ($item->discount_type === 'total') {

                } else if ($item->discount_type === 'item') {
                    $totalDiscount += $item->discount * $item->quantity;
                } else if ($item->discount_type === 'price') {

                } else {

                }
                return $totalDiscount;
            }, 0) }}€ discount'"> </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex p-4 align-items-center">
                        <span class="rounded-circle bg-secondary py-2 px-2 text-white me-2 d-inline-flex align-items-center justify-content-center">
                            <i class="ti ti-calendar-month fs-4"></i>
                        </span>
                        <div>
                            <h6 class="mb-0 fw-semibold">{{ $invoice->issued_at }}</h6>
                            <p class="secondary mb-0 fs-2">Date created</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex p-4 align-items-center">
                        <span class="rounded-circle bg-secondary py-2 px-2 text-white me-2 d-inline-flex align-items-center justify-content-center">
                            <i class="ti ti-calendar-time fs-4"></i>
                        </span>
                        <div class="d-flex flex-column align-items-start">
                            @if($daysLeft > 0)
                                <h6 class="mb-0 fw-semibold">{{ $invoice->due_at }}</h6>
                                <p class="secondary mb-0 fs-2">{{ $daysLeft }} days left</p>
                            @elseif($daysLeft == 0)
                                <h6 class="mb-0 fw-semibold">Due today</h6>
                            @else
                                <h6 class="mb-0 fw-semibold">Overdue by {{ abs($daysLeft) }} day(s)</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="card">
    <div class="card-body">
        {{-- Invoice information --}}
    <form data-request="onSaveItem">
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Status 
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-{{ $statusTypes[$invoice->status]['color'] }} border">
                                <i class="{{ $statusTypes[$invoice->status]['icon'] }}"></i>
                            </span>
                            <select class="form-select" name="data[status]">
                                @foreach ($statusTypes as $value => $status)
                                    <option value="{{ $value }}" {{ $value == $invoice->status ? 'selected' : '' }}>
                                        {{ $status['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Client</label>
                        <div class="input-group">
                            <select class="form-select" name="data[client_id]" data-request="yourMethod">
                                <option value="">Select a client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ $client->id == $invoice->client_id ? 'selected' : '' }}>
                                        {{ $client->title }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-group-text border">
                            <a href="/clients/profile/{{ $invoice->client_id}}">
                                <i class="ti ti-user"></i>
                                </a>
                            </span>
                        </div>
                        
                    </div>
                    
                </div>

        
                <div class="col-md-2 text-center">
                    <div class="mb-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100 fs-4">
                            <i class="ti ti-check me-1"></i>
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


