'use strict';
ravabe.controller('dashboardCtrl', function($scope, $http, $locale, $location) {
    $scope.single_day = $('div #vertical-scroll-2 .single').width();
    $scope.month = moment().month();
    $scope.year = moment().year();
    $scope.channels = [];
    $scope.channels_publish_data = {};
    $scope.events = [];
    $scope.toggle = function() {
        if ($('#toggle i').hasClass('fa-square')) {
            $('#toggle i').addClass('fa-check-square');
            $('#toggle i').removeClass('fa-square');
        } else {
            $('#toggle i').removeClass('fa-check-square');
            $('#toggle i').addClass('fa-square');
        }
    }
    $scope.project = function() {
        $scope.firstOfMonth = [];
        var cal_moment = moment([$scope.year, $scope.month]);
        // $scope.year = cal_moment.year();
        // $scope.month = cal_moment.month();
        $scope.dayNames = $locale.DATETIME_FORMATS['SHORTDAY'];
        $scope.months = moment.monthsShort('-MMM-');
        $scope.currentMonth = moment.months()[cal_moment.month()];
        $scope.currentMonthShort = moment.monthsShort('-MMM-')[cal_moment.month()];
        $scope.current_month = moment().month();
        $scope.current_year = moment().year();
        $scope.today = moment().date();
        var today = new Date();
        $http.post(base_url + '/get-publish-data', {'month': $scope.month,'year': $scope.year}).success(function(resp) {
            $scope.channels = resp.channels;
            preparePublishData(resp.publish_data, resp.events_data);
            $(window).trigger('resize');
        }).error(function(resp) {
        });
        // $http.post(base_url + '/event-data', {'month': $scope.month,'year': $scope.year}).success(function(resp) {
        //     $scope.events = resp;
        // }).error(function(resp) {
        // });
        var firstOfMonth = new Date($scope.year, $scope.month, 1);
        for (var k = 0; k < firstOfMonth.getDay(); k++) {
            $scope.firstOfMonth.push(k);
        }
        $scope.weeks = {};
        var cal_week = [];
        var j = 0;
        for (var i = 1; i <= cal_moment.daysInMonth(); i++) {
            var cal_date = {};
            cal_date.day = moment([cal_moment.year(), cal_moment.month()]).date(i).day();
            cal_date.full_date = moment([cal_moment.year(), cal_moment.month()]).date(i).toDate();
            // cal_date.full_date = moment(cal_date.full_date).format('YYYY-MM-DD');
            cal_date.date = i;
            cal_week.push(cal_date);
            $scope.weeks[j] = cal_week;
            if (cal_date.day == 6) {
                j++;
                cal_week = [];
            }

        }
        function preparePublishData(publish_data,events_data) {
            angular.forEach(publish_data, function(event_data, channel_id) {
                var event_week = [];
                var j = 0;
                $scope.channels_publish_data[channel_id] = {};
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
                            event_date.content_id = value.content_id;
                            event_date.channel_status = value.channel_status;
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
                    $scope.channels_publish_data[channel_id][j] = event_week;
                    if (event_date.day == 6) {
                        j++;
                        event_week = [];
                    }
                }
            });
            for (var i = 1; i <= cal_moment.daysInMonth(); i++) {
                    var event_data = {};
                    event_data.events_dates = events_data[i];
                    $scope.events.push(event_data);
            }
        }
    }
    $scope.project();
    $scope.setCalenderByMonth = function(month, obj) {
        $scope.month = obj;
        $scope.channels_publish_data = {};
        $scope.events=[];
        $scope.project($scope.month);
    } 
    $scope.setPreviousYear = function() {
        $scope.year = $scope.year-1;
        $scope.channels_publish_data = {};
        $scope.events=[];
        $scope.project();
    }
    $scope.setNextYear = function() {
        $scope.year = $scope.year+1;
        $scope.channels_publish_data = {};
        $scope.events=[];
        $scope.project();
    }
    $scope.setPreviousMonth = function() {
        var month_pre = $scope.month-1;
         if(month_pre==-1){
            $scope.month=11;
            $scope.year= $scope.year-1;
         }else{
            $scope.month = month_pre;
         }
        $scope.channels_publish_data = {};
        $scope.events=[];
        $scope.project();
    }
    $scope.setNextMonth = function() {
        var month_next = $scope.month+1;
        if(month_next==12){
            $scope.month=0;
            $scope.year= $scope.year+1;
        }else{
            $scope.month = month_next;
        }
        $scope.channels_publish_data = {};
        $scope.events=[];
        $scope.project();
    }
    $scope.setTodayDate = function() {
        if($scope.year != moment().year() || $scope.month != moment().month()){
           $scope.year = moment().year();
           $scope.month = moment().month();
           $scope.channels_publish_data = {};
           $scope.events=[];
           $scope.project();
       }
    }
    $scope.allEventShow = function(obj,data){
        obj.stopPropagation();
        $scope.all_events =[];
        $scope.all_events =data;
        var this_parent_position = $(obj.target).parent().offset();
            $('.more-events').css({
                'position':'absolute',
                'left':this_parent_position.left,
                'top':this_parent_position.top,
                'display':'block'
            });
        // $(obj.target).parent().find('.more-opened').addClass('display');
    }
    $scope.allEventClose = function(obj){
        obj.stopPropagation();
        $(obj.target).parent().parent().parent().find('.more-opened').removeClass('display');
    }
    /*$scope.addEvent = function(data) {
        console.log(data);return false;
        $http.post(base_url + '/add-event', {'event_title': data.event_name, 'start_date': data.event_start_date, 'end_date': data.event_end_date}).success(function(resp) {
            $('#add-events-modal').modal('hide');
            $scope.project();
        }).error(function(resp) {
        });
    }
     $scope.updateEvent = function(update_data) {
        $http.post(base_url + '/update-event', {'event_id':update_data.id,'event-title': update_data.event_title, 'startdate': update_data.start_full_date, 'endDate': update_data.end_full_date}).success(function(resp) {
            $('#edit-events').modal('hide');
            $scope.project();
            $scope.eventdata =[];
        }).error(function(resp) {
        });
    }*/

    $scope.contentRedirect = function(publish_data) {
        if (publish_data.status != "") {
            var month = $scope.month + 1;
            window.location = base_url + '/publish-channel-content/' + publish_data.content_id + '/' + publish_data.channel_id + '/' + $scope.year + '-' + month + '-' + publish_data.date +'/'+publish_data.status;
        } 
    }
    $scope.eventInfo = function(event,obj){
        obj.stopPropagation();
        $scope.event_date_format ="";
        var start_date = moment(event.start_full_date, "YYYY-MM-DD HH:mm:ss");
        var end_date = moment(event.end_full_date, "YYYY-MM-DD HH:mm:ss");
        if(event.start_date==event.end_date){
           $scope.event_date_format = start_date.format('ddd, MMM D'); 
        }else{
           $scope.event_date_format = start_date.format('ddd, MMM D')+' - '+end_date.format('ddd, MMM D');  
        }
        // $scope.dayname = dt.format('dddd');
        $scope.eventdata = event;
         $('.event-tooltip').hide();
             var offset = obj.target.getBoundingClientRect();
             $('.event-tooltip').css({'position': 'absolute', 'top': offset.top-90, 'left': offset.left- 95});
             $('.event-tooltip').show();
    }
    $scope.eventDelete = function(){
         $http.post(base_url + '/delete-event', {'event_id': $scope.eventdata.id}).success(function(resp) {
            $('.event-tooltip').hide();
            $scope.events=[];
            $scope.all_events =[];
            $('.more-events').hide();
            toastr.success($scope.eventdata.event_title + ' event deleted');
            $scope.project();
        }).error(function(resp) {
        });
    }
    $scope.editEvent = function(){
        $('#edit-events').modal('show');
        $('.event-tooltip').hide();
    }
    $scope.addEventPopup = function(data){
        // moment(data.full_date).format('YYYY-MM-DD');
        $('#event-start-date').val(moment(data.full_date).format('YYYY-MM-DD'));
        $('#modal-event-title').text('Add Event');
        $('#submit-event').text('Add');
        $('#event-name').val('');
        $('#event-id').val('');
        $('#add-events-modal').modal('show');
    }
    $scope.deleteChannelByProjectId = function(channel){
       $http.post(base_url + '/delete-channel-project', {'channel_id': channel.channel_id,'project_id': channel.project_id})
       .success(function(resp) {
        $scope.channels_publish_data ={};
        $scope.project();
    }).error(function(resp) {
    });
    }
});