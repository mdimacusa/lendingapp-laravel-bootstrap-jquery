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
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Client</h1>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a href="#" class="text-muted text-hover-success">User Management</a>
						</li>
					</ul>
					<!--end::Breadcrumb-->
				</div>
				<div class="d-flex align-items-center gap-2 gap-lg-3">
					<div class="m-0" data-select2-id="select2-data-134-dux9">
                        <a href="{{route('user-management.client.create')}}" class="btn btn-sm btn-flex fw-bold btn-success">Create Client</a>
						<a href="#" class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-success fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
							<span class="svg-icon svg-icon-6 svg-icon-muted me-1">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor"></path>
								</svg>
							</span>
							Filter
						</a>
						<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_63edb45d28e7f" data-select2-id="select2-data-kt_menu_63edb45d28e7f" style="">
							<form class="mt-8" method="post">
								@csrf
								<!-- <div class="separator border-gray-200"></div> -->
								<div class="px-7 py-5">
									<div class="mb-10">
										<label class="form-label fw-semibold">Search</label>
										<input type="text" name="search" class="form-control" value="{{$filters['search']??""}}"/>
									</div>
									<div class="mb-10">
										<label class="form-label fw-semibold">Select Rows:</label>
										<select class="form-select" name="rows">
											<option {{$filters['rows'] == 10 ? 'selected' : ''}}>10</option>
                                            <option {{$filters['rows'] == 25 ? 'selected' : ''}}>25</option>
                                            <option {{$filters['rows'] == 50 ? 'selected' : ''}}>50</option>
                                            <option {{$filters['rows'] == 100 ? 'selected' : ''}}>100</option>
                                            <option {{$filters['rows'] == 200 ? 'selected' : ''}}>200</option>
                                            <option {{$filters['rows'] == "All" ? 'selected' : ''}}>All</option>
										</select>
									</div>
									<div class="mb-10">
										<label class="form-label fw-semibold">From:</label>
										<input type="date" name="from" class="form-control" {{$filters['from']??""}}/>
									</div>
									<div class="mb-10">
										<label class="form-label fw-semibold">To:</label>
										<input type="date" name="to" class="form-control" {{$filters['to']??""}}/>
									</div>
									<div class="d-flex justify-content-end">
										<button type="submit" class="btn btn-sm btn-success">Submit</button>
									</div>
								</div>
							</form>
						</div>
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
                    <div class="col-xl-12">
						@if(count($clients) == 0)
                            <div class="alert alert-info py-3">No record found</div>
                        @else
                        <div class="card card-flush h-md-100">
                            <div class="card-body pt-6">
                                <div class="table-responsive">
                                    <table id="tableData" class="table align-middle table-row-dashed fs-6 gy-5">
            							<thead>
            								<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-125px">Unique ID</th>
												<th class="min-w-125px">First Name</th>
												<th class="min-w-125px">Middle Name</th>
                                                <th class="min-w-125px">Surname Name</th>
												<th class="min-w-125px">Email</th>
                                                <th class="min-w-175px">Contact Number</th>
                                                <th class="min-w-175px">Address</th>
                                                <th class="min-w-125px">Status</th>
            									<th class="min-w-125px">Date Created</th>
                                                <th class="text-end">Action</th>
            								</tr>
            							</thead>
            							<tbody class="text-gray-600 fw-semibold">
											@foreach($clients as $client)
												<tr>
													<td>{{$client->unique_id}}</td>
													<td>{{$client->first_name}}</td>
													<td>{{$client->middle_name??'NO DATA'}}</td>
													<td>{{$client->surname}}</td>
													<td>{{$client->email??'NO DATA'}}</td>
													<td>{{$client->contact_number}}</td>
													<td>{{$client->address??'NO DATA'}}</td>
													<td>
														@if($client->status == "ACTIVE")
														<div class="badge badge-light-success">ACTIVE</div>
														@else
														<div class="badge badge-light-danger">INACTIVE</div>
														@endif
													</td>
													<td>{{date('M d, Y', strtotime($client->created_at))}}</td>
													<td class="text-end">
														<a href="#"data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
															<span class="svg-icon svg-icon-muted svg-icon-1">
																<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path opacity="0.3" d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.7999C4.1 18.3999 4.1 17.7999 4.4 17.2999L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.39995 4.7 8.60002 5.2 7.90002L4.4 6.79993C4.1 6.39993 4.1 5.69993 4.5 5.29993L5.3 4.5C5.7 4.1 6.3 4.10002 6.8 4.40002L7.9 5.19995C8.6 4.79995 9.4 4.39995 10.2 4.19995L10.4 2.90002C10.5 2.40002 11 2 11.5 2H12.6C13.2 2 13.6 2.40002 13.7 2.90002L13.9 4.19995C14.7 4.39995 15.5 4.69995 16.2 5.19995L17.3 4.40002C17.7 4.10002 18.4 4.1 18.8 4.5L19.6 5.29993C20 5.69993 20 6.29993 19.7 6.79993L18.9 7.90002C19.3 8.60002 19.7 9.39995 19.9 10.2L21.2 10.4C21.7 10.5 22.1 11 22.1 11.5ZM12.1 8.59998C10.2 8.59998 8.6 10.2 8.6 12.1C8.6 14 10.2 15.6 12.1 15.6C14 15.6 15.6 14 15.6 12.1C15.6 10.2 14 8.59998 12.1 8.59998Z" fill="currentColor"/>
																	<path d="M17.1 12.1C17.1 14.9 14.9 17.1 12.1 17.1C9.30001 17.1 7.10001 14.9 7.10001 12.1C7.10001 9.29998 9.30001 7.09998 12.1 7.09998C14.9 7.09998 17.1 9.29998 17.1 12.1ZM12.1 10.1C11 10.1 10.1 11 10.1 12.1C10.1 13.2 11 14.1 12.1 14.1C13.2 14.1 14.1 13.2 14.1 12.1C14.1 11 13.2 10.1 12.1 10.1Z" fill="currentColor"/>
																</svg>
															</span>
														</a>
														<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
															<div class="menu-item px-3">
																<a href="{{route('user-management.client.profile',['tab' =>"summary",'id' => Crypt::encrypt($client->id)])}}" class="menu-link px-3">View Profile</a>
															</div>
														</div>
													</td>
												</tr>
											@endforeach
            							</tbody>
            						</table>
                                </div>
                                <div class="mt-3">
									@if($filters['rows'] != "All")
                                        {{ $clients->onEachSide(1)->links() }}
                                    @endif
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
        $('a.client').addClass('active');
   </script>
@endsection
@endsection
