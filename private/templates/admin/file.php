<?php
/**
 * Created by JetBrains PhpStorm.
 * User: terrence
 * Date: 9/28/13
 * Time: 10:45 PM
 * To change this template use File | Settings | File Templates.
 */

?>
<h2 ng-show="!file.title">New entry</h2>
<h2 ng-show="file.title">{{file.title}}</h2>
<div class="container">
    <div class="row">
        <form role="form">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" ng-model="file.title">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" ng-model="file.description" name="description" id="description"></textarea>
            </div>
            <div class="form-group">
                <label>Filename</label>
                <p class="form-control-static">{{ file.filename}}</p>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <label for="order">Order</label>
                    <select ng-model="file.order" name="order" id="order" class="form-control" ng-options="r for r in range" ng-init="file.order = file.order || range[0]">
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 pull-right">
                    <button type="submit" class="btn btn-default form-control" ng-click="submit()">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
