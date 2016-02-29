@extends('layouts.protected')
@section('content') 
<div class="page container">
    <div class="group-wrapper">
        {{Form::open(array('url' => 'alertManage/postAlertSubs', 'method' => 'post', 'id'=>'alert-filter-form'))}}
        Title: <input class="form-control" style="width:" id="a_title" type="text" name="a_title" required/>
		</br>
		Roles:
		</br>
		<select class="list-select-box" name="roleId" id="select-sort-by">
            @foreach($data['roles'] as $user_info)
				<option value="{{$user_info['id']}}">{{$user_info['role']}}</option>
			@endforeach
					
        </select>  
		</br>
		
		<h3> Set Alerts</h3>
		<table class="table table-bordered" width="60%" align="center">
   
			<tr>
			  <td>Sr No. </td>
			  <td>Main Module</td>
			  <td>Module</td>
			  <td>On/Off</td>
			</tr>
    	
		<?php $i = 1;?>
		
		@foreach($data['modules'] as $key =>$module)		
		<?php $cid=$module['cid'];?>
		
			<tr>
			  <td>{{$i++}}</td>
			  <td>{{$module['category']}}</td>
			  <td>&nbsp; <input type="hidden" name="cid[{{$module['cid']}}]" value="{{$module['cid']}}"></td>
			  
			  <td>&nbsp; 
			  </td>
			</tr>	
	
			@foreach($module['module'] as $k => $submodule)
				 <tr>
				 <td>&nbsp;</td>
				  <td><input type="hidden" name="category[{{$submodule->id}}][cid]" value="{{$cid}}"></td>
				  <td>{{$submodule->module}}</td>     
				 <input type="hidden" name="mid[{{$submodule->id}}]" value="{{$submodule->id}}">
				   <td> 
				  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><input name="permisson[{{$submodule->id}}][onoff]" id="add_id" type="checkbox"   style="display:block;"></div>
				  </td>
				</tr>
			
			
			@endforeach
	
		@endforeach
			<tr><td>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<input type='submit' name='submit' value='Add' class="btn btn-info "/>
						</div></div></td></tr>
   
		</table>
		
		{{Form::close()}}
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#select-sort-by").heapbox({
            'effect': {
                'type': 'slide',
                'speed': 'fast'
            },
            'heapsize': '200px',
            
        });
   });
</script>
@endsection 

