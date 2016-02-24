'use strict';
ravabe.controller('ChannelSettingCtrl', function($scope, $http) {
    $scope.plateforms = [];
    $scope.viewNetworkData =function() {
        $http.post(base_url + '/plateforms-data').success(function(resp) {
            $scope.plateforms =resp.plateforms;
            if($scope.plateforms =='' && FIRST_FLIGHT != true){
              toastr.error('You have not connected any channels'); 
            }
     }).error(function(resp) {
        
     });
 }
 $scope.viewNetworkData();
 $scope.deleteChannel = function (data,obj){
    //obj.target.innerHTML = 'Revoking...';
    $http.post(base_url + '/delete-channel', {'channel_id': data.id}).success(function(resp) {
        toastr.success(data.name + ' channel deleted');
        $scope.viewNetworkData();
    }).error(function(resp) {
        $scope.plateforms =[];
    });
}

$scope.needReconnect = function(channel){
   if(channel.network =='Facebook Page'){
        var url ='Facebook';
    }else if(channel.network =='Linkedin Company'){
        var url ='Linkedin';
    }else{
        var url = channel.network;
    }
   var myWindow = window.open(base_url +'/auth/'+url, "", "top=100, left=400, width=500, height=500");
   myWindow.focus();
}
});


