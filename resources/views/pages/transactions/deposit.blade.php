@extends('layout.app')
@section('container')
<link rel="stylesheet" href="{{asset('assets/numpad/numpad-light.css')}}"/>
	<div class="d-flex flex-column flex-column-fluid">
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Deposit Fund</h1>
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<li class="breadcrumb-item text-muted">
							<a href="" class="text-muted text-hover-success">Transactions</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
			<div id="kt_app_content_container" class="app-container container-fluid ">
			    <form method="POST" id="borrowForm" action="{{route('transactions.deposit.store')}}">
			        @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-12">
                            <span class="d-flex mb-5 position-relative">
                                <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
                                    Fund Details
                                </span>
                                <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>

                            </span>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-5 fv-row">
                                        <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Enter amount"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{old('amount')}}">
                                            @error("amount")
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-5 fv-row">
                                        <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Pincode</label>
                                        <input type="password" name="pincode" id="pincode" class="form-control" placeholder="Enter pincode">
                                        @error("pincode")
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>

                            <div class="mb-5 fv-row">
                                <button type="submit" class="btn btn-light-success ls-1 text-uppercase blocker fs-8 fw-bolder w-100">Deposit</button>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <span class="d-flex mb-5 position-relative">
                                <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
                                    Summary
                                </span>
                                <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                            </span>
                            <div class="separator separator-dashed my-3"></div>

                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-3 me-2">Current Fund</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-3">₱{{number_format($total_fund,2)}}</span>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-3"></div>

                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6 me-2">Fund Added</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-6"><span id="fundAdded">₱0.00</span></span>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>

                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-4 me-2">Total Fund</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-4"><span id="totalFund">₱0.00</span></span>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                        </div>
                    </div>
				</form>
			</div>
		</div>
	</div>
@section('custom')
 	<script>
        $('a.deposit-fund').addClass('active');
    </script>
    <script src="{{asset('assets/numpad/numpad.js')}}"></script>
    <script>
    window.addEventListener("load", () => {
    // (C1) BASIC NUMPAD
        numpad.attach({
            target: document.getElementById("pincode"),
            mix:4,
            max:4,
        });

    });

    $("#amount").keyup(function(){
        let amount  = $("#amount").val()
        let current = "{{$total_fund}}";
        let total   = parseFloat(current)+parseFloat(amount);

        $("#fundAdded").html((amount==='')?"₱0.00":'₱'+amount)
        $("#totalFund").html(isNaN(total.toFixed(2))?"₱0.00":'₱'+total.toFixed(2))
    })
    </script>
@endsection
@endsection
