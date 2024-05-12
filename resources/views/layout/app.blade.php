<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{asset('assets/images/angels-mini-lending.png')}}">
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Angel's Mini Lending</title>
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">

	<!--begin::Theme mode setup on page load-->
	<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-theme-mode")) { themeMode = document.documentElement.getAttribute("data-theme-mode"); } else { if ( localStorage.getItem("data-theme") !== null ) { themeMode = localStorage.getItem("data-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-theme", themeMode); }</script>
	<!--end::Theme mode setup on page load-->

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            @include('layout.top-navigation')
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('layout.navigation')
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    @yield('container')
                    @include('layout.footer')
                </div>
            </div>
         </div>
    </div>

    <div class="toast bg-light" id="toastElement" role="alert" aria-live="assertive" aria-atomic="true" style="position:absolute;bottom:35px;right:7px;z-index:999">
        <div class="toast-header">
            <i class="ki-duotone ki-abstract-39 fs-2 text-primary me-3"><span class="path1"></span><span class="path2"></span></i>
            <strong class="me-auto">Keenthemes</strong>
            <small>11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <span id="realtimeMsg"></span>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="borrow_confirmation">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Loan Details</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>

                <div class="modal-body p-6">

                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Client Name</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6"><span id="displayClientUniqueID"></span></span>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-3"></div>

                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Rate</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6"><span id="displayClientRate"></span></span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>

                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Tenurity</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6"><span id="displayClientTenurity"></span></span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Disbursement Date</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6"><span id="displayClientDisbursementDate"></span></span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Amount</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6"><span id="displayClientAmount"></span></span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Interest</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6"><span id="displayClientInterest"></span></span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Loan Outstanding</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6"><span id="displayClientOutstanding"></span></span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Monthly</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6"><span id="displayClientMonthly"></span></span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn-borrow-confirmation">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
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
            $("#password-lock-confirmation").click(function(){
                var x = document.getElementById("password-confirmation");
                if (x.type === "password") {
                    x.type = "text";
                    $(this).removeClass('far fa-eye').addClass('far fa-eye-slash')
                } else {
                    x.type = "password";
                    $(this).removeClass('far fa-eye-slash').addClass('far fa-eye')
                }
		    })
	</script>
    <script>
        $(document).ready(function(){
            $("#seen").click(function(event){
                event.preventDefault();
                var link = $(this).attr("href");
                $.ajax({
                    url: link,
                    type: 'GET',
                    success: function(data) {
                        location.reload();
                    }
                });
            });
        });
    </script>
    @if(Session::has('swal.message'))
        <script>
        Swal.fire({
            html: "<span class='fs-8 text-uppercase fw-bold'>{!!Session::get('swal.message')!!}</span>",
            icon: "info",
            customClass: {
                confirmButton: "btn btn-primary btn-sm"
            }
        });
        </script>
    @endif
    @if(Session::has('swal.success'))
        <script>
        Swal.fire({
            html: "<span class='fs-8 text-uppercase fw-bold'>{!!Session::get('swal.success')!!}</span>",
            icon: "success",
            customClass: {
                confirmButton: "btn btn-success btn-sm"
            }
        });
        </script>
    @endif

    @if(Session::has('swal.error'))
        <script>
        Swal.fire({
            html: "<span class='fs-8 text-uppercase fw-bold'>{!!Session::get('swal.error')!!}</span>",
            icon: "error",
            customClass: {
                confirmButton: "btn btn-success btn-sm"
            }
        });
        </script>
    @endif

    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('d89b2afbc344cd86fa73', {
        cluster: 'ap1'
        });

        var channel = pusher.subscribe('borrow-channel');
        channel.bind('borrow-event', function(data) {
            $('#realtimeMsg').html(data.message);
            var a = document.getElementById('toastElement');
            setTimeout(() => {
                a.classList.add("show")
            }, 250);
        });

    </script>

    @yield('custom')

</body>

</html>
