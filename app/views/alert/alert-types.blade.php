@extends('layouts.protected')
@section('content')
<style>
form.ng-pristine {
    float: left;
    margin-right: 10px;
}
.tablepadding{
	padding:6px;
}

</style>
<div class="container">
<div class="row">
  <div> @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <div class="col-lg-4 col-lg-offset-4 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2"> </div>
  </div>
</div>
<div class="row">
  <h1> Manage Alerts</h1>
  <br/> <br/>
 <h3><a href="{{url('alert/addType')}}" class="nav-item ">Add New</a></h3>
  <br/> <br/>
  <table class="table table-bordered tablepadding" width="60%" align="center">
    <tr>
      <th>Sr No. </th>
      <th>Alerts Type</th>
      <th colspan="2">Operations</th>
    </tr>
	@if($data!=null)
    @foreach($data as $key=>$alert)
    <tr>
      <td>{{$key+1}}</td>
      <td> <label>{{$alert['type']}}</label></td>
      <td> 
						{{Form::open(array('url'=> 'alert/addSubType'))}}
										<input type="hidden" id="alertId" name="alertId" value="{{$alert['id']}}" />           
										<input type="hidden" id="alertName" name="alertName" value="{{$alert['type']}}" />  
										<button type="submit" class="btn btn-info">Add</button>
								{{Form::close()}}
									{{Form::open(array('url'=> 'alert/editType'))}}
										<input type="hidden" id="alertId" name="alertId" value="{{$alert['id']}}" />           
										<input type="hidden" id="alertName" name="alertName" value="{{$alert['type']}}" required/>
										<button type="submit" class="btn btn-info">Update</button>
								{{Form::close()}}
								
								
					 </td>
      <td>       
									{{Form::open(array('url'=> 'alert/deleteType'))}}
										<input type="hidden" id="alertId" name="alertId" value="{{$alert['id']}}" />
											<button type="submit" class="btn btn-info">Delete</button>
									{{Form::close()}}
				  </td>
    </tr>
				<?php 
				
				if($data[$key]['subtype']!=''){
					
					$k = 0;
											foreach($data[$key]['subtype'] as $sybkey=>$subalert){
				?>
											<tr>
													<td align="right">{{$k= $k+1}}</td>
													<td style="padding-left:30px;">{{$subalert['type']}}</td>
													<td> 
															{{Form::open(array('url'=> 'alert/editSubType'))}}
																	<input type="hidden" id="alertId" name="alertId" value="{{$subalert['id']}}" />           
																	<input type="hidden" id="alertParentId" name="alertParentId" value="{{$alert['id']}}" /> 
																	<input type="hidden" id="alertName" name="alertName" value="{{$subalert['type']}}" required/>
																<button type="submit" class="btn btn-info">Update</button>
															{{Form::close()}}
													</td>
													<td>       
															{{Form::open(array('url'=> 'alert/deleteSubType'))}}
																<input type="hidden" id="alertSubId" name="alertSubId" value="{{$subalert['id']}}" />
																<button type="submit" class="btn btn-info">Delete</button>
															{{Form::close()}}
													</td>
										
						</tr>
			<?php 	 
			}
								 	}
			?>
    @endforeach
	@endif	
  </table>
</div>
@endsection 