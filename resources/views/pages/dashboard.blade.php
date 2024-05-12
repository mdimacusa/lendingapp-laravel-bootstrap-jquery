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
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Dashboard</h1>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a href="#" class="text-muted text-hover-success">Main Navigation</a>
						</li>
					</ul>
					<!--end::Breadcrumb-->
				</div>
			</div>
			<!--end::Toolbar container-->

		</div>
		<!--end::Toolbar-->
		<!--begin::Content-->
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<!--begin::Content container-->
			<div id="kt_app_content_container" class="app-container container-fluid">

			    <div class="row p-0 justify-content-center">
                   <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card text-center min-w-125px rounded pt-4 pb-4 my-3">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Today's Transaction</span>
                            <span class="fs-2hx fw-bold text-gray-900 counted" data-kt-countup="true" data-kt-countup-value="36899" data-kt-initialized="1">₱{{number_format($today_transaction,2)}}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card text-center min-w-125px rounded pt-4 pb-4 my-3">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Overall Transaction</span>
                            <span class="fs-2hx fw-bold text-gray-900 counted" data-kt-countup="true" data-kt-countup-value="36899" data-kt-initialized="1">₱{{number_format($overall_transaction,2)}}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card text-center min-w-125px rounded pt-4 pb-4 my-3">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Today's Income</span>
                            <span class="fs-2hx fw-bold text-gray-900 counted" data-kt-countup="true" data-kt-countup-value="72" data-kt-initialized="1">₱{{number_format($today_income,2)}}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card text-center min-w-125px rounded pt-4 pb-4 my-3">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Overall Income</span>
                            <span class="fs-2hx fw-bold text-gray-900 counted" data-kt-countup="true" data-kt-countup-value="291" data-kt-initialized="1">₱{{number_format($overall_income,2)}}</span>
                        </div>
                    </div>
                </div>
                <div class="row p-0 justify-content-center">
                   <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card text-center min-w-125px rounded pt-4 pb-4 my-3">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Company Fund</span>
                            <span class="fs-2hx fw-bold text-gray-900 counted" data-kt-countup="true" data-kt-countup-value="36899" data-kt-initialized="1">₱{{number_format($total_fund,2)}}</span>
                        </div>
                    </div>
                    <!-- <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card text-center min-w-125px rounded pt-4 pb-4 my-3">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Total Used Fund</span>
                            <span class="fs-2hx fw-bold text-gray-900 counted" data-kt-countup="true" data-kt-countup-value="72" data-kt-initialized="1">₱{{number_format($overall_used_fund,2)}}</span>
                        </div>
                    </div> -->
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card text-center min-w-125px rounded pt-4 pb-4 my-3">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Total Unpaid</span>
                            <span class="fs-2hx fw-bold text-gray-900 counted" data-kt-countup="true" data-kt-countup-value="291" data-kt-initialized="1">{{$total_unpaid}}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card text-center min-w-125px rounded pt-4 pb-4 my-3">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Total Partially Paid</span>
                            <span class="fs-2hx fw-bold text-gray-900 counted" data-kt-countup="true" data-kt-countup-value="291" data-kt-initialized="1">{{$total_partially_paid}}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card text-center min-w-125px rounded pt-4 pb-4 my-3">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Total Paid</span>
                            <span class="fs-2hx fw-bold text-gray-900 counted" data-kt-countup="true" data-kt-countup-value="291" data-kt-initialized="1">{{$total_paid}}</span>
                        </div>
                    </div>
                </div>


			    <div class="row g-5 g-xl-10 mb-5 mb-xl-10 justify-content-center mt-3">
                    <div class="col-xl-12">
                        @if(count($fivedays_before) == 0)
                            <div class="alert alert-info py-3">No record found</div>
                        @else
                        <div class="card card-flush h-md-100">
                            <div class="card-header card-0 pt-6">
                                <div class="card-title">
                                    <h3>5 Day's Before Due</h3>
    						    </div>
                            </div>
                            <div class="card-body pt-6">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
            							<thead>
            								<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
												<th class="min-w-125px">Name</th>
                                                <th class="min-w-125px">Reference</th>
                                                <th class="min-w-125px">Amount</th>
												<th class="min-w-125px">Rate</th>
                                                <th class="min-w-125px">Upcoming Due Date</th>
            									<th class="text-end">Due Date</th>
            								</tr>
            							</thead>
            							<tbody class="text-gray-600 fw-semibold">
                                            @foreach($fivedays_before as $soa)
            								<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
												<td class="min-w-125px">{{$soa->fullname}}</td>
                                                <td class="min-w-125px">{{$soa->reference}}</td>
                                                <td class="min-w-125px">₱{{number_format($soa->amount,2)}}</td>
												<td class="min-w-125px">{{$soa->rate}}</td>
                                                <th class="min-w-125px">{{$soa->upcoming_due_date}}</th>
            									<td class="text-end">{{$soa->due_date}}</td>
            								</tr>
                                            @endforeach
            							</tbody>
            						</table>
                                </div>
                                <div class="mt-3">
									{{ $fivedays_before->onEachSide(1)->links() }}
								</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
			</div>
		</div>
	</div>

@section('custom')
 	<script>
        $('a.dashboard').addClass('active');
    </script>
@endsection
@endsection
