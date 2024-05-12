@extends('layout.app')
@section('container')
	<div class="d-flex flex-column flex-column-fluid">
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Edit Permission</h1>
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<li class="breadcrumb-item text-muted">
							<a href="" class="text-muted text-hover-success">Settings</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<div id="kt_app_content_container" class="app-container container-fluid ">
			    <form method="POST">
			        @csrf
                    <div class="card">
    					<div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-12 col-12">
                                    <span class="d-flex mb-5 position-relative">
                                        <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
                                            Roles And Permissions
                                        </span>
                                        <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                                    </span>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="mb-4">
                                                <label class="form-label">{{__('Role Name')}}:</label>
                                                <input type="text" class="form-control" value="{{$role->title}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-0">
                                        <h2>Permissions</h2>
                                        <div class="d-flex justify-content-end">
                                            <div>
                                                <a href="#" onclick="$('input[type=\'checkbox\']').attr('checked',true);">Select All</a> |
                                                <a href="#" onclick="$('input[type=\'checkbox\']').removeAttr('checked',true);">Deselect All</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed mb-3"></div>
                                    <div class="row">
                                        @foreach($permissions as $key => $module)
                                            <div class="col-md-4 col-12 mb-2">
                                            <h3 class="fs-6 fw-semibold mb-4">{{$module->module}}</h3>
                                            <div class="separator separator-dashed my-3"></div>
                                                <div class="row mb-4">
                                                    @foreach($module->permission as $permission)
                                                    <div class="col-12">

                                                        <div class="form-check form-check-inline mb-2">
                                                            <input class="form-check-input" type="checkbox" name="permissions[{{$permission->id}}]" id="permission_read_write_{{$permission->id}}" value="1" {{$permission->status === 1? 'checked' : ''}}>
                                                            <label class="form-check-label" for="permission_read_write_{{$permission->id}}">{{$permission->name}}</label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mb-5">
                                        <button type="submit" class="btn btn-light-success ls-1 text-uppercase blocker fs-8 fw-bolder w-100">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</form>
			</div>
		</div>
	</div>
@section('custom')
 	<script>
        $('a.roles-and-permissions').addClass('active');

    </script>
@endsection
@endsection
