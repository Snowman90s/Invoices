{{-- tab navigation --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills user-profile-tab">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4 {{ $tab == 'items' ? 'active' : '' }}" href="/invoices/profile/{{ $invoice->id }}/items">
                            <i class="ti ti-shopping-cart me-2 fs-6"></i>
                            <span class="d-none d-md-block">Items</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4 {{ $tab == 'details' ? 'active' : '' }}" href="/invoices/profile/{{ $invoice->id }}/details" >
                            <i class="ti ti-list-details me-2 fs-6"></i>
                            <span class="d-none d-md-block">Details</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-4 {{ $tab == 'payments' ? 'active' : '' }}" href="/invoices/profile/{{ $invoice->id }}/payments" >
                            <i class="ti ti-cash me-2 fs-6"></i>
                            <span class="d-none d-md-block">Payments</span>
                        </a>
                    </li>
                        <div class="col-md text-end">
                            <div class="p-3">
                                <a class="btn bg-danger-subtle text-danger me-2 px-2" href="#" data-request="onDelete" data-request-confirm="Are you sure you want to delete this invoice?" data-request-data="id: {{ $invoice->id }}">
                                    <i class="ti ti-trash fs-6"></i>
                                </a>
                                <a class="btn bg-primary-subtle text-primary me-2 px-2" href="#" data-request="onCloneInvoice" data-request-confirm="Are you sure you want to clone this invoice?" data-request-data="id: {{ $invoice->id }}">
                                    <i class="ti ti-copy fs-6"></i>
                                </a>
                                <a class="btn bg-primary-subtle text-primary px-2" href="#" data-request="" data-request-confirm="Are you sure you want to delete this invoice?" data-request-data="id: {{ $invoice->id }}">
                                    <i class="ti ti-message fs-6"></i>
                                </a>
                            </div>
                        </div>
                    </ul>

                    <div class="tab-content">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



