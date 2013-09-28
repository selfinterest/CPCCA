angular.module("CPCCA", ["ngResource"])
  .config(["$locationProvider", "$routeProvider", function($locationProvider, $routeProvider){
    $locationProvider.html5Mode(true).hashPrefix("!");
    $routeProvider
      .when("/", {
        templateUrl: "/api/template/index.html",
        controller: "IndexCtrl",
        activate: "index",
        resolve: {
          "Document": ["$resource", function($resource){
            return $resource("/api/documents");
          }]
        }
      })
      .when("/about", {
        activate: "about",
        template: "<div>Something something about.</div>"
      })
  }])
  .run(["$rootScope", function($rootScope){
    $rootScope.$on("$routeChangeSuccess", function(e, $route){
      $rootScope.activeRoute = $route.$$route.activate;
    })
  }])
  .controller("IndexCtrl", ["$scope", "Document", function($scope, Document){
    var numDocumentsPerRow = 2;
    $scope.documents = Document.query(function(documents){
      //Figure out rows. Each row has three columns (documents)
      $scope.rows = [];
      $scope.numRows = Math.ceil(documents.length / numDocumentsPerRow);
      var documentCounter = 0;
      for(var x = 0; x < $scope.numRows; x++){
        if(documentCounter + numDocumentsPerRow < documents.length){
          $scope.rows.push(documents.slice(documentCounter, documentCounter + numDocumentsPerRow))
        } else {
          $scope.rows.push(documents.slice(documentCounter));
        }
        documentCounter = documentCounter + numDocumentsPerRow;
      }
      /*setTimeout(function(){
        $.scrollTo("#inner-wrap", {duration: 300});
      }, 500);*/

    });
  }])
