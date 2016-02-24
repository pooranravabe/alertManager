@extends('layouts.protected')
@section('content')
<div class="container">
<h3 style="text-align:center;">Edit Alert Sub Type</h3>
{{ Form::open(array('url' => 'alert/updateSubType')) }}  
	       <div>                      
            <label for="organization"><h4>Alert Type</h4></label></br>    
            <input type="hidden" id="alertTypeEditId" name="alertTypeEditId" value="{{$alertTypeId}}" class="form-control" required> 
												<input type="hidden" id="alertSubParentId" name="alertSubParentId" value="{{$alertparentId}}" class="form-control" required>												
            <input type="text" id="alertTypeEditName" name="alertTypeEditName" value="{{$alertTypeName}}" class="form-control" required>     
     		</br>
     		<div>
            <button type="submit"  value="update" class="btn btn-default btn-info">Update</button> 
            </div>            
   
    {{ Form::close() }}
</br>
</div>
@endsection