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
          "file": ["resources", "$location", function(resources, $location){
            return resources.file.get({name: $location.search().selected});
            //return $resource("/api/admin/file/:name").get($location.search().selected);
          }]
        }
      })
  }])
  .service("resources", ["$resource", function($resource){
    return {
      file: $resource("/api/admin/file/:name", {name: "@filename"})
    }
  }])
  .controller("FooterCtrl", ["$scope", "$fileUploader", "$rootScope", "$http", "$window", function($scope, $fileUploader, $rootScope, $http, $window){
    var uploader = $fileUploader.create({
      scope: $scope,                          // to automatically update the html. Default: $rootScope
      url: '/api/admin/upload',
      removeAfterUpload: true

    });

    uploader.bind( 'complete', function( event, xhr, item ) {
      $rootScope.$broadcast("receivedFile", {item: item, response: xhr.response});
    });

    $scope.uploader = uploader;

    $scope.logout = function(){
      $http.post("/api/logout").success(function(){
        $window.location.replace("/login");
      })
    }

  }])
  .controller("FilesCtrl", ["$scope", "$resource", "$http", "$location", function($scope, $resource, $http, $location){

    $scope.selected = {};
    $scope.selected.name = angular.isDefined($location.search().selected) ? $location.search().selected : null;
    $scope.files = [];
    $http.get("/api/admin/files").success(function(files){
      $scope.files = files;
    })

    $scope.$on("receivedFile", function(e, obj){
      var newFile = angular.fromJson(obj.response);
      $scope.files.push({name: newFile.name, db: false});
    });

    $scope.$on("fileSaved", function(e, obj){
      $scope.files = _.map($scope.files, function(f){
        if(obj.name == f.name){
          f.db = true;
        }
        return f;
      })
    })


  }])

  .controller("FileCtrl", ["$scope", "file", "$location", "resources", "$rootScope", function($scope, file, $location, resources, $rootScope){
    if(!angular.isDefined(file.filename)){
      file.filename = $location.search().selected;
    }



    $scope.file = file;

    var range = [];
    for(var x = 1; x < 11; x++){
      range.push(x);
    }

    $scope.range = range;

    $scope.submit = function(){
      $scope.file.$save(function(){
        $rootScope.$broadcast("fileSaved", {name: file.filename});
      });
    }

  }])
  .controller("UserCtrl", ["$scope", function($scope){

  }])