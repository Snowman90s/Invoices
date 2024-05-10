<div class="card">
        <div class="card-body">
            <div id="profile-overview">
                <div class="row ">
                    
                    <div class=" mb-3">
                        <div class="table-responsive mt-3">
                            <table class="table table-nowrap table-hover table-align-middle">
                                <thead class="thead-light">
                                    <div class="text-end me-3">
                                <a href="#" class="btn bg-primary-subtle ms-2"
                                data-request="onOpenCreateModal">
                                    <i class="ti ti-plus"></i>
                                    Create Payment
                                </a>
                                    </div>
                                    <tr>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th class="text-end">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($payments as $payment)
                                    <tr id="list-row-{{ $payment->id }}" data-request-data="Payment: {{ $payment->id }}"
                                class="align-middle">
                                        <td>
                                            <span class="badge bg-primary-subtle rounded-3 py-2 align-items-center text-primary fw-semibold fs-2 d-inline-flex gap-1">
                                                <div name="payment_type">
                                                    <a>
                                                    @if(isset($paymentStatuses[$payment->type]))
                                                        <i class="{{ $paymentStatuses[$payment->type]['icon'] }}"></i>
                                                        {{ $paymentStatuses[$payment->type]['type'] }}
                                                    @else
                                                        Undefined Type
                                                    @endif
                                                    </a>
                                                </div>
                                            </span>
                                        </td>
                                        <td>{{ $payment->amount }}</td>
                                        <td>{{ $payment->date }}</td>
                                        <td class="text-end">
                                            <input type="hidden" name="id" value="{{ $payment->id }}">
                                            <a href="#" class="btn bg-danger-subtle text-danger p-1 px-2"
                                                data-request="onDeletePayment"
                                                data-request-data="id: {{ $payment->id }}"
                                                data-request-confirm="Are you sure you want to delete this Payment?">
                                                <i class="ti ti-trash fs-3"></i>
                                            </a>

                                            <a href="#" class="btn bg-primary-subtle text-primary p-1 px-2" data-request="onOpenModal"  data-request-data="id: {{ $payment->id }}">
                                                <i class="ti ti-cash fs-3"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>                
                    </div>
                </div>
            </div>
        </div>
</div>
