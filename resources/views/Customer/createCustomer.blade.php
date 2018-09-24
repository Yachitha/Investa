@extends('layouts.navAndside')

@section('content')
    <div class="col-md-8"> <h4 class="page-title">Create New Customer</h4> </div>
    <div>
        <ol class="breadcrumb col-md-4">
            <li><a href="#">Customer</a></li>
            <li class="active">Create New Customer</li>
        </ol>
    </div>
<div class="card" style="margin-top: 50px;">
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-success alert-dismissable" role="alert">
                {{ session('message') }}
            </div>
        @endif
            @if (session('error'))
                <div class="alert alert-warning" role="alert">
                    {{ session('error') }}
                </div>
            @endif
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
<form style="width: 90% ; padding-top: 0.5%"  action="/createCustomer" method="POST">
  @csrf
    <div class="form-group">
    <label for="exampleFormControlInput1">Customer Number</label>
    <input type="text" class="form-control" name="customer_no" placeholder="000">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Customer Name</label>
    <input type="text" class="form-control" name="name" placeholder="Example Name">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">NIC number</label>
    <input type="text" class="form-control" name="nic" placeholder="123456789V">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Telephone Number</label>
    <input type="text" class="form-control" name="contact_no" placeholder=" 07X XXX XXXX">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Address Line 1</label>
    <input type="text" class="form-control" name="addLine1" placeholder="No XXX , Such road, State">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Address Line 2</label>
    <input type="text" class="form-control" name="addLine2" placeholder="Road, Village, City">
  </div>
  <div class="form-group">
    <label for="exampleFormControlSelect1">CITY</label>
    <select class="form-control" name="city">
      <option>Karapitya</option>
      <option>Habaraduwa</option>
      <option>Ambalangoda</option>
      <option>Hikkaduwa</option>
      <option>Galle</option>
    </select>
  </div>
    <div class="form-group">
        <label for="salesRep">Sales Rep</label>
        <select name="salesRep_id" class="form-control" id="salesRep">
            {{ $salesReps = \App\User::select('id','name')->where('role_id','=','2')->get() }}
            @foreach( $salesReps as $salesRep)
                <option value='{{ $salesRep->id }}'>{{ $salesRep->name }}</option>
            @endforeach
        </select>
    </div>
  <div class="form-group" >
  	<input type="submit" name="submit"  id="submit">
  </div>
</form>

@endsection
