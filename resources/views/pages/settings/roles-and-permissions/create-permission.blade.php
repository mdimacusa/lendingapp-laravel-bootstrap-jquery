@extends('layout.app')
@section('container')
	<div class="d-flex flex-column flex-column-fluid">
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Create Permission</h1>
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
			    <form method="POST" id="permission_form" enctype="multipart/form-data">
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
                                        <div class="col-md-4 col-sm-12">
                                            <div class="mb-5 fv-row">
                                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Permission</label>
                                                <input type="text" name="name" class="form-control permission_input" value="{{old('name')}}">
                                                @error("name")
                                                    <div class="text text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="mb-5 fv-row">
                                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Module</label>
                                                <input type="text" name="module" class="form-control permission_input" value="{{old('module')}}">
                                                @error("module")
                                                    <div class="text text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="mb-5 fv-row">
                                                <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Slug</label>
                                                <input type="text" name="slug" id="permission_slug" class="form-control" value="{{old('slug')}}" readonly>
                                                @error("slug")
                                                    <div class="text text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-5 fv-row">
                                        <button type="submit" class="btn btn-light-success ls-1 text-uppercase blocker fs-8 fw-bolder w-100">Submit</button>
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

        var old_slug = ""

        $('#permission_slug').on('input',function(e){
            $(this).val(old_slug);
        })
        $('#permission_slug').on('click',function(e){
            old_slug = $(this).val()
        })
        $(".permission_input").on('input',function(){
            let form        = $("#permission_form");
            let pattern     = /[^a-zA-Z0-9\s\-\_]/g;
            let pattern2    = /[\s\-\_]/g;
            let pattern3    = /[\-\-\-]+/g;
            $(this).val($(this).val().replace(pattern, ''));

            let module      = form.find('[name="module"]');
            let name        = form.find('[name="name"]');
            // alert(form);
            let module_slug = module.val().replace(pattern2,'-').replace(pattern3,'-').toLowerCase()
            let name_slug   = name.val().replace(pattern2,'-').replace(pattern3,'-').toLowerCase()

            $("#permission_slug").val(name_slug + "." + module_slug)
        });
    </script>
@endsection
@endsection
