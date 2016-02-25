@extends('layouts.protected')
@section('content')
<div class="container">
<h1 style="text-align:center;">Add Alert Type</h1>
	       <div>                      
									{{ Form::open(array('url' => 'alert/addedType')) }}  
            <label for="organization"><h4>Alert Name</h4></label></br>                         
            <input type="text" id="alertName" name="alertName" value="" class="form-control" required>     
												</br>
												<div>
													<button type="submit"  value="add" class="btn btn-default btn-info">Add</button> 
												</div>            
									{{ Form::close() }}
							</div>
@endsection