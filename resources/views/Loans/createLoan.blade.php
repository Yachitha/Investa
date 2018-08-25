@extends('layouts.navAndside')

@section('content')
					<div class="col-md-8">
                        <h4 class="page-title">Create New Loan</h4> </div>
                        <ol class="breadcrumb col-md-4">
                            <li><a href="#">Loan</a></li>
                            <li class="active">Create New Loan</li>
                        </ol>
                                    
<form style="width: 90% ; padding-top: 0.5%">
  <div class="form-group">
    <label for="exampleFormControlInput1">Customer Number</label>
    <input type="text" class="form-control" name="customer_no" placeholder="000">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Loan Number</label>
    <input type="text" class="form-control" name="loan_no" placeholder="000">
  </div>

  <!-- <div class="form-group">
    <label for="exampleFormControlInput1">Customer Name</label>
    <input type="text" class="form-control" id="name" placeholder="Example Name">
  </div> -->
  <div class="form-group">
    <label for="exampleFormControlInput1">Loan Amount</label>
    <input type="text" class="form-control" name="loan_amount" placeholder="123456.78">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Interest</label>
    <input type="email" class="form-control" name="interest_rate" placeholder="9%">
  </div>
  <div class="form-group">
    <label for="exampleFormControlSelect1">Number of Installments</label>
    <select class="form-control" name="no_of_installments">
      <option>30</option>
      <option>60</option>
      <option>90</option>
      <option>100</option>
      <option>120</option>
    </select>
  </div>

  <div class="form-group">
    <label for="exampleFormControlInput1">Installment amount</label>
    <input type="text" class="form-control" name="installment_amount" name="installment_amount" placeholder="1234.56">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Start Date</label>
    <input type="date" class="form-control" name="addLine1" name="start_date" placeholder="yy-mm-dd">
  </div>
    <div class="form-group">
    <label for="exampleFormControlInput1">End Date</label>
    <input type="date" class="form-control" name="addLine1" name="end_date" placeholder="yy-mm-dd">
  </div>
  
  <div class="form-group" >
  	<input type="submit" name="submit"  id="submit">
  </div>
  
  </style>
</form>

@endsection