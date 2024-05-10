<div class="row">
    
    <div class="col-md-6 fs-8 mb-3">

        <div class="d-flex align-items-center">
            <div class="p-6 bg-primary-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                <i class="{{ $aimx['icon'] }} text-primary fs-8"></i>
            </div>
            <div class="">
                <div class="user-meta-info">
                    <div class="fs-8">{{ $aimx['title'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a class="btn mb-1 btn-rounded btn-light text-dark font-medium text-dark" data-bs-toggle="tooltip" title="Widgets" href="#">
            <i class="ti ti-layout-dashboard fs-4"></i>
        </a>
        <a class="btn mb-1 btn-rounded btn-light text-dark font-medium text-dark" data-bs-toggle="tooltip" title="Settings" href="#">
            <i class="ti ti-settings fs-4"></i>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12 card py-3">
        <div class="row">
            <div class="col-md-6">

                <a href="/invoice/profile/new" class="btn bg-primary-subtle ms-2">
                    <i class="ti ti-plus"></i>
                    {{ 'Add Invoice' }}

                </a>
                {{-- import --}}
                {{-- <a href="#" class="btn bg-primary-subtle">
                    <i class="ti ti-file"></i>
                    {{ 'Import' }}
                </a> --}}
                
            </div>
            <div class="col-md-6 text-end">
                <div class="input-group">
                    @if ($data['filterCount'])
                        <input class="form-control form-control" name="data[search]" value="" placeholder="{{ 'Search disabled' }}" disabled="disabled">
                        <button class="btn bg-primary-subtle text-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas" data-request="onShowFilters">
                            <i class="ti ti-adjustments"></i>
                            {{ 'Filter' }}
                            <span class="badge bg-danger badge-danger">{{ $data['filterCount'] }}</span>
                        </button>
                        <button class="btn btn btn-light-danger" type="button" data-request="onUnsetFilters">
                            <i class="ti ti-times"></i>
                            {{ 'Cancel filter' }}
                        </button>
                    @else
                        <input class="form-control form-control" name="data[search]" value="{{ $data['search'] }}" placeholder="{{ 'Search for...' }}">
                        <button class="btn bg-primary-subtle text-primary" type="submit">
                            <i class="ti ti-search"></i>
                            {{ 'Search' }}
                        </button>
                        <button class="btn bg-secondary-subtle text-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas" data-request="onShowFilters">
                            <i class="ti ti-adjustments"></i>
                            {{ 'Filter' }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>