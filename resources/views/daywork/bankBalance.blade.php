@extends('layouts.navAndside')

@section('content')
					<div class="col-md-8">
                        <h4 class="page-title">Add Bank Balance</h4> </div>
                        <ol class="breadcrumb col-md-4">
                            <li><a href="#">Daily Activities</a></li>
                            <li class="active">Add Bank Balance</li>
                        </ol>
                                    
<form style="width: 90% ; padding-top: 0.5%">
  <div class="form-group">
    <label for="exampleFormControlInput1">Bank Balance</label>
    <input type="text" class="form-control" id="customer_no" name="bank_balance" placeholder="123456.78">
  </div>
  
  <div class="form-group" >
  	<input type="submit" name="submit"  id="submit">
  </div>
  
  </style>
</form>

@endsection