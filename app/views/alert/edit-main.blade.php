@extends('layouts.protected')
@section('content')
<div class="container">
<h1 style="text-align:center;">Edit Alert Type</h1>
{{ Form::open(array('url' => 'alert/updateType')) }}  
	       <div>                      
            <label for="organization"><h4>Alert Type</h4></label></br>    
            <input type="hidden" id="alertTypeEditId" name="alertTypeEditId" value="{{$alerttypeId}}" class="form-control" required>                      
            <input type="text" id="alertTypeEditName" name="alertTypeEditName" value="{{$alertTypeName}}" class="form-control" required>     
     		</br>
     		<div>
            <button type="submit"  value="update" class="btn btn-default btn-info">Update</button> 
            </div>            
   
    {{ Form::close() }}
</br>
</div>
@endsection