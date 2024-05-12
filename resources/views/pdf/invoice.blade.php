<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{asset('assets/images/angels-mini-lending.png')}}">
    <title>Angels Mini Lending Invoice</title>
</head>
<style>
   @page { margin: 0px; }
   body { margin: 0px; }
</style>
<body style="font-family:Tahoma, Geneva, sans-serif;letter-spacing:3px;margin-top:10px" onload="print()">
    <div style="margin-left:15px;margin-right:15px;">
        <div style="max-width:100%;height:auto;padding:100px;padding-top:0!important;padding-bottom:0!important">
            <center>
                <img align="center" src="{{ asset('assets/images/print-icon.png') }}" style="width:30%">
                   <p style="font-size:10px">Angels Mini Lending Transaction Summary</p><br>
            </center>
            <p align="center" style="font-size:9px;"><i>Admin's Copy</i></p>
            <span style="display:block;border-bottom:1.6px dashed #000000;"></span>
            <div style="margin:auto">
                <table width="100%" cellpadding="0" cellspacing="0" style="line-height:2.8;">
                    <tr style="border-bottom:2px solid #F0F0F0;">
                        <td style="font-size:9px;">Reference Number:</td>
                        <td align="right"style="font-size:9px;"><strong>{{$transactions->reference}}</strong></td>
                    </tr>
                    <tr style="border-bottom:2px solid #F0F0F0;">
                        <td style="font-size:9px;">Name:</td>
                        <td align="right" style="font-size:9px;"><strong>{{$transactions->fullname}}</strong></td>
                    </tr>
                    <tr style="border-bottom:2px solid #F0F0F0;">
                        <td style="font-size:9px;">Tenurity: </td>
                        <td align="right" style="font-size:9px;"><strong>{{$transactions->tenurity}} {{$transactions->tenurity!='1'?'months':'month'}}</strong></td>
                    </tr>
                    <tr style="border-bottom:2px solid #F0F0F0;">
                        <td style="font-size:9px;">Rate: </td>
                        <td align="right" style="font-size:9px;"><strong>{{str_replace(['0.0','0.'], '', $transactions->rate)}} %</strong></td>
                    </tr>
                    <tr >
                        <td style="font-size:10px;">Principal Amount:</td>
                        <td align="right" style="font-size:10px;"><strong><span style="font-family: DejaVu Sans; sans-serif;line-height:2">&#x20B1;</span>{{number_format($transactions->amount,2)}}</strong></td>
                    </tr>
                    <tr >
                        <td style="font-size:10px;">Total Amount:</td>
                        <td align="right" style="font-size:10px;"><strong><span style="font-family: DejaVu Sans; sans-serif;line-height:2">&#x20B1;</span>{{number_format($transactions->amount + $transactions->interest,2)}}</strong></td>
                    </tr>
                </table>
            </div>
            <span style="display:block;border-bottom:1.6px dashed #000000;margin-top:5px"></span>
            <p style="font-size:9px;">Date of Transaction: <strong>{{date('M d, Y', strtotime($transactions->last_payment_date))}}</strong></p>
            <p style="font-size:9px;">IMPORTANT:<strong>This will serve as your proof of payment.</strong></p>
            <br>
                <div style="float:left;width:52%" >
                    <p style="font-size:9px;">Authorized Signature:</p>
                </div>
                <div style="float:right;width:48%">
                    <p style="font-size:9px;">Processed By: <strong>{{$transactions->name}}</strong></p>
                </div>

            {{-- <br><br><br><br>
                <div style="float:left;width:45%;border-top:1px solid black">
                    <p style="font-size:9px;"><i>Client's Signature over printed name</i></p>
                </div>
                <div style="float:right;width:48%;border-top:1px solid black">
                    <p style="font-size:9px;"><i>Authorized Representative's Signature over printed name</i></p>
                </div> --}}
         </div>
    </div>
    <br><br><br><br><br>
    <span style="display:block;border-bottom:1.6px dashed #000000;margin-bottom:10px"></span>
    <div style="margin-left:15px;margin-right:15px;">
        <div style="max-width:100%;height:auto;padding:100px;padding-top:0!important;padding-bottom:0!important">
            <center>
                <img align="center" src="{{ asset('assets/images/print-icon.png') }}" style="width:30%">
                   <p style="font-size:10px">Angels Mini Lending Transaction Summary</p><br>
            </center>
            <p align="center" style="font-size:9px;"><i>Admin's Copy</i></p>
            <span style="display:block;border-bottom:1.6px dashed #000000;"></span>
            <div style="margin:auto">
                <table width="100%" cellpadding="0" cellspacing="0" style="line-height:2.8;">
                    <tr style="border-bottom:2px solid #F0F0F0;">
                        <td style="font-size:9px;">Reference Number:</td>
                        <td align="right"style="font-size:9px;"><strong>{{$transactions->reference}}</strong></td>
                    </tr>
                    <tr style="border-bottom:2px solid #F0F0F0;">
                        <td style="font-size:9px;">Name:</td>
                        <td align="right" style="font-size:9px;"><strong>{{$transactions->fullname}}</strong></td>
                    </tr>
                    <tr style="border-bottom:2px solid #F0F0F0;">
                        <td style="font-size:9px;">Tenurity: </td>
                        <td align="right" style="font-size:9px;"><strong>{{$transactions->tenurity}} {{$transactions->tenurity!='1'?'months':'month'}}</strong></td>
                    </tr>
                    <tr style="border-bottom:2px solid #F0F0F0;">
                        <td style="font-size:9px;">Rate: </td>
                        <td align="right" style="font-size:9px;"><strong>{{str_replace(['0.0','0.'], '', $transactions->rate)}} %</strong></td>
                    </tr>
                    <tr >
                        <td style="font-size:10px;">Principal Amount:</td>
                        <td align="right" style="font-size:10px;"><strong><span style="font-family: DejaVu Sans; sans-serif;line-height:2">&#x20B1;</span>{{number_format($transactions->amount,2)}}</strong></td>
                    </tr>
                    <tr >
                        <td style="font-size:10px;">Total Amount:</td>
                        <td align="right" style="font-size:10px;"><strong><span style="font-family: DejaVu Sans; sans-serif;line-height:2">&#x20B1;</span>{{number_format($transactions->amount + $transactions->interest,2)}}</strong></td>
                    </tr>
                </table>
            </div>
            <span style="display:block;border-bottom:1.6px dashed #000000;margin-top:5px"></span>
            <p style="font-size:9px;">Date of Transaction: <strong>{{date('M d, Y', strtotime($transactions->last_payment_date))}}</strong></p>
            <p style="font-size:9px;">IMPORTANT:<strong>This will serve as your proof of payment.</strong></p>
            <br>
                <div style="float:left;width:52%" >
                    <p style="font-size:9px;">Authorized Signature:</p>
                </div>
                <div style="float:right;width:48%">
                    <p style="font-size:9px;">Processed By: <strong>{{$transactions->name}}</strong></p>
                </div>

            {{-- <br><br><br><br>
                <div style="float:left;width:45%;border-top:1px solid black">
                    <p style="font-size:9px;"><i>Client's Signature over printed name</i></p>
                </div>
                <div style="float:right;width:48%;border-top:1px solid black">
                    <p style="font-size:9px;"><i>Authorized Representative's Signature over printed name</i></p>
                </div> --}}
         </div>
    </div>
</body>
</html>


