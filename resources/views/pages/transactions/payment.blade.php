@extends('layout.app')
@section('container')
<link rel="stylesheet" href="{{asset('assets/numpad/numpad-light.css')}}"/>
	<div class="d-flex flex-column flex-column-fluid">
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Payment</h1>
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
			    <form method="POST" action="{{url('transactions/statement-of-account/'.$status.'/payment/'.$query->reference)}}">
			        @csrf
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                 <div class="row justify-content-center">
                                    <div class="col-md-8 col-12">
                                        <span class="d-flex mb-5 position-relative">
                                            <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
                                                Payment Details
                                            </span>
                                            <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                                        </span>

                                        <div class="row">
                                            <div class="col-md-6 col-sm-12" id="payment-method">
                                                <div class="mb-5 fv-row">
                                                    <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Payment Type</label>
                                                    <select class="form-select btn-sm" name="payment_method" id="payment_method" data-control="select2"  data-hide-search="true">
                                                        <option value="Standard Payment" {{old('payment_method') == "Standard Payment" ? 'selected' : ''}}>Standard Payment</option>
                                                        @if($remaining_month!=1)
                                                        <option value="Interest" {{old('payment_method') == "Interest" ? 'selected' : ''}}>Interest</option>
                                                        @endif
                                                        <option value="Pay All" {{old('payment_method') == "Pay All" ? 'selected' : ''}}>Pay All</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12" id="payment-for">
                                                <div class="mb-5 fv-row">
                                                    <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Payment For</label>
                                                    <select type="" class="form-select btn-sm" id="interest" name="payment_for" data-control="select2"  data-hide-search="true">
                                                        @php
                                                            $x = $count_month;
                                                        @endphp
                                                        @for ($i = 1; $i <= $x; $i++)
                                                            <option value="{{ $i }}" id="{{ $i }}_interest_option" @selected(old('payment_for') == $i)>{{ $i }} {{$i!='1'?'month`s':'month'}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12" id="payment-date">
                                                <div class="mb-5 fv-row">
                                                    <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Payment Date</label>
                                                    <input type="date" name="payment_date" class="form-control" value="{{old('payment_date')}}">
                                                    @error("payment_date")
                                                        <div class="text text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="mb-5 fv-row">
                                                    <label class="form-label mb-3 fs-9 fw-bold text-uppercase">Remarks</label>
                                                    <textarea name="remarks" class="form-control" rows="3" cols="50">{{$query->remarks}} {{old('remarks')}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 totalPaymentAmount">
                                                <div class="mb-5 fv-row">
                                                    <label class="form-label mb-3 fs-9 fw-bold text-uppercase">Amount To Pay:</label><br>
                                                        <label class="text fs-7">
                                                            <span class="badge badge-light-primary fs-6" id="amount_to_pay">
                                                                ₱{{number_format($remaining_month==1 ? $query->loan_outstanding : $query->monthly,2)}}
                                                            </span>
                                                        </label>
                                                    <div class="separator separator-dashed mt-1"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="mb-5 fv-row">
                                                    <label class="form-label mb-3 fs-9 fw-bold text-uppercase required">Pincode</label>
                                                    <input type="password" name="pincode" id="pincode" class="form-control" value="">
                                                        @error("pincode")
                                                            <div class="text text-danger">{{ $message }}</div>
                                                        @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator separator-dashed my-3"></div>
                                        <div class="mb-5 fv-row">
                                            <button type="submit" class="btn btn-light-success ls-1 text-uppercase blocker fs-8 fw-bolder w-100">Pay Now</button>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <span class="d-flex mb-5 position-relative">
                                            <span class="d-inline-block mb-2 fs-5 text-uppercase fw-bold text-gray-900">
                                                Loan Details
                                            </span>
                                            <span class="d-inline-block position-absolute h-2px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                                        </span>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Account  #</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{$query->account_no}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Reference #</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{$query->reference}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Client Unique ID</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{$query->unique_id}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Fullname</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{$query->fullname}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Tenurity</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{$query->tenurity}} {{$query->tenurity=="1"?"Month":"Month`s"}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Remaining {{$remaining_month=="1"?"Month":"Month`s"}} To Pay</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">
                                                    {{--{{$remaining_month}} {{$remaining_month=="1"?"Month":"Month`s"}}--}}
                                                    {{$count_month}} {{$count_month!='1'?'Month`s':'Month'}}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Rate</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{$query->rate}} %</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Amount</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">₱{{$query->amount}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Monthly</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">₱{{round($query->monthly,2)}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Current Loan Outstanding</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">₱{{$query->loan_outstanding}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Disbursement Date</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{date('M d, Y', strtotime($query->disbursement_date))}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Last Payment Date</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{empty($data->last_payment_date)?"NO DATA":date('M d, Y', strtotime($data->last_payment_date))}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Upcoming Due Date</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{date('M d, Y', strtotime($query->upcoming_due_date))}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>

                                        <div class="d-flex flex-stack">
                                            <div class="text-gray-700 fw-semibold fs-6 me-2">Due Date</div>
                                            <div class="d-flex align-items-senter">
                                                <span class="text-gray-900 fw-bolder fs-6">{{date('M d, Y', strtotime($query->due_date))}}</span>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-3"></div>
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
        $('a.soa').addClass('active');
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
    $("#rate").change(function(){
        let rate = $("#rate").val()
        let tenurity = $("#tenurity").val()
        let amount = $("#amount").val()
        let interest = (amount*rate)*tenurity
        let loanoutstanding = parseFloat(amount)+parseFloat(interest)
        let monthly = loanoutstanding/tenurity
        $("#interest").val(isNaN(interest.toFixed(2))?"0.00":interest.toFixed(2))
        $("#loanoutstanding").val(isNaN(loanoutstanding.toFixed(2))?"0.00":loanoutstanding.toFixed(2))
        $("#monthly").val(isNaN(monthly.toFixed(2))?"0.00":monthly.toFixed(2))
    })

    $("#tenurity").change(function(){
        let rate = $("#rate").val()
        let tenurity = $("#tenurity").val()
        let amount = $("#amount").val()
        let interest = (amount*rate)*tenurity
        let loanoutstanding = parseFloat(amount)+parseFloat(interest)
        let monthly = loanoutstanding/tenurity
        $("#interest").val(isNaN(interest.toFixed(2))?"0.00":interest.toFixed(2))
        $("#loanoutstanding").val(isNaN(loanoutstanding.toFixed(2))?"0.00":loanoutstanding.toFixed(2))
        $("#monthly").val(isNaN(monthly.toFixed(2))?"0.00":monthly.toFixed(2))
    })

    $("#amount").keyup(function(){

        let rate = '{{$query->rate}}';
        let tenurity = '{{$query->tenurity}}';
        let amount = $("#amount").val();
        let interest = (amount*rate)*tenurity;
        let loanoutstanding = '{{$query->loan_outstanding}}';
        let monthly = loanoutstanding/tenurity;
        let balanceAmount = '{{$query->balance_amount}}';
        let newMonthly = '{{$query->monthly}}';

        let calculateAmount = (parseFloat(newMonthly)-parseFloat(amount))*parseFloat(rate);

        let calculateAmountBalance = (Number(newMonthly)-Number(amount)+Number(balanceAmount))*parseFloat(rate);

        let calculateAmountLoan = (Number(loanoutstanding)-Number(amount)+Number(balanceAmount))*parseFloat(rate);

        $("#interest").val(isNaN(interest.toFixed(2))?"0.00":interest.toFixed(2));
        // $("#loanoutstanding").val(isNaN(loanoutstanding.toFixed(2))?"0.00":loanoutstanding.toFixed(2));
        $("#monthly").val(isNaN(monthly.toFixed(2))?"0.00":monthly.toFixed(2));

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
                    if(data.client_id!=null){
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
    <script>
        $(document).ready(function(){
            let interest = parseFloat('{{$query->interest}}');
            let loan_outstanding = parseFloat('{{$query->loan_outstanding}}');
            let rate = parseFloat('{{$query->rate}}');
            let monthly = parseFloat('{{$query->monthly}}');
            let remaining_month = parseInt('{{$remaining_month}}');
            let additional_penalties = parseInt('{{$query->additional_interest_amount}}');

            $('#payment_method').on('change',function()
            {

                switch($(this).val())
                {
                    case "Standard Payment":
                        $('#payment-for').css('display','block');
                        $('#payment-method').removeClass( "col-md-12" ).addClass("col-md-6");
                        $('#payment-date').removeClass( "col-md-6" ).addClass("col-md-12");
                        $('#payment_for').css('display','block');
                        $("#amount_to_pay").text(monthly.toFixed(2));
                        $('#interest').prop('disabled', false);

                        if(remaining_month===1)
                        {
                            $("#amount_to_pay").text("₱"+(loan_outstanding).toFixed(2));
                        }
                        else
                        {
                            $("#amount_to_pay").text("₱"+(monthly).toFixed(2));
                        }

                        break;
                    case "Interest":

                        $('#payment-for').css('display','none');
                        $('#payment-date').removeClass( "col-md-12").addClass( "col-md-6");
                        if(remaining_month===1)
                        {
                            $("#amount_to_pay").text("₱"+(loan_outstanding).toFixed(2));
                        }
                        else
                        {
                            if(additional_penalties>0)
                            {
                                $("#amount_to_pay").text("₱"+((monthly*rate)+additional_penalties).toFixed(2));
                            }
                            else
                            {
                                $("#amount_to_pay").text("₱"+(monthly*rate).toFixed(2));
                            }


                        }
                        break;
                    default:
                        $("#amount_to_pay").text("₱"+(loan_outstanding).toFixed(2));
                        $('#payment-date').removeClass( "col-md-12").addClass( "col-md-6");
                        $('#payment-for').css('display','none');
                        $("#amount_to_pay").text("₱"+(loan_outstanding).toFixed(2));

                }

            });
            $('#interest').on('change',function()
            {
                if(remaining_month===1)
                {
                    $("#amount_to_pay").text("₱"+(loan_outstanding).toFixed(2));
                }
                else
                {
                    $("#amount_to_pay").text("₱"+(Math.round(monthly*$(this).val())).toFixed(2));
                }
            });
        });
    </script>
    <script>
        function CopyAmount(amount) {
            var ex = $('<input>').val(amount).appendTo('body').select();
            document.execCommand("copy");
            alert("Amount has been copied.");
            ex.remove();
        }
    </script>

    @if(old('payment_method') == "Standard Payment")
    <script>
        $(document).ready(function(){

            let interest = parseFloat('{{$query->interest}}');
            let loan_outstanding = parseFloat('{{$query->loan_outstanding}}');
            let rate = parseFloat('{{$query->rate}}');
            let monthly = parseFloat('{{$query->monthly}}');
            let remaining_month = parseInt('{{$remaining_month}}')
            $('#payment-for').css('display','block');
            $('#payment-method').removeClass( "col-md-12" ).addClass("col-md-6");
            $('#payment-date').removeClass( "col-md-6" ).addClass("col-md-12");
            $('#payment_for').css('display','block');
            $("#amount_to_pay").text(monthly.toFixed(2));
            $('#interest').prop('disabled', false);

            if(remaining_month===1)
            {
                $("#amount_to_pay").text("₱"+(loan_outstanding).toFixed(2));
            }
            else
            {
                $("#amount_to_pay").text("₱"+(Math.round(monthly*parseInt('{{old('payment_for')}}'))).toFixed(2));

            }
        });
    </script>
    @endif

    @if(old('payment_method') == "Interest")
    <script>

        $(document).ready(function(){
            let interest = parseFloat('{{$query->interest}}');
            let loan_outstanding = parseFloat('{{$query->loan_outstanding}}');
            let initial_rate = 0.01;
            let monthly = parseFloat('{{$query->monthly}}');
            let remaining_month = parseInt('{{$remaining_month}}')

            $("#amount_to_pay").text("₱"+(loan_outstanding).toFixed(2));
            $('#payment-date').removeClass( "col-md-12").addClass( "col-md-6");
            $('#payment-for').css('display','none');

            if(remaining_month===1)
            {
                $("#amount_to_pay").text("₱"+(loan_outstanding).toFixed(2));
            }
            else
            {
                $("#amount_to_pay").text("₱"+(((loan_outstanding-interest)*initial_rate)+interest).toFixed(2));
            }
        });
    </script>
    @endif

    @if(old('payment_method') == "Pay All")
    <script>
        $(document).ready(function(){
            let interest = parseFloat('{{$query->interest}}');
            let loan_outstanding = parseFloat('{{$query->loan_outstanding}}');
            let initial_rate = 0.01;
            let monthly = parseFloat('{{$query->monthly}}');
            let remaining_month = parseInt('{{$remaining_month}}')

            $("#amount_to_pay").text("₱"+(((loan_outstanding-interest)*initial_rate)+interest).toFixed(2));
            $('#payment-for').css('display','none');
            $('#payment-date').removeClass( "col-md-12").addClass( "col-md-6");

            $("#amount_to_pay").text("₱"+(loan_outstanding).toFixed(2));
        });
    </script>
    @endif




@endsection
@endsection
