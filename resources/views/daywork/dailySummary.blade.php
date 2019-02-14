@extends('layouts.navAndside')

@section('content')
    <div class="col-md-8">
        <h4 class="page-title">Daily Summary</h4>
    </div>
    <ol class="breadcrumb col-md-4">
        <li><a href="#">Daily Activities</a></li>
        <li class="active">Daily Summary</li>
    </ol>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4>Summary</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($sorted)
                            @foreach($sorted as $key=>$items)
                                <div class="card border-danger col-md-6">
                                    <div class="card-header bg-danger">
                                        <h4>{{ $key }} Summary</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped table-secondary">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Customer No</th>
                                                <th scope="col">Amount(LKR)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php($total=0.0)
                                            @foreach($items as $innerKey=>$value)
                                                <tr>
                                                    <th scope="row">{{ $innerKey+1 }}</th>
                                                    <td>{{ $value['customer_no'] }}</td>
                                                    <td>{{ number_format ($value['repayment']->amount,2) }}</td>
                                                </tr>
                                                @php($total+=($value['repayment']->amount))
                                            @endforeach
                                                <tr class="">
                                                    <th scope="row" colspan="2">Total(LKR)</th>
                                                    <td>{{ number_format ($total,2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
