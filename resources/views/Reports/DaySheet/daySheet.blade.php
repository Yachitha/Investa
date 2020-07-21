<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Day Sheet</title>
    <link href="{{ url ('pixeladmin-lite/html/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <style type="text/css">
        @page {
            margin: 5px;
        }

        body {
            margin: 0;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        .summary table , th,td {
            font-size: small;
            border: 1px solid black;
            text-align: center;
            line-height: 40px;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        h3 {
            font-weight: bolder;
        }

        .invoice table {
            margin: 15px;
        }

        .invoice h3 {
            margin-left: 15px;
        }

        .information {
            background-color: #60A7A6;
            color: #FFF;
        }

        .information table {
            padding: 10px;
        }
    </style>

</head>
<body>
<p>Hello world</p>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <h3>SOUTHERN INVESTMENT</h3>
        </div>
        <div class="col-md-6 pull-right text-right">
            <h3>Day Sheet- {{$date}}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <p>Route: {{$route_name}}</p>
        </div>
        <div class="col-lg-4 text-center">
            <p>Sales Rep: {{$sales_rep}}</p>
        </div>
        <div class="col-lg-4 pull-right text-right">
            <p>Due Total Collection: {{$due_total}}</p>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="summary col-lg-12">
            <table width="100%">
                <thead>
                <tr>
                    <th>BF Amount</th>
                    <th>Loan Payment</th>
                    <th>Loan Investment</th>
                    <th>Extra Expenses</th>
                    <th>Balance</th>
                    <th>Access</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$bf_amount}}</td>
                    <td>{{$total_col}}</td>
                    <td>{{$loan_cash+$loan_che}}</td>
                    <td>{{$extra_ex}}</td>
                    <td>{{$balance}}</td>
                    <td>{{$access}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br/>
<div class="data">

</div>

<div class="information" style="position: absolute; bottom: 0; width: 100%">

</div>
<script src="{{ url ('pixeladmin-lite/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ url ('pixeladmin-lite/html/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>
</html>
