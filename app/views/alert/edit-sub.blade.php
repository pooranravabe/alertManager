@extends('layouts.protected')
@section('content')
<div class="container">
<h3 style="text-align:center;">Edit Alert Sub Type</h3>
{{ Form::open(array('url' => 'alert/updateSubType')) }}
<div>
  <label for="organization">
  <h4>Alert Type</h4>
  </label>
  </br>
  <input type="hidden" id="alertTypeEditId" name="alertTypeEditId" value="{{$data['alertId']}}" class="form-control" required>
  <input type="hidden" id="alertSubParentId" name="alertSubParentId" value="{{$data['alertParentId']}}" class="form-control" required>
  Sub Alert Name:
  <input type="text" id="alertTypeEditName" name="alertTypeEditName" value="{{$data['alertName']}}" class="form-control" required>
  <br>
  Color Code:
  <input type="text" id="alertColorCode" name="alertColorCode" value="{{$data['color_code']}}" class="form-control" required>
  <br>
  Sub Alert Message:
  <textarea class="form-control" id="alertSubMessage" name="alertSubMessage" rows="3" required>{{$data['alert_message']}}</textarea>
  </br>
    <br>
  Module Name:<br>
  <select class="list-select-box" name="moduleName" id="select-sort-by"  required>
    <option value="">Select Moduel</option>
     @foreach($data['modulecategories'] as $key=>$val)
          @if($data['module_id']==$val->id)																		
          	<option value="{{$val->id}}" selected>{{$val->category}}</option>
          @else	
              <option value="{{$val->id}}">{{$val->category}}</option>	
          @endif			
     @endforeach													
  </select>
  <br>
  <br>
  <span id="submodule"> Sub Module Name:<br>
  <select class="list-select-box submodulename" name="subModuleId" id="sub_module" style="width:200px;" required>
    <option value="">Select Sub Moduel</option>
    @foreach($data['sodulesubcategories'] as $key=>$val)
         @if($data['module_sub_id']==$val->id)
         	<option value="{{$val->id}}" selected>{{$val->module}}</option>
         @else				
         	<option value="{{$val->id}}">{{$val->module}}</option>
         @endif
    @endforeach											
  </select>
  </span> <br>
  From Status:<br>
  <select class="list-select-box" name="fromStatus" id="fromStatus">
    <option value="">Select Status</option>
    <option value="less" <?php if($data['from_status'] == 'less') { echo 'selected'; }?>>Less Then</option>
    <option value="greater" <?php if($data['from_status']=='greater') { echo 'selected'; }?>>Greater Then</option>
    <option value="equal" <?php if($data['from_status']=='equal') { echo 'selected'; }?>>Equal To</option>
  </select>
  </br>
  <br>
  From %:
  <input type="number" id="fromPercent" name="fromPercent" value="{{$data['range_from']}}" class="form-control">
  <br>
  To Status:<br>
  <select class="list-select-box" name="toStatus" id="toStatus">
    <option value="">Select Status</option>
    <option value="less" <?php if($data['to_status'] == 'less') echo 'selected';?>>Less Then</option>
    <option value="greater" <?php if($data['to_status']=='greater') echo 'selected';?>>Greater Then</option>
    <option value="equal" <?php if($data['to_status']=='equal') echo 'selected';?>>Equal To</option>
  </select>
  <br>
  <br>
  To %:
  <input type="number" id="toPercent" name="toPercent" value="{{$data['range_to']}}" class="form-control">

  </br>
  <div>
    <button type="submit"  value="update" class="btn btn-default btn-info">Update</button>
  </div>
  {{ Form::close() }} </br>
</div>
<script>
    $(document).ready(function () {
							
								
								
        $("#select-sort-by").heapbox({
           'effect': {
                'type': 'slide',
                'speed': 'fast'
            },
            'heapsize': '200px',
         	"onChange":function(val, elm) {
											$('#alertmoduleid').val(val);
											$.ajax({url: 'getSubModule',
													data: {'moduleId': val},
													error: function() {
													//
													},
													success: function(data) {
														if(data)
															$('#submodule').html(data);
													},
													type: 'GET'
											});
									}	   
        });
								
								$("#sub_module").heapbox({
           'effect': {
                'type': 'slide',
                'speed': 'fast'
            },
            'heapsize': '200px'
								});
								$("#fromStatus").heapbox({
           'effect': {
                'type': 'slide',
                'speed': 'fast'
            },
            'heapsize': '200px'
								});
								$("#toStatus").heapbox({
           'effect': {
                'type': 'slide',
                'speed': 'fast'
            },
            'heapsize': '200px'
								});
   });
</script> 
@endsection