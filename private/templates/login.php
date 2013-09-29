<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" ng-app="CPCCA"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" ng-app="CPCCA"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" ng-app="CPCCA"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" ng-app="CPCCA"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title ng-bind="title">CPCCA</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <!-- <link rel="stylesheet" href="/public/css/bootstrap.min.css"> -->

    <link rel="stylesheet" href="/public/css/bootstrap-slate.min.css">
    <style>
            /*body {
                padding-top: 50px;
                padding-bottom: 20px;
            }*/
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            /*background-color: #eee;*/
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }
        .form-signin .checkbox {
            font-weight: normal;
        }
        .form-signin .form-control {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>

    <link rel="stylesheet" href="/public/css/main.css">

    <script src="/public/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>
<body>
    <div class="container">
        <form class="form-signin" action="/api/login" method="POST">
            <h2 class="form-signin-heading">Please sign in</h2>
            <input type="text" class="form-control" placeholder="Email address" autofocus="" name="email">
            <input type="password" class="form-control" placeholder="Password" name="password">
            <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
            </label>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            <div><p class="text-danger"><?php echo $flash["message"] ?></p></div>
        </form>
    </div>
</body>
</html>