<div class="table-responsive">
    <table class="table table-nowrap table-hover table-align-middle">
        <thead class="thead-light">
            <tr>
                <th class="align-middle" style="width: 30px">
                    <input type="checkbox" class="form-check-input" id="list-select-all">
                </th>
                
                <th>{{ 'Name' }}</th>
                <th width="150px">{{ 'Total price' }}</th>
                <th>{{ 'Paid' }}</th>
                <th width="170px">{{ 'Status' }}</th>
                <th>{{ 'Client' }}</th>
                <th width="170px">{{ 'Date' }}</th>
                <th class="text-end" width="100px">{{ 'Action' }}</th>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($listItems as $listItem)
                <tr id="list-row-{{ $listItem->id }}" data-request-data="listItem: {{ $listItem->id }}" class="align-middle">

                    <td class="align-middle">
                        <input type="checkbox" class="form-check-input" id="check-{{ $listItem->id }}">
                    </td>
                    
                    <td>
                        <a href="/invoices/profile/{{ $listItem->id }}">
                            <div class="d-flex align-items-center ">
                                <div class="p-2 bg-primary-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-device-ipad-horizontal-dollar text-primary fs-3"></i>
                                </div>
                                <div class="">
                                    <div class="user-meta-info">
                                        <h6 class="user-name mb-0" data-name="{{ $listItem->name }}">{{ $listItem->name }}</h6>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </td>
                    <td>{{ $listItem->price_total }}</td>

                    <td>
                        {{ $listItem->paid }}
                        @if(isset($listItem->refunded) && $listItem->refunded > 0.00)
                            <span class="badge bg-warning p-1 fs-2" data-bs-toggle="tooltip" title="Refunded amount">
                                <i class="ti ti-receipt-refund fs-2 me-1"></i>{{ $listItem->refunded }}
                            </span>
                        @endif
                    </td>

                    <td>
                        @if(!$listItem->is_paid && strtotime($listItem->due_at) < strtotime(date('Y-m-d')))
                            <div class="align-middle">
                                <span class="rounded-3 py-2 pe-3 text-{{ isset($statusTypes[$listItem->status]) ? $statusTypes[$listItem->status]['text-color'] : 'fallback' }} fw-semibold fs-2 d-inline-flex align-items-center gap-1">
                                    <i class="{{ isset($statusTypes[$listItem->status]) ? $statusTypes[$listItem->status]['icon'] : 'fallback' }} fs-4"></i>{{ isset($statusTypes[$listItem->status]) ? $statusTypes[$listItem->status]['title'] : 'fallback' }}
                                </span>
                                <span class="badge rounded-3 py-2 fw-semibold fs-2 d-inline-flex align-items-center gap-1" data-bs-toggle="tooltip" title="Overdue">
                                    <i class="ti ti-calendar-x fs-4"></i>
                                </span>
                            </div>
                        @else    
                            <span class="rounded-3 py-2 pe-3 text-{{ isset($statusTypes[$listItem->status]) ? $statusTypes[$listItem->status]['text-color'] : 'fallback' }} fw-semibold fs-2 d-inline-flex align-items-center gap-1">
                                <i class="{{ isset($statusTypes[$listItem->status]) ? $statusTypes[$listItem->status]['icon'] : 'fallback' }} fs-4"></i>
                                {{ isset($statusTypes[$listItem->status]) ? $statusTypes[$listItem->status]['title'] : 'fallback' }}
                            </span>
                        @endif
                    </td>

                    <td>
                        <a href="/clients/profile/{{ $listItem->client->id}}">
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-primary-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-user text-primary fs-3"></i>
                                    </div>
                                    <div class="">
                                        <div class="user-meta-info">
                                            <h6 class="user-name mb-0" data-name="{{ $listItem->client->title }}">{{ $listItem->client->title }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </a>
                    </td>
                    <!-- <td>{{ $listItem->client->title }}</td> -->
                    <td class="fs-3">
                            {{ $listItem->issued_at }} <br>
                            {{ $listItem->due_at }}
                    </td>


                    <td class="text-end ">
                        <a class="btn bg-secondary-subtle btn-sm" data-bs-toggle="tooltip" title="{{ $listItem->website }}" href="{{ $listItem->website }}" target="_blank">
                            <i class="ti ti-link "></i>
                        </a>
                        <a href="/invoices/profile/{{ $listItem->id }}" class="btn bg-primary-subtle btn-sm" data-bs-toggle="tooltip" title="Open profile">
                            <i class="ti ti-device-ipad-horizontal-dollar "></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

</div>

