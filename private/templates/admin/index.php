<?php
    require_once($headPath);
?>
</head>
<body>
    <div id="wrap">
        <div class="container">
            <div class="row">
                <h2>Operations</h2>
                <div class="col-md-4">
                    <div class="list-group" ng-controller="FilesCtrl">
                        <div class="list-group-item-heading"><h4>Files</h4></div>
                        <a ng-repeat="file in files" ng-click="selected.name = file.name" ng-href="/admin/files?selected={{file.name}}" class="list-group-item" ng-class="{'active': selected.name == file.name }">
                            <span class="glyphicon glyphicon-file"></span> {{file.name}}
                            <span class="glyphicon glyphicon-check pull-right" ng-show="file.db"> </span>
                        </a>
                    </div>
                    <div class="list-group" ng-controller="UserCtrl">
                        <div class="list-group-item-heading"><h4>Users</h4></div>
                    </div>
                </div>
                <div ng-view class="col-md-8">
                    <h3></h3>
                </div>
            </div>
        </div>
    </div>
    <div id="footer" ng-controller="FooterCtrl">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="inputWrapper">
                        <input ng-file-select type="file" multiple />
                    </div>
                </div>
                <div class="col-md-7">
                    <a href="" class="btn btn-link btn-primary btn-sm dropdown-toggle" ng-show="uploader.queue.length > 0">Queue &uarr;</a>
                    <ul class="dropdown-menu bottom-up">
                        <li ng-repeat="item in uploader.queue">
                            <a href="#">
                                <span class="glyphicon glyphicon-minus-sign"></span> {{item.file.name}}
                            </a>
                            <div class="container">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <button class="btn btn-sm btn-primary" ng-click="$event.preventDefault(); uploader.uploadAll()" ng-show="uploader.queue.length > 0"><span class="glyphicon glyphicon-upload"></span> Upload all</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-sm btn-primary" ng-click="logout()">Logout</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/public/lib/angular/angular.min.js"></script>
    <script src="/public/lib/angular-resource/angular-resource.min.js"></script>
    <script src="/public/lib/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
    <script src="/public/lib/angular-file-upload/js/angular/modules/files.js"></script>
    <script src="/public/lib/underscore/underscore-min.js"></script>
    <script src="/public/js/admin.js"></script>
</body>