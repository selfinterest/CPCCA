angular.module("CPCCA", [])
  .config(["$location", "$routeProvider", function($location, $routeProvider){
    $location.html5Mode(true).hashPrefix("!");
    $routeProvider
      .when("/", {
        template: "<div>BLAH</div>"
      })
  }])
