@extends('layouts.protected')
@section('content') 
<div class="page container">
    <div class="group-wrapper">
        {{Form::open(array('url' => 'alert', 'method' => 'get', 'id'=>'alert-filter-form'))}}
        <div class="list-header-action alert-action col-lg-12 col-md-12 col-sm-12">
           <div class="col-lg-2 user-alert-notification">
                Filter Notifications
           </div> 
           <div class="col-lg-1 user-alert-select">
                Select type
           </div>
           <div class="col-lg-8 user-alert-status">
                <input id="user_added" {{(in_array('user_added' ,$filters)) ? 'checked="checked"' : ''}} type="checkbox" name="notification_filter[]" value="user_added">
                <label for="user_added"></label>
                <img src="{{URL::asset('assets/images/user_added.png')}}">
              
                <input id="user_pending" {{(in_array('pending' ,$filters)) ? 'checked="checked"' : ''}} type="checkbox" name="notification_filter[]" value="pending">
                <label for="user_pending"></label>
                <img src="{{URL::asset('assets/images/pending.png')}}">
           
                <input id="user_rejected" {{(in_array('rejected' ,$filters)) ? 'checked="checked"' : ''}} type="checkbox" name="notification_filter[]" value="rejected">
                <label for="user_rejected"></label>
                <img src="{{URL::asset('assets/images/status-rejected.png')}}">
           
                <input id="user_authorization" {{(in_array('authorization' ,$filters)) ? 'checked="checked"' : ''}} type="checkbox" name="notification_filter[]" value="authorization">
                <label for="user_authorization"></label>
                <img src="{{URL::asset('assets/images/status-authorization.png')}}">
           </div>
           <div class="filter">
            <input id="btn_filter_alert" class="btn btn-default btn-generic" type="submit" value="Filter">
        </div>
        </div>
        <div class="clearfix"></div>
                <!-- MULTI FILTERS -->
        <div class="col-lg-12 col-md-12 col-sm-12 list-main">
            <div class="list-channel col-lg-offset-8">
                <p class="selectChannel pull-left">Sort Posts By</p>
                <select class="list-select-box" name="sort_by" id="select-sort-by">
                    <option value="DESC"@if($sort_by == 'DESC'){{'selected="selected"'}}@endif>Descending</option>
                    <option value="ASC" @if($sort_by == 'ASC'){{'selected="selected"'}}@endif>Ascending</option>
                </select>  
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 list-main">
            <div class="content">
                <table class="table" cellspacing="50">
                    <tbody>
                        @foreach($alert_details as $alert_detail)
                        <tr> 
                            <td style="width:50px;vertical-align:middle;"><a href=""><span class="channel_icon"><i class=""></i></span></a></td>
                            <td style="width:210px;vertical-align:middle;">{{$alert_detail['message']}}</td>
                            <td style="width:180px;">
                            @foreach($alert_detail['user_info'] as $user_info)
                            <div class="user-status-detail">
                                @if(isset($user_info['name']))
                                <img class="list-img img-circle" title="{{$user_info['name']}}" src="{{$user_info['image']}}" />
                                <i class="fa fa-{{$user_info['approval_status']}}"></i>
                                @endif
                            </div>
                            @endforeach
                            </td>
                            <td style="width:103px;vertical-align:middle;"><span class="alert-status-{{$alert_detail['type']}}"></span></td>
                            <td style="width:100px;vertical-align:middle;" >{{$alert_detail['date']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>
        </div>
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
            'onChange':function(){
                $('#alert-filter-form').submit();
            }
        });
   });
</script>
@endsection 

