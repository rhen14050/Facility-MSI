<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    margin:0;
    padding:0;
    background:#ffffff;
    font-family: Arial, Helvetica, sans-serif;
}

.container{
    max-width:600px;
    margin:auto;
}

.header{
    text-align:center;
    padding:20px 15px;
}

.title{
    text-align:center;
    font-size:18px;
    font-weight:bold;
}

.text-center{
    text-align:center;
}

.details{
    margin:auto;
    font-size:12px;
    border-collapse: collapse;
}

.details td{
    padding:6px 10px;
    border:1px solid #ddd;
}

.button{
    display:inline-block;
    background:#4A90E2;
    color:#ffffff !important;
    padding:10px 26px;
    border-radius:24px;
    font-size:13px;
}

.divider{
    border-top:1px solid #000;
    margin:20px 0;
}

.footer{
    font-size:11px;
    line-height:1.5;
    padding:15px;
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>Facility MSI Inventory System</h2>
    </div>

    <div class="title">
        Withdrawal Request for Approval
    </div>

    <br>

    <div class="text-center">
        <b>
            <p style="font-size:12px;">
                Hi, you have a withdrawal request pending approval. See details below:
            </p>
        </b>

        <br>

        <table class="details">
            <tr>
                <td><strong>Item Name</strong></td>
                <td>{{ $emailData['item_name'] }}</td>
            </tr>
            <tr>
                <td><strong>Description</strong></td>
                <td>{{ $emailData['item_description'] }}</td>
            </tr>
            <tr>
                <td><strong>Request Withdrawal Quantity</strong></td>
                <td>{{ $emailData['qty'] }}</td>
            </tr>
            <tr>
                <td><strong>Transaction Date</strong></td>
                <td>{{ $emailData['transaction_date'] }}</td>
            </tr>
            <tr>
                <td><strong>Current EOH</strong></td>
                {{-- <td>{{ number_format($emailData['current_eoh'], 2) }}</td> --}}
                <td>{{ $emailData['current_eoh']}}</td>
            </tr>
             <tr>
                <td><strong>Remarks</strong></td>
                <td>{{ $emailData['remarks'] }}</td>
            </tr>
            
        </table>

        <br><br>

        <a href="http://rapidx/">Login to RapidX to access Facility MSI Inventory System</a>
        {{-- <a href="{{ config('app.url') }}" class="button" target="_blank">
            Open Facility MSI Inventory System
        </a> --}}

    </div>

    <div class="divider"></div>

    <div class="footer">
        <p class="text-center">
            <strong><em>If there are any concerns, please contact the ISS team.</em></strong>
        </p>

        <p style="text-align:justify;">
        Notice of Disclaimer:<br>
        This message contains confidential information intended for a specific individual and purpose,
        and is protected by law. If you are not the intended recipient, you should delete this message.
        Any disclosure, copying, or distribution of this message, or the taking of any action based on it,
        is strictly prohibited.
        </p>
    </div>

</div>

</body>
</html>