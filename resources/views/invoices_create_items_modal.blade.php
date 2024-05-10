<!-- Modal -->
<div class="modal fade" id="invoiceCreateItemModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="invoiceCreateItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center px-4 ">
                <div class="modal-title h4" id="invoiceCreateItemModalLabel">Create Item</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <div class="">
                    <form data-request="{{ isset($widget) ? 'onAction' : 'onCreateItems' }}">
                        @csrf
                        {{-- Company Details --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Item name</label>
                                    <input type="text" class="form-control" placeholder="Name" name="name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Price</label>
                                    <input type="text" id="priceInput" class="form-control" placeholder="Price" name="price">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Quantity</label>
                                    <input type="text" id="quantityInput" class="form-control" placeholder="Quantity" name="quantity">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Total price</label>
                                    <input type="text" class="form-control" placeholder="Total price"
                                        name="price_total">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- HTML -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Total</label>
                                    <input type="text" class="form-control" placeholder="Total" id="inTotalInput" name="in_total" readonly>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                <label class="form-label fw-semibold">Discount type</label>
                                    <select class="form-select fw-semibold" name="discount_type">Discount Type
                                        @foreach ($discountTypes as $value => $discountType)
                                            <option value="{{ $value }}" {{ $value == $invoiceItem->discount_type ? 'selected' : '' }}>
                                                {{ $discountType['name'] }}
                                            </option>
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                <label class="form-label fw-semibold">Discount</label>
                                    <input type="text" class="form-control" id="discountInput" placeholder="Discount"
                                        name="discount">
                                </div>
                            </div>
                        </div>

                        {{-- For scanner --}}
                        @if (isset($widget))
                            <input id="widget_id" name="widget_id" value="{{ $widget->id }}" type="text"hidden>
                            <input id="action" name="action" value="onCreateItems" type="text" hidden>
                            <input id="invoice_id" name="invoice_id" value="{{ $invoice->id }}" type="text" hidden>
                        @endif

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary my-4" data-bs-dismiss="modal">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#invoiceCreateItemModal').modal('show');

// JavaScript (jQuery)
$(document).ready(function() {
    // Add event listeners to quantity, discount, and price inputs
    $('#quantityInput, #discountInput, #priceInput').on('input', function() {
        // Get the values of quantity, discount, and price
        var quantity = parseFloat($('#quantityInput').val());
        var discount = parseFloat($('#discountInput').val());
        var price = parseFloat($('#priceInput').val());

        // Perform the calculation for in_total
        var preTotal = price - (price * (discount / 100));
        var inTotal = preTotal * quantity;

        // Update the value of the in_total input
        $('#inTotalInput').val(inTotal.toFixed(2)); // Assuming you want to display the total with two decimal places
    });
});


</script>
