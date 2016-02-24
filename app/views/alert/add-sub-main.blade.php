@extends('layouts.protected')
@section('content')
<div class="container">
<h3 style="text-align:center;">Add Alert Sub Type</h3>
	       <div>                      
									{{ Form::open(array('url' => 'alert/addedSubType')) }}  
            <label for="organization"><h4>Alert Name::{{$alertName}}</h4></label></br>     
												<input type="hidden" id="alertTypeId" name="alertTypeId" value="{{$alertId}}" class="form-control" required>
											<input type="hidden" id="mainAlertName" name="mainAlertName" value="{{$alertName}}" class="form-control" required>												
            <input type="text" id="alertSubName" name="alertSubName" value="" class="form-control" required>     
												</br>
												<div>
													<button type="submit"  value="add" class="btn btn-default btn-info">Add</button> 
												</div>            
									{{ Form::close() }}
							</div>
@endsection