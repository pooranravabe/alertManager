@extends('layouts.protected')
@section('content')
<div class="container">
<div class="row">
  <div> @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <div class="col-lg-4 col-lg-offset-4 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2"> </div>
  </div>
</div>
<div class="row">
  <h1> Alert Subscription List</h1>
 <br/> <br/>
 <!--<a href="{{url('alertManage/addAlertSubs')}}" class="nav-item btn btn-info ">Add New Subscription</a>
  <br/> <br/>-->
  <table class="table table-bordered" width="60%" align="center">
    <tr>
      <th>Sr No. </th>
      <th>Alert Subscription</th>
      <th colspan="2">Operations</th>
    </tr>
	@if($data['AlertsList']!=null)
    @foreach($data['AlertsList'] as $key=>$title)
    <tr>
      <td>{{$key+1}}</td>
      <td> <label>{{$title->title}}</label></td>
       <td> {{Form::open(array('url'=> 'alertManage/editAlertSubs'))}}
			<input type="hidden" id="titleId" name="titleId" value="{{$title->id}}" /> 
			<input type="hidden" id="titleRole" name="titleRole" value="{{$title->permission_role}}" />
			<input type="hidden" id="titleName" name="titleName" value="{{$title->title}}" required/>
			<button type="submit" class="btn btn-info">Edit</button>
		{{Form::close()}}
        <!--      
		{{Form::open(array('url'=> 'alertManage/deleteAlertSubs'))}}
			<input type="hidden" id="titleId" name="titleId" value="{{$title->id}}" />
			<button type="submit" class="btn btn-info">Delete</button></td>
		{{Form::close()}}-->
	  </td>
    </tr>
    @endforeach
	@endif	
  </table>
</div>
@endsection 