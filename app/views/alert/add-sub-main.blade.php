@extends('layouts.protected')
@section('content')
<div class="container">
<h3 style="text-align:center;">Add Alert Sub Type</h3>
<div> {{ Form::open(array('url' => 'alert/addedSubType')) }}
  <label for="organization">
  <h4>Sub type Alert Under::{{$data['alertName']}}</h4>
  </label>
  </br>
  <input type="hidden" id="alertTypeId" name="alertTypeId" value="{{$data['alertId']}}" class="form-control" required>
  <input type="hidden" id="mainAlertName" name="mainAlertName" value="{{$data['alertName']}}" class="form-control" required>
  Sub Alert Name:
  <input type="text" id="alertSubName" name="alertSubName" value="" class="form-control" required>
  <br>
  Color Code:
  <input type="text" id="alertColorCode" name="alertColorCode" value="" class="form-control" required>
  <br>
  Sub Alert Message:
  <textarea class="form-control" id="alertSubMessage" name="alertSubMessage" rows="3" required></textarea>
  <br>
  
  Module Name:<br>
  <select class="list-select-box" name="moduleName" id="select-sort-by"  required>
    <option value="">Select Moduel</option>
    @foreach($data['modulecategories'] as $key=>$val)																											
    <option value="{{$val->id}}">{{$val->category}}</option>
    @endforeach																						
  </select>
  <br>
  <span id="submodule"></span>
   <br>
  
  From Status:<br>
  <select class="list-select-box" name="fromStatus" id="fromStatus">
    <option value="">Select Status</option>
    <option value="less">Less Then</option>
    <option value="greater">Greater Then</option>
    <option value="equal">Equal To</option>
  </select>
  </br>
  <br>
  From %:
  <input type="number" id="fromPercent" name="fromPercent" value="" class="form-control">
  <br>
  To Status:<br>
  <select class="list-select-box" name="toStatus" id="toStatus">
    <option value="">Select Status</option>
    <option value="less">Less Then</option>
    <option value="greater">Greater Then</option>
    <option value="equal">Equal To</option>
  </select>
  <br>
  <br>
  To %:
  <input type="number" id="toPercent" name="toPercent" value="" class="form-control">
 
  <div> <br>
    <button type="submit"  value="add" class="btn btn-default btn-info">Add</button>
  </div>
  {{ Form::close() }} </div>
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