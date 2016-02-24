'use strict';
ravabe.factory('channelsData', function($http) {
    var channelsData = {};
    console.log('channel-service');
    channelsData.channelsByProjectId = function() {
        var channelsData = $http.get('get-publish-data');
        return channelsData;
    };
    return  channelsData;
});