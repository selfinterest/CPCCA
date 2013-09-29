/**
 * Created by: Terrence C. Watson
 * Date: 9/28/13
 * Time: 7:59 PM
 */
angular.module("CPCCA", ['files', 'ui.bootstrap', 'ngResource'])
  .config(["$locationProvider", "$routeProvider", function($locationProvider, $routeProvider){
    $locationProvider.html5Mode(true).hashPrefix("!");
    $routeProvider
      .when("/admin/files", {
        //template: "<div>FILES</div>"
        templateUrl: "/api/admin/template/file.php",
        controller: "FileCtrl",
        resolve: {
          "file": ["$resource", "$location", function($resource, $location){
            return $resource("/api/admin/file/:name").get({name: $location.search().selected});
            //return $resource("/api/admin/file/:name").get($location.search().selected);
          }]
        }
      })
  }])
  .controller("FooterCtrl", ["$scope", "$fileUploader", "$rootScope", function($scope, $fileUploader, $rootScope){
    var uploader = $fileUploader.create({
      scope: $scope,                          // to automatically update the html. Default: $rootScope
      url: '/api/admin/upload',
      removeAfterUpload: true

    });

    uploader.bind( 'complete', function( event, xhr, item ) {
      $rootScope.$broadcast("receivedFile", {item: item, response: xhr.response});
    });

    $scope.uploader = uploader;

  }])
  .controller("FilesCtrl", ["$scope", "$resource", "$http", "$location", function($scope, $resource, $http, $location){

    $scope.selected = {};
    $scope.selected.name = angular.isDefined($location.search().selected) ? $location.search().selected : null;
    $scope.files = [];
    $http.get("/api/admin/files").success(function(files){
      $scope.files = files;
    })

    $scope.$on("receivedFile", function(e, obj){
      $scope.files.push(obj.item.file.name);
    });
  }])

  .controller("FileCtrl", ["$scope", "file", function($scope, file){
    $scope.file = file;
  }])