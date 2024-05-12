@if($tab=="summary")
<form method="POST" action="{{route('user-management.administrator.profile.update',['tab'=>'summary','id'=>Crypt::encrypt($users->id)])}}">
    @csrf
    <span class="d-flex mb-5 position-relative">
        <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
            Administrator Details
        </span>
        <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>
    </span>
    <input type="hidden" name="users_id" value="">
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="mb-4">
                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Full Name</label>
                <input type="text" name="full_name" value="{{$users->name}}" class="form-control">
                @error("full_name")
                    <div class="text text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="mb-4">
                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Email</label>
                <input type="email" name="email" value="{{$users->email}}" class="form-control">
                @error("email")
                    <div class="text text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="mb-4">
                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Pincode</label>
                <input type="text" name="pincode" value="{{$users->pincode}}" class="form-control">
                @error("pincode")
                    <div class="text text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="mb-4">
                <label class="form-label mb-3 fs-9 fw-bold text-uppercase">Status</label>
                <select name="status" class="form-control">
                    <option value="ACTIVE" {{$users->status=="ACTIVE"?"Selected":""}}>ACTIVE</option>
                    <option value="INACTIVE" {{$users->status=="INACTIVE"?"Selected":""}}>INACTIVE</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="mb-4">
                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Password</label>
                <div class="input-group">
                    <span class="input-group-text border-right-0" >
                        <a style="cursor:pointer" >
                            <i id="password-lock" class="far fa-eye"></i>
                        </a>
                    </span>
                    <input type="password" id="password" name="password" autocomplete="new-password" class="form-control" />
                </div>
                @error("password")
                    <div class="text text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="mb-4">
                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text border-right-0" >
                        <a style="cursor:pointer" >
                            <i id="password-lock-confirmation" class="far fa-eye"></i>
                        </a>
                    </span>
                    <input type="password" id="password-confirmation" name="password_confirmation" class="form-control" />
                </div>
                @error("password_confirmation")
                    <div class="text text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="separator separator-dashed my-3"></div>
    <div class="mb-5 fv-row">
        <button type="submit" class="btn btn-light-success ls-1 text-uppercase blocker fs-8 fw-bolder w-100">Save Changes</button>
    </div>
</form>
@elseif($tab=="deposit")
    @if(count($query) == 0)
    <div class="alert alert-info py-3">No record found</div>
    @else
    <div class="row">
        <div class="col-md-9 col-12 mb-5">
            <div class="card">
                <div class="card-body pt-6">
                    <div class="table-responsive">
                        <table id="tableData" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">Reference</th>
                                    <th>Amount</th>
                                    <th class="text-end">Processed Date</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach($query as $data)
                                <tr>
                                    <td>{{$data->reference}}</td>
                                    <td>₱{{number_format($data->amount,2)}}</td>
                                    <td class="text-end">{{$data->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        @if($filters['rows'] != "All")
                        {{ $query->onEachSide(1)->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <span class="d-flex mb-5 position-relative">
                <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
                    Administrator Details
                </span>
                <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>
            </span>
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-semibold fs-6 me-2">Unique ID</div>
                <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">1165937</span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-semibold fs-6 me-2">Name</div>
                <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{$users->name}}</span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-semibold fs-6 me-2">Email</div>
                <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{$users->email}}</span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>

            <a href="#" class="card bg-body shadow-sm hoverable card-xl-stretch mb-5">
                <div class="card-body">
                    <div class="text-gray-900 fw-bold fs-2 mb-2">₱{{number_format($total_deposit,2)}}</div>
                    <div class="fw-semibold text-gray-800 text-uppercase fs-9">Total Deposit</div>
                </div>
            </a>
        </div>
    </div>
    @endif
@else
    @if(count($query) == 0)
        <div class="alert alert-info py-3">No record found</div>
    @else
    <div class="row">
        <div class="col-md-9 col-12 mb-5">
            <div class="card">
                <div class="card-body pt-6">
                    <div class="table-responsive">
                        <table id="tableData" class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">Reference</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end">Request Date</th>
                                    <th class="text-end">Processed Date</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach($query as $data)
                                <tr>
                                    <td>{{$data->reference}}</td>
                                    <td>₱{{$data->amount}}</td>
                                    <td>
                                        @if($data->status == 0)
                                        <div class="badge badge-light-danger">UNPAID</div>
                                        @elseif($data->status == 1)
                                        <div class="badge badge-light-primary">PARTIALLY PAID</div>
                                        @else
                                        <div class="badge badge-light-success">FULLY PAID</div>
                                        @endif
                                    </td>
                                    <td class="text-end">{{$data->disbursement_date}}</td>
                                    <td class="text-end">{{$data->updated_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        @if($filters['rows'] != "All")
                        {{ $query->onEachSide(1)->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <span class="d-flex mb-5 position-relative">
                <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
                    Administrator Details
                </span>
                <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>
            </span>
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-semibold fs-6 me-2">Unique ID</div>
                <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">1165937</span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-semibold fs-6 me-2">Name</div>
                <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{$users->name}}</span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>
            <div class="d-flex flex-stack">
                <div class="text-gray-700 fw-semibold fs-6 me-2">Email</div>
                <div class="d-flex align-items-senter">
                    <span class="text-gray-900 fw-bolder fs-6">{{$users->email}}</span>
                </div>
            </div>
            <div class="separator separator-dashed my-3"></div>

            <a href="#" class="card bg-body shadow-sm hoverable card-xl-stretch mb-5">
                <div class="card-body">
                    <div class="text-gray-900 fw-bold fs-2 mb-2">₱{{number_format($administrator_fully_paid,2)}}</div>
                    <div class="fw-semibold text-gray-800 text-uppercase fs-9">Fully Paid</div>
                </div>
            </a>
            <a href="#" class="card bg-body shadow-sm hoverable card-xl-stretch mb-5">
                <div class="card-body">
                    <div class="text-gray-900 fw-bold fs-2 mb-2">₱{{number_format($administrator_partially_paid,2)}}</div>
                    <div class="fw-semibold text-gray-800 text-uppercase fs-9">Partially Paid</div>
                </div>
            </a>
            <a href="#" class="card bg-body shadow-sm hoverable card-xl-stretch mb-5">
                <div class="card-body">
                    <div class="text-gray-900 fw-bold fs-2 mb-2">₱{{number_format($administrator_unpaid,2)}}</div>
                    <div class="fw-semibold text-gray-800 text-uppercase fs-9">Unpaid</div>
                </div>
            </a>
        </div>
    </div>
    @endif
@endif
