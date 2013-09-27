angular.module("CPCCA", [])
  .config(["$locationProvider", "$routeProvider", function($locationProvider, $routeProvider){
    $locationProvider.html5Mode(true).hashPrefix("!");
    $routeProvider
      .when("/", {
        template: "<div>BLAH</div>"
      })
  }])
