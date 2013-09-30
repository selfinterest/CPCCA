<?php require_once($headPath) ?>
    </head>
    <body>
    <div id="wrap">
        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div class="navbar navbar" ng-controller="NavCtrl">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand hidden-lg hidden-md" href="#">Canadian Parliamentary Coalition to Combat Antisemitism</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li ng-class="{active: active == 'index'}"><a href="/">Home</a></li>
                        <li ng-class="{active: active == 'about'}"><a href="/about">About</a></li>
                        <li ng-class="{active: active == 'contact'}"><a href="/contact">Contact</a></li>
                        <li class="dropdown">
                            <a href="" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span> Documents <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li ng-repeat="document in documents">
                                    <a ng-href="/#{{document.filename}}" ng-click="scrollToDocument(document.filename)">{{document.title}}</a>
                                </li>
                                <!-- <li><a href="/test">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Nav header</li>
                                <li><a href="#">Separated link</a></li>
                                <li><a href="#">One more separated link</a></li> -->
                            </ul>
                        </li>
                    </ul>
                    <!-- <form class="navbar-form navbar-right">
                        <div class="form-group">
                            <input type="text" placeholder="Email" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Sign in</button>
                    </form> -->
                </div><!--/.navbar-collapse -->
            </div>
        </div>

        <div class="container" ng-controller="JumbotronCtrl">
            <!-- <div class="pull-right"><button type="button" class="btn btn-default btn-sm" ng-click="minimize()">
                <span class="glyphicon glyphicon-minus"></span>
            </button></div> -->
            <div class="jumbotron">
                <div class="container" id="jumbotron">
                    <!-- title -->
                    <div class="row hidden-sm hidden-xs">
                        <div class="col-lg-12 centered">
                            <h2 class="text-center text-primary"><strong>Canadian Parliamentary Coalition to Combat Antisemitism</strong></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-lg-12 logo">
                            <img src="/public/img/canadian-coat-of-arms-smallest.png" class="img-responsive"/>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-lg-3">
                            <p class="lead text-muted text-center">Ottawa, Canada</p>
                        </div>
                        <div class="col-lg-2 col-lg-offset-7">
                            <p class="lead text-muted text-center">2011</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="inner-wrap">
            <div class="container wrapped ng-view">
            </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        </div> <!-- /inner wrap -->
    </div> <!-- /wrap -->
    <div id="footer">
        <div class="container">
            <footer>
                <p class="text-muted pull-right">&copy; TCW Consulting 2013</p>
            </footer>
        </div>
    </div>
        <script>window.jQuery || document.write('<script src="/public/js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="/public/lib/jquery.scrollTo.min.js"></script>
        <script src="/public/js/vendor/bootstrap.min.js"></script>

        <script src="/public/lib/angular/angular.min.js"></script>
        <script src="/public/lib/angular-resource/angular-resource.min.js"></script>
        <script src="/public/lib/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
        <script src="/public/js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
