@extends('layout.app')
@section('container')
	<div class="d-flex flex-column flex-column-fluid">
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Create Administrator</h1>
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<li class="breadcrumb-item text-muted">
							<a href="" class="text-muted text-hover-success">User Management</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<div id="kt_app_content_container" class="app-container container-fluid ">
                <form method="POST" action="{{route('user-management.administrator.store')}}">
			        @csrf
                    <span class="d-flex mb-5 position-relative">
                        <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
                            Administrator Details
                        </span>
                        <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                    </span>
                    <input type="hidden" name="users_id" value="">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="mb-4">
                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">First Name</label>
                                <input type="text" name="first_name" value="{{old('first_name')}}" class="form-control">
                                @error("first_name")
                                    <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-4">
                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase">Middle Name</label>
                                <input type="text" name="middle_name" value="{{old('middle_name')}}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-4">
                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Surname</label>
                                <input type="text" name="surname" value="{{old('surname')}}" class="form-control">
                                @error("surname")
                                    <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-4">
                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Email</label>
                                <input type="email" name="email" value="{{old('email')}}" class="form-control">
                                @error("email")
                                    <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-4">
                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Pincode</label>
                                <input type="password" name="pincode" class="form-control" autocomplete="new-password" maxlength="4">
                                @error("pincode")
                                    <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-4">
                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Password</label>
                                <input type="password" name="password" class="form-control" autocomplete="new-password">
                                @error("password")
                                    <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-4">
                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                                @error("password_confirmation")
                                    <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    <div class="mb-5 fv-row">
                        <button type="submit" class="btn btn-light-success ls-1 text-uppercase blocker fs-8 fw-bolder w-100">Submit</button>
                    </div>
				</form>
			</div>
		</div>
	</div>
@section('custom')
 	<script>
        $('a.administrator').addClass('active');
    </script>

@endsection
@endsection
