(function () {
    'use strict';

    angular
        .module('app')
        .factory('PortalService', PortalService);

    PortalService.$inject = ['$http'];
    function PortalService($http) {
        var service = {};

        service.BingBong = BingBong;
        return service;

        function BingBong() {
            return $http.get('api/portal').then(handleSuccess, handleError('Error getting all users'));
        }
		
		// private functions

        function handleSuccess(res) {
            return res.data;
        }

        function handleError(error) {
            return function () {
                return { success: false, message: error };
            };
        }
    }

})();
