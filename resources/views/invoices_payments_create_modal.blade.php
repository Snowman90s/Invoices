<div id="profile-overview">
    <div class="modal fade" id="invoices_payments_create_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title" id="userDetailsModalLabel" style="width: 100%; text-align: left;">
                        <strong>Create Payment</strong></h4>
                    <button type="button" class="btn-close me-1" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body-sm p-2">
                    <div class="p-3">
                        <form data-request="{{ isset($widget) ? 'onAction' : 'onCreatePayment' }}">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="type" class="form-label">Type:</label>
                                    <select class="form-select mr-sm-2" name="type" id="inlineFormCustomSelect">
                                        <option value="bank">Bank</option>
                                        <option value="card">Card</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="date" class="form-label">Date:</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="amount" class="form-label">Amount:</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" required>
                                </div>
                            </div>

                            {{-- for scanner --}}
                            @if (isset($widget))
                                <input id="action" name="action" value="onCreatePayment" type="text" hidden>
                                <input id="invoice_id" name="invoice_id" value="{{ $invoice->id }}" type="text" hidden>
                                <input id="widget_id" name="widget_id" value="{{ $widget->id }}" type="text" hidden>
                            @endif

                            <div class="row justify-content-center">
                                <div class="text-center m-3">
                                    <button type="submit"
                                        class="btn btn-primary">Create</button>
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
    $('#invoices_payments_create_modal').modal('show');
</script>
