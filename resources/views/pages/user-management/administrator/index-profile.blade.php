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
                        @if($tab!="summary")
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
                        @endif
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group w-100 mb-5">
                            <a href="{{route('user-management.administrator.profile',['tab'=>'transaction','id'=>Crypt::encrypt($users->id)])}}" class="btn blocker btn-sm {{($tab=="transaction"?'btn-default':'btn-sm btn-light-success')}}">Transaction</a>
                            <a href="{{route('user-management.administrator.profile',['tab'=>'deposit','id'=>Crypt::encrypt($users->id)])}}" class="btn blocker btn-sm {{($tab=="deposit"?'btn-default':'btn-sm btn-light-success')}}">Deposit</a>
                            <a href="{{route('user-management.administrator.profile',['tab'=>'summary','id'=>Crypt::encrypt($users->id)])}}" class="btn blocker btn-sm {{($tab=="summary"?'btn-default':'btn-sm btn-light-success')}}">Summary</a>
                        </div>
                    </div>
                </div>
			    <div class="row g-5 g-xl-10 mb-xl-10 justify-content-center">
                    <div class="col-md-12">
                        @include('pages.user-management.administrator.tab')
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
@section('custom')
 	<script>
        $('a.administrator').addClass('active');
   </script>
@endsection
@endsection

