(function () {
    'use strict';

    angular
        .module('app')
        .controller('HomeController', HomeController);

    HomeController.$inject = ['UserService', 'PortalService', '$rootScope'];
    function HomeController(UserService, PortalService, $rootScope) {
        var vm = this;
        vm.user = null;

        initController();

        function initController() {
            loadCurrentUser();
            doThing();
        }

        function loadCurrentUser() {
            UserService.GetByUsername($rootScope.globals.currentUser.username)
                .then(function (user) {
                    vm.user = user;
                });
        }
        
        function doThing() {
            PortalService.BingBong().then(function(res) {
                console.log(res);
            });
        }
    }

})();