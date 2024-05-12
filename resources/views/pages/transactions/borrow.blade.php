@extends('layout.app')
@section('container')
<link rel="stylesheet" href="{{asset('assets/numpad/numpad-light.css')}}"/>
	<div class="d-flex flex-column flex-column-fluid">
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Borrow</h1>
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
			    <form method="POST" id="borrowForm" action="{{route('transactions.borrow-store')}}" enctype="multipart/form-data">
			        @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-12">
                            <span class="d-flex mb-5 position-relative">
                                <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
                                    Client Details
                                </span>
                                <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>

                            </span>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-5 fv-row">
                                        <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Client Unique ID</label>
                                        <input type="text" name="unique_id" id="unique_id" class="form-control" value="{{old('unique_id')}}" placeholder="Enter Client Unique ID" onkeyup="showClient(this)">
                                        <small style="font-size:15px" id="client_detail"></small>
                                        @error("unique_id")
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-5 fv-row">
                                        <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Rate</label>
                                        <select class="form-select btn-sm" name="rate" id="rate" data-control="select2" data-hide-search="true">
                                            @foreach($rates as $rate)
                                                <option value="{{$rate->id}}" data-rate="{{$rate->rate}}" {{old('rate') == $rate->id ? 'selected' : ''}}>{{str_replace(['0.0','0.'], '', $rate->rate)}} %</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-5 fv-row">
                                        <label class="form-label mb-3 fs-9 fw-bold text-uppercase  required">Tenurity</label>
                                        <select class="form-select btn-sm" name="tenurity" id="tenurity"  data-control="select2"  data-hide-search="true" value="">
                                            <option value="1" {{old('tenurity') == 1 ? 'selected' : ''}}>1 Month</option>
                                            <option value="2" {{old('tenurity') == 2 ? 'selected' : ''}}>2 Month`s</option>
                                            <option value="3" {{old('tenurity') == 3 ? 'selected' : ''}}>3 Month`s</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-5 fv-row">
                                        <label class="form-label mb-3 fs-9 fw-bold text-uppercase  required">Disbursement Date</label>
                                        <input type="datetime-local" name="disbursement_date" id="disbursement_date" class="form-control" value="{{old('disbursement_date')}}">
                                        @error("disbursement_date")
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-5 fv-row">
                                        <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Amount</label>
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Enter amount"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{old('amount')}}">
                                            @error("amount")
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-5 fv-row">
                                        <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Pincode</label>
                                        <input type="password" name="pincode" id="pincode" class="form-control" value="">
                                        @error("pincode")
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6 col-12">
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                                <div class="mb-4">
                                                    <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Valid ID from Agreement</label>
                                                    <input type="file" name="valid_id" class="form-control" accept="image/jpeg, image/png, image/jpg">
                                                    @error("valid_id")
                                                        <div class="text text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12  col-12">
                                                <div class="mb-4">
                                                    <label class="form-label fs-7">Upload Proof of Valid ID must contain signature and name
                                                        <br>
                                                        File Format: JPG / PNG / JPEG
                                                        <br>
                                                        Maximum image size: 1MB
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-12">
                                                <div class="mb-4">
                                                    <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Form Agreement PDF</label>
                                                    <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                                                    @error("pdf_file")
                                                        <div class="text text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12  col-12">
                                                <div class="mb-4">
                                                    <label class="form-label fs-7">Upload Proof of PDF file Agreement
                                                        <br>
                                                        File Format: PDF
                                                        <br> Maximum image size: 1MB
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-center col-12">
                                        <div class="mb-4">
                                            <div class="upload-title text-muted fs-6 mb-5">Sample VALID ID with SIGNATURE and Name</div>
                                            <img src="{{asset('assets/images/id_with_signature.jpg')}}" id="preview" width="220px" style="border: 1px dotted #555;">

                                        </div>
                                    </div>
                                </div>

                            <div class="separator separator-dashed my-3"></div>
                            <div class="mb-5 fv-row">
                                <a id="submit" class="btn btn-light-success ls-1 text-uppercase blocker fs-8 fw-bolder w-100">Borrow</a>
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
                                <div class="text-gray-700 fw-semibold fs-6 me-2">Interest</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-6"><span id="interest">₱0.00</span></span>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-3"></div>

                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6 me-2">Loan Outstanding</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-6"><span id="loanoutstanding">₱0.00</span></span>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>

                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6 me-2">Monthly</div>
                                <div class="d-flex align-items-senter">
                                    <span class="text-gray-900 fw-bolder fs-6"><span id="monthly">₱0.00</span></span>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>

                            {{-- <div class="d-flex align-items-center mb-8">
                                <div class="form-check form-check-custom form-check-solid me-4">
                                    <input class="form-check-input " type="checkbox" name="consent">
                                </div>
                                 <div class="flex-grow-1 text-gray-800 fs-6" style="text-align:justify">
                                    By clicking the box, you are confirming that you have read, understood, and agree to Angels Lending and
                                    Angels Mini Lending Corporation's <a target="_blank" href="">CONSENT FORM</a>
                                </div>
                            </div>--}}
                        </div>
                    </div>
				</form>
			</div>
		</div>
	</div>
@section('custom')
 	<script>
        $('a.borrow').addClass('active');
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
    </script>
    <script>
        $(document).ready(function(){
            $("#submit").click(function(){

                var clientUniqueID = $("#client_detail").text();
                var clientRate = $("#rate").find("option:selected").data("rate").toString();
                var clientTenurity = $("#tenurity").val();
                var clientDisbursementDate = $("#disbursement_date").val();
                var clientAmount = $("#amount").val();
                var dateTime = new Date(clientDisbursementDate);

                let rate = $('#rate option:selected').data('rate')
                let interest = (clientAmount*rate)*clientTenurity
                let loanoutstanding = parseFloat(clientAmount)+parseFloat(interest)
                let monthly = loanoutstanding/clientTenurity

                $("#displayClientUniqueID").empty();
                $("#displayClientRate").empty();
                $("#displayClientTenurity").empty();
                $("#displayClientDisbursementDate").empty();
                $("#displayClientAmount").empty();
                $("#displayClientInterest").empty();
                $("#displayClientOutstanding").empty();
                $("#displayClientMonthly").empty();

                $("#displayClientUniqueID").html((clientUniqueID==="")?"Invalid Client Name":clientUniqueID);
                $("#displayClientRate").html(clientRate.replace(/0\.0|0./g, '')+'%');
                $("#displayClientTenurity").html((clientTenurity==1)?clientTenurity+' Month':clientTenurity+' Month`s');
                $("#displayClientDisbursementDate").html(dateTime.toLocaleString());
                $("#displayClientAmount").html((!clientAmount)?'Invalid Amount':'₱'+clientAmount);
                $("#displayClientInterest").html((!interest)?'Invalid Interest':'₱'+interest.toFixed(2));
                $("#displayClientOutstanding").html((!loanoutstanding)?'Invalid Loan Outstanding':'₱'+loanoutstanding.toFixed(2));
                $("#displayClientMonthly").html((!monthly)?'Invalid Monthly':'₱'+monthly.toFixed(2));

                $('#borrow_confirmation').modal("show");
                ;

            });
            $("#btn-borrow-confirmation").click(function(){
                $("#borrowForm").submit();
            });

        });
    </script>
    <script>
    $("#rate").change(function(){
        let rate = $('#rate option:selected').data('rate')
        let tenurity = $("#tenurity").val()
        let amount = $("#amount").val()
        let interest = (amount*rate)*tenurity
        let loanoutstanding = parseFloat(amount)+parseFloat(interest)
        let monthly = loanoutstanding/tenurity
        $("#interest").html(isNaN(interest.toFixed(2))?"₱0.00":'₱'+interest.toFixed(2))
        $("#loanoutstanding").html(isNaN(loanoutstanding.toFixed(2))?"₱0.00":'₱'+loanoutstanding.toFixed(2))
        $("#monthly").html(isNaN(monthly.toFixed(2))?"₱0.00":'₱'+monthly.toFixed(2))
    })

    $("#tenurity").change(function(){
        let rate = $('#rate option:selected').data('rate')
        let tenurity = $("#tenurity").val()
        let amount = $("#amount").val()
        let interest = (amount*rate)*tenurity
        let loanoutstanding = parseFloat(amount)+parseFloat(interest)
        let monthly = loanoutstanding/tenurity
        $("#interest").html(isNaN(interest.toFixed(2))?"₱0.00":'₱'+interest.toFixed(2))
        $("#loanoutstanding").html(isNaN(loanoutstanding.toFixed(2))?"₱0.00":'₱'+loanoutstanding.toFixed(2))
        $("#monthly").html(isNaN(monthly.toFixed(2))?"₱0.00":'₱'+monthly.toFixed(2))
    })

    $("#amount").keyup(function(){
        let rate = $('#rate option:selected').data('rate')
        let tenurity = $("#tenurity").val()
        let amount = $("#amount").val()
        let interest = (amount*rate)*tenurity
        let loanoutstanding = parseFloat(amount)+parseFloat(interest)
        let monthly = loanoutstanding/tenurity
        $("#interest").html(isNaN(interest.toFixed(2))?"₱0.00":'₱'+interest.toFixed(2))
        $("#loanoutstanding").html(isNaN(loanoutstanding.toFixed(2))?"₱0.00":'₱'+loanoutstanding.toFixed(2))
        $("#monthly").html(isNaN(monthly.toFixed(2))?"₱0.00":'₱'+monthly.toFixed(2))

    })
    </script>
    <script>
    function showClient(el){
        var id = $(el).val()
        if(id==""){
             $("#client_detail").html("");
        }
        else{
            $.ajax({
                url:"{{route('transactions.borrow.client')}}/"+id,
                method:"GET",
                success:(function(data){
                    if(data.unique_id!=null){
                        $("#client_detail").html(data.first_name +" "+ data.surname).addClass('text-success').removeClass('text-danger');
                    }
                    else{
                        $("#client_detail").html("No client found.").addClass('text-danger').removeClass('text-success');
                    }
                })
            });
        }
    }
    </script>
    @if(old('unique_id'))
    <script>
        let id = parseInt("{{old('unique_id')}}");
        $.ajax({
                url:"{{route('transactions.borrow.client')}}/"+id,
                method:"GET",
                success:(function(data){
                    if(data.unique_id!=null){
                        $("#client_detail").html(data.first_name +" "+ data.surname).addClass('text-success').removeClass('text-danger');
                    }
                    else{
                        $("#client_detail").html("No client found.").addClass('text-danger').removeClass('text-success');
                    }
                })
            });
    </script>
     @endif
    @if(old('amount'))
    <script>
        let rate = $('#rate option:selected').data('rate')
        let tenurity = $("#tenurity").val()
        let amount = $("#amount").val()
        let interest = (amount*rate)*tenurity
        let loanoutstanding = parseFloat(amount)+parseFloat(interest)
        let monthly = loanoutstanding/tenurity
        $("#interest").html(isNaN(interest.toFixed(2))?"₱0.00":'₱'+interest.toFixed(2))
        $("#loanoutstanding").html(isNaN(loanoutstanding.toFixed(2))?"₱0.00":'₱'+loanoutstanding.toFixed(2))
        $("#monthly").html(isNaN(monthly.toFixed(2))?"₱0.00":'₱'+monthly.toFixed(2))
    </script>
    @endif
@endsection
@endsection
