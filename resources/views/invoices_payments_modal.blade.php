<div id="profile-overview">
    <div class="modal fade" id="invoices_payments_modal" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-center px-4">
                    <h4 class="modal-title" id="userDetailsModalLabel" style="width: 100%; text-align: left;">
                        <strong>Edit Payment</strong></h4>
                    <button type="button" class="btn-close me-1" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4">
                    <form data-request="{{ isset($widget) ? 'onAction' : 'onUpdatePayment' }}">

                        <input type="hidden" name="id" value="{{ $payment->id }}">

                    <div class="row">
                    <div class="col-6 mb-3">
                        <label for="type" class="form-label">Type:</label>
                        <select class="form-select" name="type">
                            @foreach($paymentStatuses as $key => $paymentStatus)
                                <option value="{{ $key }}" {{ $payment->type == $key ? 'selected' : '' }}>
                                    {{ $paymentStatus['type'] }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount:</label>
                            <input type="number" step="0.01" name="amount" value="{{ $payment->amount }}" class="form-control"
                                required>
                        </div>

                        @if(isset($widget))
                            <input type="hidden" name="widget_id" value="{{ $widget->id }}">
                            <input id="action" name="action" value="onUpdatePayment" type="text" hidden>
                        @endif

                        <div class="row justify-content-center">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $('#invoices_payments_modal').modal('show');
</script>
