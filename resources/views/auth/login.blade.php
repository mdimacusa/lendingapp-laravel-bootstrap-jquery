<!DOCTYPE html>
<html lang="en">
    <head>
		<title>Angel's Mini Lending</title>
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
		<link rel="icon" href="{{asset('assets/images/angels-mini-lending.png')}}">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	</head>
    <body id="kt_body" class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<style>
                body
                {
                    background-image: url('assets/images/bg1.jpg');
                }
            </style>
			<div class="d-flex flex-column flex-center flex-column-fluid">
				<div class="d-flex flex-center w-lg-50 p-10">
                    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
                        <!--begin::Card-->
                        <div class="bg-body d-flex flex-center flex-column align-items-stretch flex-center rounded-4 w-md-450px p-8">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">

                                <!--begin::Form-->
                                <form method="POST" class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" action="{{ route('login') }}">
                                    @csrf
                                    <div class="text-center mb-20">
                                            <img alt="Logo" src="{{asset('assets/images/print-icon.png')}}" width="220"/>
                                    </div>
                                    <div class="fv-row mb-8 fv-plugins-icon-container">
                                        <input type="email" placeholder="Email" name="email" class="form-control bg-transparent @error('email') is-invalid @enderror">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-3 fv-plugins-icon-container">
                                        <div class="input-group">
                                            <span class="input-group-text border-right-0" >
                                                <a style="cursor:pointer" >
                                                    <i id="password-lock" class="far fa-eye"></i>
                                                </a>
                                            </span>
                                            <input type="password" placeholder="Password" id="password" name="password" class="form-control  bg-transparent @error('email') is-invalid @enderror" />
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                        <div></div>
                                        <a href="{{ route('password.request') }}" class="link-primary">
                                            Forgot Password ?
                                        </a>
                                    </div>
                                    <div class="d-grid mb-10">
                                        <button type="submit" class="btn btn-success">
                                            <span class="indicator-label">Sign In</span>
                                        </button>
                                    </div>
                                    {{-- <div class="text-gray-500 text-center fw-semibold fs-6">
                                        Not a Member yet?

                                        <a href="/metronic8/demo1/authentication/layouts/creative/sign-up.html" class="link-primary">
                                            Sign up
                                        </a>
                                    </div> --}}
                                </form>
                                <!--end::Form-->

                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Footer-->
                            <div class="d-flex flex-stack px-lg-10">
                                <div class="d-flex fw-semibold text-primary fs-base gap-5">
                                    <a href="/metronic8/demo1/pages/team.html" target="_blank">Terms</a>

                                    <a href="/metronic8/demo1/pages/contact.html" target="_blank">Contact Us</a>
                                </div>
                                <!--end::Links-->
                            </div>
                            <!--end::Footer-->
                        </div>
                        <!--end::Card-->
                    </div>
				</div>
			</div>

		</div>
		<script>var hostUrl = "assets/";</script>
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<script src="assets/js/custom/authentication/sign-in/general.js"></script>
        <script src="https://www.google.com/recaptcha/api.js"></script>
	</body>
    <script>
        $("#password-lock").click(function(){
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
                $(this).removeClass('far fa-eye').addClass('far fa-eye-slash')
            } else {
                x.type = "password";
                $(this).removeClass('far fa-eye-slash').addClass('far fa-eye')
            }
        })
    </script>
</html>
