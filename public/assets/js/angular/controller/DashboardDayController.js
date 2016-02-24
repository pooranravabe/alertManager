



'use strict';
ravabe.controller('dashboardDayCtrl', function($scope, $http, $locale) {
    $scope.channels = [];
    $scope.days = {};
    $scope.channels_events = {};
    $scope.month = moment().month();
    $scope.year = moment().year();
    $scope.events = [];
    $scope.project = function() {
        $http.post(base_url + '/get-publish-data', {'month': $scope.month,'year': $scope.year}).success(function(resp) {
            $scope.channels = resp.channels;
            preparePublishDaysData(resp.publish_data,resp.events_data);
            $(window).trigger('resize');
        }).error(function(resp) {
        });
        // $http.post(base_url + '/event-data', {'month': $scope.month,'year': $scope.year}).success(function(resp) {
        //     $scope.events = resp;
        // }).error(function(resp) {
        // });
        var cal_moment = moment([$scope.year, $scope.month]);
        $scope.currentMonth = moment.months()[cal_moment.month()];
        $scope.months = moment.monthsShort('-MMM-');
        $scope.currentMonthShort = moment.monthsShort('-MMM-')[cal_moment.month()];
        $scope.current_month = moment().month();
        $scope.today = moment().date();
        var cal_week = [];
        $scope.currentMonth = moment.months()[cal_moment.month()];
        for (var i = 1; i <= cal_moment.daysInMonth(); i++) {
            var cal_date = {};
            cal_date.day = moment([cal_moment.year(), cal_moment.month()]).date(i).format('dddd');
            cal_date.full_date = moment([cal_moment.year(), cal_moment.month()]).date(i).toDate();
            cal_date.date = i;
            cal_week.push(cal_date);
            $scope.days = cal_week;
        }
        function preparePublishDaysData(publish_data,events_data) {
            angular.forEach(publish_data, function(event_data, channel_id) {
                var event_week = [];
                $scope.channels_events[channel_id] = {};
                for (var i = 1; i <= cal_moment.daysInMonth(); i++) {
                    var event_date = {};
                    event_date.day = moment([cal_moment.year(), cal_moment.month()]).date(i).day();
                    event_date.date = i;
                    event_date.status = '';
                    event_week.push(event_date);
                    var publish_same_date =[];
                    angular.forEach(event_data, function(value, key) {
                        if (value.date == i) {
                            publish_same_date.push(value);
                            if (value.status == 'SUCCESS') {
                                event_date.status = 'green';
                            } 
                            event_date.channel_status = value.channel_status;
                            event_date.content_id = value.content_id;
                            event_date.channel_id = value.channel_id;
                        }

                    });
                    angular.forEach(publish_same_date, function(same_date, key) {
                        if (same_date.channel_id == channel_id) {
                            if(same_date.status == 'PENDING'|| same_date.status == 'FAILED'){
                               event_date.status = 'red';
                               event_date.channel_status = same_date.channel_status;
                               event_date.content_id = same_date.content_id;
                               event_date.channel_id = same_date.channel_id;
                            }
                        }

                    });
                    $scope.channels_events[channel_id] = event_week;
                }
            });
        for (var i = 1; i <= cal_moment.daysInMonth(); i++) {
                var event_data_date = {};
                event_data_date.events_dates = events_data[i];
                    $scope.events.push(event_data_date);
            }
        }
    }
    $scope.project();
    $scope.setCalenderByMonth = function(obj) {
        $scope.month = obj;
        $scope.channels_events = {};
        $scope.events=[];
        $scope.project($scope.month);
    }
    $scope.setPreviousYear = function() {
        $scope.year = $scope.year-1;
        $scope.channels_events = {};
        $scope.events=[];
        $scope.project();
    }
    $scope.setNextYear = function() {
        $scope.year = $scope.year+1;
        $scope.channels_events = {};
        $scope.events=[];
        $scope.project();
    }
    $scope.setTodayDate = function() {
        if($scope.month != moment().month()){
           $scope.year = moment().year();
           $scope.month = moment().month();
           $scope.channels_events = {};
           $scope.project();
       }
    }
     $scope.allEventShow = function(obj,data){
        obj.stopPropagation();
        $('.event-tooltip').hide();
        $scope.all_events =[];
        $scope.all_events =data;
         var this_parent_position = $(obj.target).parent().offset();
            $('.more-events').css({
                'position':'absolute',
                'left':this_parent_position.left,
                'top':this_parent_position.top,
                'display':'block',
                'width':'20%'
            });
    }
    $scope.allEventClose = function(obj){
        $(obj.target).parent().parent().parent().find('.more-opened').hide();
    }
    // $scope.addEvent = function(data) {
    //     $http.post(base_url + '/add-event', {'event-title': data.eventname, 'startdate': data.StartDate, 'endDate': data.EndDate}).success(function(resp) {
    //         $('#add-events').modal('hide');
    //         $scope.project();
    //     }).error(function(resp) {
    //     });
    // }
    //  $scope.updateEvent = function(update_data) {
    //     $http.post(base_url + '/update-event', {'event_id':update_data.id,'event-title': update_data.event_title, 'startdate': update_data.start_full_date, 'endDate': update_data.end_full_date}).success(function(resp) {
    //         $('#edit-events').modal('hide');
    //         $scope.project();
    //         $scope.eventdaydata =[];
    //     }).error(function(resp) {
    //     });
    // }
    $scope.contentRedirect = function(publish_data) {
        if (publish_data.status != "") {
            var month = $scope.month + 1;
            window.location = base_url + '/publish-channel-content/' + publish_data.content_id + '/' + publish_data.channel_id + '/' + $scope.year + '-' + month + '-' + publish_data.date +'/'+publish_data.status;
        }
    }
    $scope.eventInfo = function(event,obj){
        obj.stopPropagation();
        var dt = moment(event.start_date, "YYYY-MM-DD HH:mm:ss");
         $scope.event_date_format ="";
        var start_date = moment(event.start_full_date, "YYYY-MM-DD HH:mm:ss");
        var end_date = moment(event.end_full_date, "YYYY-MM-DD HH:mm:ss");
        if(event.start_date==event.end_date){
           $scope.event_date_format = start_date.format('ddd, MMM D'); 
        }else{
           $scope.event_date_format = start_date.format('ddd, MMM D')+' - '+end_date.format('ddd, MMM D');  
        }
        // $scope.dayname = dt.format('dddd');
        $scope.eventdaydata = event;
         $('.event-tooltip').hide();
             var offset = obj.target.getBoundingClientRect();
             $('.event-tooltip').css({'position': 'absolute', 'top': offset.top-90, 'left': offset.left- 95});
             $('.event-tooltip').show();
    }
    $scope.eventDelete = function(){
         $http.post(base_url + '/delete-event', {'event_id': $scope.eventdaydata.id}).success(function(resp) {
            $('.event-tooltip').hide();
            $scope.events=[];
            $scope.all_events =[];
            toastr.success($scope.eventdaydata.event_title + ' event deleted');
            $scope.project();
        }).error(function(resp) {
        });
    }
    $scope.deleteChannelByProjectId = function(channel){
       $http.post(base_url + '/delete-channel-project', {'channel_id': channel.channel_id,'project_id': channel.project_id})
       .success(function(resp) {
        $scope.channels_events ={};
        $scope.channels =[];
        $scope.project();
    }).error(function(resp) {
    });
    }
    $scope.addEventPopup = function(data){
        $('#event-start-date').val(moment(data.full_date).format('YYYY-MM-DD'));
        $('#modal-event-title').text('Add Event');
        $('#submit-event').text('Add');
        $('#event-name').val('');
        $('#event-id').val('');
        $('#add-events-modal').modal('show');
    }
    
});

