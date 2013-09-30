angular.module("CPCCA", ["ngResource", "ui.bootstrap"])
  .config(["$locationProvider", "$routeProvider", function($locationProvider, $routeProvider){
    $locationProvider.html5Mode(true).hashPrefix("!");
    $routeProvider
      .when("/", {
        templateUrl: "/api/template/index.html",
        controller: "IndexCtrl",
        activate: "index",
        title: "CPCCA | Documents",
        resolve: {
          "documents": "Documents"
        }
      })
      .when("/about", {
        activate: "about",
        templateUrl: "/api/template/about.php",
        title: "CPCCA | About"
      })
      .when("/contact", {
        template: "<div>Something something contact.</div>",
        activate: "contact",
        title: "CPCCA | Contact"
      })
  }])
  .run(["$rootScope", function($rootScope){

  }])
  .service("Documents", ["$resource", "$q", function($resource, $q){
    var resource = $resource("/api/documents");
    var deferred = $q.defer();
    resource.query(function(documents){
      deferred.resolve(documents);
    });
    return deferred.promise;
  }])
  .controller("NavCtrl", ["$scope", "Documents", "$timeout", "$anchorScroll", "$rootScope", function($scope, documents, $timeout, $anchorScroll, $rootScope){
    $scope.documents = documents;

    $scope.$on("$routeChangeSuccess", function(e, $route){
      $scope.active = $route.$$route.activate;
      $rootScope.title = $route.$$route.title;
    });

    $scope.scrollToDocument = function(filename){
      $timeout(function(){
        $anchorScroll();
      })
    }
  }])
  .controller("JumbotronCtrl", ["$scope", function($scope){
    $scope.minimize = function(){
      $("#jumbotron").collapse();
    }
  }])
  .controller("IndexCtrl", ["$scope", "documents", "$anchorScroll", "$timeout", "$location", function($scope, documents, $anchorScroll, $timeout, $location){
    var numDocumentsPerRow = 2;
    $scope.documents = documents;

    //$scope.documents = Document.query(function(documents){
    //Figure out rows. Each row has three columns (documents)
    $scope.rows = [];
    $scope.numRows = Math.ceil(documents.length / numDocumentsPerRow);      //We should -1 from the length if we want the primary document up top

    var documentCounter = 0;              //should be one if we want the top row single

    //The very first document is special. We give it its own item.
    $scope.primary = $scope.documents[0];

    for(var x = documentCounter; x < $scope.numRows; x++){
      if(documentCounter + numDocumentsPerRow < $scope.documents.length){
        $scope.rows.push($scope.documents.slice(documentCounter, documentCounter + numDocumentsPerRow))
      } else {
        $scope.rows.push($scope.documents.slice(documentCounter));
      }
      documentCounter = documentCounter + numDocumentsPerRow;
    }

    $timeout(function(){
      $anchorScroll();
      $scope.selected = $location.hash();
    });

    $scope.setSelected = function(filename){
      $scope.selected = filename;
      $location.hash(filename);
    }


    /*setTimeout(function(){
      $.scrollTo("#inner-wrap", {duration: 300});
    }, 500);*/

  //});
  }])
