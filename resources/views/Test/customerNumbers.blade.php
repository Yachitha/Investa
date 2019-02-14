@extends('layouts.navAndside')

@section('content')
    <div class="col-md-8">
        <h4 class="page-title">Customer Numbers</h4>
    </div>
    <ol class="breadcrumb col-md-4">
        <li><a href="#">Customer</a></li>
        <li class="active">Customer Numbers</li>
    </ol>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4>Customers</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="card border-danger col-md-12">
                            <div class="card-header bg-danger">
                                <h4>Customer Numbers</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-secondary">
                                    <thead>
                                    <tr>
                                        <th scope="col">Customer No</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">NIC</th>
                                    </tr>
                                    </thead>
                                    @if(!$error)
                                    @if($customers)
                                        @foreach($customers as $customer)
                                            <tbody>
                                                <tr>
                                                    <th scope="row">{{ $customer->customer_no }}</th>
                                                    <td>{{ $customer->name }}</td>
                                                    <td>{{ $customer->NIC }}</td>
                                                </tr>
                                            </tbody>
                                        @endforeach
                                        @else
                                        <p>Customer List Empty</p>
                                    @endif
                                        @else
                                            {{ $message }}
                                        @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
