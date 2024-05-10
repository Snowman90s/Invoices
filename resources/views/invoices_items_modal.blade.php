<!-- Modal -->
<div class="modal fade" id="invoiceItemModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="invoiceItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center px-4 ">
                <div class="modal-title h4" id="invoiceItemModalLabel">Edit Item</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
            <div class="">
                    <form data-request="{{ isset($widget) ? 'onAction' : 'onUpdateItem' }}">
                        @csrf

                        <input id="invoiceItemId" name="invoiceItemId" value="{{ $invoiceItem->id }}" type="text" hidden>

                        {{-- Company Details --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Item name</label>
                                    <input type="text" class="form-control" placeholder="Name" name="name"
                                        value="{{ $invoiceItem->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Price</label>
                                    <input type="text" class="form-control" placeholder="Price" name="price"
                                        value="{{ $invoiceItem->price }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Quantity</label>
                                    <input type="text" class="form-control" placeholder="Quantity" name="quantity"
                                        value="{{ $invoiceItem->quantity }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Total price</label>
                                    <input type="text" class="form-control" placeholder="Total price" name="price_total"
                                        value="{{ $invoiceItem->price_total }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Total</label>
                                    <input type="number" class="form-control" placeholder="Total" name="in_total"
                                        value="{{ $invoiceItem->in_total }}" required>
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
                                    <input type="text" class="form-control" placeholder="Discount"
                                        name="discount">
                                </div>
                            </div>
                        </div>

                        {{-- For scanner --}}
                        @if (isset($widget))
                            <input id="widget_id" name="widget_id" value="{{ $widget->id }}" type="text"hidden>
                            <input id="action" name="action" value="onCreateItem" type="text" hidden>
                        @endif

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary my-4">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Init modal window and show it -->
<script>
    $('#invoiceItemModal').modal('show');
</script>
