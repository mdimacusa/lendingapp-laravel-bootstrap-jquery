@extends('layout.app')
@section('container')
    <div class="d-flex flex-column flex-column-fluid">
		<!--begin::Toolbar-->
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<!--begin::Toolbar container-->
			<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
				<!--begin::Page title-->
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<!--begin::Title-->
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Payment History</h1>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a href="#" class="text-muted text-hover-success">Transactions</a>
						</li>
					</ul>
					<!--end::Breadcrumb-->
				</div>
				<div class="d-flex align-items-center gap-2 gap-lg-3">
					<div class="m-0" data-select2-id="select2-data-134-dux9">
						<a href="#" class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-success fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
							<span class="svg-icon svg-icon-6 svg-icon-muted me-1">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor"></path>
								</svg>
							</span>
							Filter
						</a>
						<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_63edb45d28e7f" data-select2-id="select2-data-kt_menu_63edb45d28e7f" style="">
							<form class="mt-8" method="post" action="">
								@csrf
								<!-- <div class="separator border-gray-200"></div> -->
								<div class="px-7 py-5">
									<div class="mb-10">
										<label class="form-label fw-semibold">Search</label>
										<input type="text" name="keyword" class="form-control"/>
									</div>
									<div class="mb-10">
										<label class="form-label fw-semibold">Select Rows:</label>
										<select class="form-select" name="row" data-control="select2" data-hide-search="true" >
											<option value="10">10</option>
											<option value="25">25</option>
											<option value="50">50</option>
											<option value="100">100</option>
										</select>
									</div>
									<div class="mb-10">
										<label class="form-label fw-semibold">From:</label>
										<input type="date" name="from" class="form-control"/>
									</div>
									<div class="mb-10">
										<label class="form-label fw-semibold">To:</label>
										<input type="date" name="to" class="form-control"/>
									</div>
									<div class="d-flex justify-content-end">
										<button type="submit" class="btn btn-sm btn-success">Submit</button>
									</div>
								</div>
							</form>
						</div>
						<form class="d-inline" method="post" action="">
					        @csrf
						    <input type="hidden" name="search">
						    <input type="hidden" name="row">
						    <input type="hidden" name="from">
						    <input type="hidden" name="to">
						    <button type="submit" class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-success fw-bold">Download</button>
						</form>
					</div>

				</div>
			</div>
			<!--end::Toolbar container-->

		</div>
		<!--end::Toolbar-->
		<!--begin::Content-->
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<!--begin::Content container-->
			<div id="kt_app_content_container" class="app-container container-fluid">

			    <div class="row g-5 g-xl-10 mb-5 mb-xl-10 justify-content-center">
                    <div class="col-lg-4">
                        <div class="card card-flush h-md-100">
                            <div class="card-body">
                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Account  #</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{$query->account_no}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Reference  #</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{$query->reference}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Full Name</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{$query->fullname}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Client Unique ID</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{$query->unique_id}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Current Loan Outstanding</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">₱{{$query->loan_outstanding}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Amount Borrowed</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">₱{{$query->amount}}</span>
                                    </div>
                                </div>

                                {{--<div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Balance Amount</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{$query->balance_amount!=null?'₱'.$query->balance_amount:'NO DATA'}}</span>
                                    </div>
                                </div>--}}

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Monthly</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">₱{{$query->monthly}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Rate</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{$query->rate}} %</span>
                                    </div>
                                </div>


                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Disbursement Date</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{date('M d, Y', strtotime($query->disbursement_date))}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Last Payment Date</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{empty($query->last_payment_date)?"NO DATA":date('M d, Y', strtotime($query->last_payment_date))}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Upcoming Due Date</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{empty($query->upcoming_due_date)?"NO DATA":date('M d, Y', strtotime($query->upcoming_due_date))}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Due Date</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">{{date('M d, Y', strtotime($query->due_date))}}</span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Status</div>
                                    <div class="d-flex align-items-senter">
                                        <span class="text-gray-900 fw-bolder fs-6">
                                            @if($query->status == "0")
                                            <span class="badge badge-light-danger fs-6">UNPAID</span>
                                            @elseif($query->status == "1")
                                            <span class="badge badge-light-primary fs-6">PARTIALLY PAID</span>
                                            @else
                                            <span class="badge badge-light-success fs-6">FULLY PAID</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card card-flush h-md-100">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table  class="table align-middle table-row-dashed border rounded fs-6 gy-5 gs-7 mt-5">
            							<thead>
            								<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                <th>Payment Method</th>
												<th>Current Loan Outstanding</th>
                                                <th>Amount Paid</th>
                                                <th>Processed By</th>
                                                <th class="text-end">Date of transaction</th>
                                                <th class="text-end">Upcoming Due Date</th>
            								</tr>
            							</thead>
            							<tbody class="text-gray-600 fw-semibold">
                                            @foreach($datas as $data)
                                            <tr>
                                                <td>{{$data->payment_method}}</td>
                                                <td>₱{{$data->loan_outstanding}}</td>
                                                <td>{{$data->payment_amount!=null?'₱'.$data->payment_amount:'NO DATA'}}</td>
                                                <td>{{$data->name}}</td>
                                                <td class="text-end">{{empty($data->last_payment_date)?"NO DATA":date('M d, Y', strtotime($data->last_payment_date))}}</td>
                                                <td class="text-end">{{empty($data->upcoming_due_date)?"NO DATA":date('M d, Y', strtotime($data->upcoming_due_date))}}</td>
                                                {{-- <td class="text-end"><a href="javascript:void(0)" id="viewDetails()">view details</a></td> --}}
                                            </tr>
                                            @endforeach
            							</tbody>
            						</table>
                                </div>
                                <div class="mt-3">
									{{ $datas->onEachSide(1)->links() }}
								</div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
@section('custom')
 	<script>
        $('a.soa-funds').addClass('active');
   </script>
@endsection
@endsection
