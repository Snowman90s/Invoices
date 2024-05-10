<form data-request="onSetFilters">
    <div class="row">
        <div class="col-md-12 mb-4">
            <label for="client_id" class="form-label fw-semibold">Client</label>
            <select class="form-select" id="client_id" name="filters[client_id][is]" placeholder="Client name">
                <option value="" selected>-- Select Client --</option>
                <!-- Loop through clients to generate options -->
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ isset($filters['client_id']['is']) && $filters['client_id']['is'] == $client->id ? 'selected' : '' }}>{{ $client->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-4">
            <label for="statusTypes" class="form-label fw-semibold">Status</label>
            <select name="filters[status][is]" class="form-select">
                <option value="" selected>-- Select Status --</option>
                <!-- Loop through status types to generate options -->
                @foreach ($statusTypes as $key => $status)
                    <option value="{{ $key }}" {{ isset($filters['status']['is']) && $filters['status']['is'] == $key ? 'selected' : '' }}>{{ $status['title'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <label for="issued_at" class="form-label fw-semibold">From</label>
            <input type="date" class="form-control" id="issued_at" name="filters[issued_at][after]" placeholder="From">
        </div>
        <div class="col-md-6 mb-4">
            <label for="due_at" class="form-label fw-semibold">To</label>
            <input type="date" class="form-control" id="due_at" name="filters[due_at][before]" placeholder="To">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <label for="price_total" class="form-label fw-semibold">Total price</label>
            <input type="text" class="form-control" id="price_total" name="filters[price_total][after]" placeholder="From">
        </div>
        <div class="col-md-6 mb-4">
            <label for="price_total" class="form-label fw-semibold">Total price</label>
            <input type="text" class="form-control" id="price_total" name="filters[price_total][before]" placeholder="To">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <label for="paid" class="form-label fw-semibold">Paid</label>
            <input type="text" class="form-control" id="paid" name="filters[paid][after]" placeholder="From">
        </div>
        <div class="col-md-6 mb-4">
            <label for="paid" class="form-label fw-semibold">Paid</label>
            <input type="text" class="form-control" id="paid" name="filters[paid][before]" placeholder="To">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 text-end">
            <button class="btn bg-secondary-subtle text-secondary" type="button" data-request="onUnsetFilters">
                <i class="ti ti-reload"></i>
                Reset
            </button>
        </div>
        <div class="col-md-6 text-start">
            <button type="submit" class="btn btn-primary me-1">
                <i class="ti ti-adjustments"></i>
                Filter
            </button>
        </div>
    </div>
</form>
