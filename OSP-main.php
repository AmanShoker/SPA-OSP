<?php session_start() ?>
<!DOCTYPE html>
<html lang="en" ng-app="ospApp">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OSP</title>
        <link rel="icon" href="images/shopping_icon.png" type="image/png">
        <link rel="stylesheet" href="OSPstyles.css">
        <link rel="stylesheet" href="CatalogueStyles.css">
        <link rel="stylesheet" href="PaymentStyles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script>
        function allowDrop(ev) {
        ev.preventDefault();
        }
        function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
        }
        function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        window.open("addNewShoppingCartItem.php?itemId=" + data);
        }
        </script>
    
    </head>

    <body>

    <script>
            if (window.location.href.includes('added=True')){
                alert("Successfully added to your shopping cart");
                window.close();
            }     
            else if (window.location.href.includes('added=False')){
                alert("Item Already contained in shopping cart");
                window.close();
            }
            else if (window.location.href.includes('created=True')){
                alert("Review Successfully Created");
            }
    </script>
    
    <header>
            <ul>
                <li><img src="images/shopping_icon.png"></li>
                <li><a href="#/">Home</a></li>
                <li><a href="#!aboutus">About Us</a></li>
                <li>
                    <a href="">Catalogue</a>
                    <ul class="dropdown">
                        <li><a href="#!catalogue">Electronics</a></li>
                        <li><a href="">Gardening</a></li>
                        <li><a href="">Sports</a></li>
                        <li><a href="">Clothes</a></li>
                    </ul>
                </li>
                <li id="shoppingCart" ondrop="drop(event)" ondragover="allowDrop(event)"><a href="#!shoppingCart">Shopping Cart</a></li>
                <li>
                    <a href="#">Reviews</a>
                    <ul class="dropdown">
                        <li><a href="#!reviews">View Reviews</a></li>
                        <?php 
                        if (isset($_SESSION['username'])){
                            echo "<li><a href='#!createReview'>Create Review</a></li>";
                        }
                        ?>
                    </ul>
                </li>
                <?php 
                    require "connect.php";
                    require "UserTableController.php";
                    
                    if (isset($_SESSION['username'])) {
                        $UTC = New UserTableController();
                        $username = $_SESSION["username"];
                        
                        if ($UTC->checkIfAdmin($conn,$username)): ?>
                <li>
                    <a href="">DB Maintain</a>
                    <ul class="dropdown">
                        <li><a href="AdminInsert.php">Insert</a></li>
                        <li><a href="AdminDelete.php">Delete</a></li>
                        <li><a href="AdminSelect.php">Select</a></li>
                        <li><a href="AdminUpdate.php">Update</a></li>
                    </ul>
                </li>
                <?php endif; } ?>
                
                <?php if (isset($_SESSION['username'])): ?>
                    <li id="searchToggle">
                        <a id="searchLink">Search</a>

                        <form  ng-submit='submitForm()' ng-controller='searchFormCtrl' id="searchBar">
                            <input type="text" name="searchQuery" placeholder="Search by Order-Id">
                            <button type="submit" class="search">Search</button>
                        </form>
                    </li>
                <?php endif; ?>

                <li id="signIn-Up">
                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="SignOut.php" class="signOut">Sign Out</a>
                    <?php else: ?>
                        <a href="OSP-begin.html#!/signIn" class="sign-in">Sign-in</a>
                        <a href="OSP-begin.html#!/signUp" class="sign-up">Sign Up</a>
                    <?php endif; ?>
                </li>
            </ul>
        </header>
        <div ng-view></div>
        <script>
        var mapLoads = 0;
        var app = angular.module('ospApp', ['ngRoute']);
            app.config(function($routeProvider) {
                $routeProvider
                .when('/', {
                templateUrl : 'Homepage.php'})
                .when('/aboutus', {
                templateUrl : 'AboutUsPage.php'})
                .when('/reviews', {
                templateUrl : 'Reviews.php'})
                .when('/createReview', {
                templateUrl : 'CreateReview.php'})
                .when('/searchResult', {
                templateUrl : 'SearchResult.php'})
                .when('/catalogue', {
                templateUrl : 'Catalogue.php'})
                .when('/shoppingCart', {
                templateUrl : 'ShoppingCart.php',
                controller : 'shoppingCartFormCtrl'})
                .when('/checkout', {
                templateUrl : 'Checkout.php',
                controller : 'checkoutFormCtrl'})
                .when('/invoiceSummary', {
                templateUrl : 'invoiceSummary.php'})
                .otherwise({redirectTo: '/'});
                });

                app.controller('searchFormCtrl', function ($scope, $http, $location, $templateCache, $route) {
                $scope.submitForm = function () {
                    var formData = new FormData(document.querySelector('#searchBar'));
                    $http.post('ProcessSearch.php', formData, {
                    headers: {'Content-Type': undefined}, transformRequest: angular.identity
                    }).then(
                        function(response){
                            $templateCache.remove('SearchResult.php');
                            if ($location.path() === '/searchResult'){
                                $route.reload();
                            }
                            $location.path('searchResult');
                        }
                    )
                }
            });

            app.controller('shoppingCartFormCtrl', function ($scope, $http, $location) {
                $scope.submitForm = function () {
                    var formData = new FormData(document.querySelector('#cartForm'));
                    $http.post('saveCartDataToSession.php', formData, {
                    headers: {'Content-Type': undefined}, transformRequest: angular.identity
                    }).then(
                        function(response){
                            $location.path('checkout');
                        }
                    )
                }
            });

            app.controller('checkoutFormCtrl', function ($scope, $http, $location) {
                $scope.submitForm = function () {
                    var formData = new FormData(document.querySelector('#checkoutForm'));
                    $http.post('saveCheckOutDataToSession.php', formData, {
                    headers: {'Content-Type': undefined}, transformRequest: angular.identity
                    }).then(
                        function(response){
                            $location.path('invoiceSummary');
                        }
                    )
                }
            });
            
            app.run(function($rootScope, $templateCache, $route) {
                $rootScope.$on('$routeChangeStart', function(event, next, current) {
                    if (next.templateUrl === 'Checkout.php'){
                        $templateCache.remove(next.templateUrl);
                    }
                    else if (next.templateUrl === 'invoiceSummary.php'){
                        $templateCache.remove(next.templateUrl);
                    }
                 });

                $rootScope.$on('$viewContentLoaded', function(){
                    if ($('#map').length) {
                        mapLoads = 0;
                        initMap();
                        
                    }
                    if ($('#cartForm').length){
                        $(":input[type=number]").bind('keyup mouseup', function () {
                            let priceId = $(this).attr("updateFieldId");
                            let quantity = $(this).val();
                            let price = $(this).attr("itemPrice");
                            document.getElementById(priceId).value = "$" + (price*quantity);       
                        });
                    }

                    if ($('#checkoutForm').length){
                                $("#paymentOption").on("change", function(){
                                    $("#paymentFields").html("");
                                    if ($(this).val() == "Credit Card"){
                                        $("#paymentFields").append('<br><label for="cardNumber">Credit Card Number:</label> <input type="text" id="cardNumber" name="cardNumber" required><br><br>');
                                        $("#paymentFields").append('<label for="expiryDate">Expiry Date:</label> <input type="month" id="expiryDate" name="expiryDate" required><br><br>');
                                        $("#paymentFields").append(' <label for="cvv">CVV:</label> <input type="text" id="cvv" name="cvv" required>');
                                    }
                                    else if ($(this).val() == "Debit Card"){
                                        $("#paymentFields").append('<br><label for="cardNumber">Debit Card Number:</label> <input type="text" id="cardNumber" name="cardNumber" required><br><br>');
                                        $("#paymentFields").append('<label for="expiryDate">Expiry Date:</label> <input type="month" id="expiryDate" name="expiryDate" required><br><br>');
                                        $("#paymentFields").append(' <label for="cvv">CVV:</label> <input type="text" id="cvv" name="cvv" required>');
                                    }

                                    else if ($(this).val() == "Gift Card"){
                                        $("#paymentFields").append('<br><label for="cardNumber">Gift Card Number:</label> <input type="text" id="cardNumber" name="cardNumber" required><br><br>');
                                    }
                                });
                            function updateDeliveryDate() {
                                const shippingMethod = document.getElementById('shippingMethod').value;
                                const deliveryDateInput = document.getElementById('deliveryDate');
                                const today = new Date();
                                
                                shippingCost = shippingMethod === 'express' ? 50 : 0;
                                document.getElementsByName('shippingCost')[0].value = shippingCost;

                                const deliveryDate = new Date(today);
                                if (shippingMethod === 'express') {
                                    deliveryDate.setDate(today.getDate() + 1);
                                } else {
                                    deliveryDate.setDate(today.getDate() + 7);
                                }
                                
                                const formattedDate = deliveryDate.toISOString().split('T')[0];
                                deliveryDateInput.setAttribute('min', formattedDate);
                                deliveryDateInput.value = formattedDate;
                            }
                            updateDeliveryDate();
                            document.getElementById('shippingMethod').addEventListener('change', updateDeliveryDate);
                    }
                });
            });
        
                var userLat;
                var userLng;
                var userPositionMarker;
                var map;

                var selectedBranchLat;
                var selectedBranchLong;
                var selectedBranchMarker;

                var directionRenderer;

                var options;

            async function initMap(){
                
                const userIconUrl = "https://img.buzzfeed.com/buzzfeed-static/static/enhanced/webdr06/2013/4/11/1/enhanced-buzz-24965-1365659349-6.jpg?downsize=700%3A%2A&output-quality=auto&output-format=auto";
                const branchIconUrl = "https://cdn-icons-png.flaticon.com/512/5439/5439360.png";

                const userIcon ={
                    url: userIconUrl,
                    scaledSize: new google.maps.Size(50, 50)
                };
                const branchIcon ={
                    url: branchIconUrl,
                    scaledSize: new google.maps.Size(50, 50)
                };

                if (mapLoads == 0){
                    navigator.geolocation.getCurrentPosition(showPosition);
                    mapLoads = 1;
                }
                else{
                setBranchLocMarker();
                }

                function showPosition(position){
                userLat = position.coords.latitude;
                userLng = position.coords.longitude;
                options = {
                    zoom:10,
                    center:{lat:userLat,lng:userLng}
                    }
                map = new google.maps.Map(document.getElementById('map'), options);
                
                userPositionMarker = new google.maps.Marker({position:{lat:userLat,lng:userLng}, map:map, icon:userIcon});
                setBranchLocMarker();
                }

                function setBranchLocMarker(){
                    if (typeof selectedBranchMarker == "object" ){
                        selectedBranchMarker.setMap(null);
                    }
                    let selectedBranch = document.getElementById("branches").value;
                    if (selectedBranch == "Downtown Toronto Branch"){
                        selectedBranchLat = 43.659561;
                        selectedBranchLong = -79.400377;
                    }
                    else if (selectedBranch == "Etobicoke Branch"){
                        selectedBranchLat = 43.669121;
                        selectedBranchLong = -79.540505;
                    }
                    else if (selectedBranch == "Mississauga Branch"){
                        selectedBranchLat = 43.596023;
                        selectedBranchLong = -79.694742;
                    }
                    selectedBranchMarker = new google.maps.Marker({position:{lat:selectedBranchLat,lng:selectedBranchLong}, map:map, icon:branchIcon});
                    //selectedBranchMarker.setMap(map);
                    console.log();

                    createPathBetween(selectedBranchMarker.position,userPositionMarker.position);
                }

                function createPathBetween(start, end){
                    const request = {
                        origin: start,
                        destination: end,
                        travelMode: google.maps.DirectionsTravelMode.WALKING
                    }
                    if (typeof directionRenderer == "object"){
                        directionRenderer.setMap(null);
                    }
                    directionService = new google.maps.DirectionsService();
                    directionRenderer = new google.maps.DirectionsRenderer();
                    directionRenderer.setMap(map);
                    directionService.route(request, function(response, status){
                        if (status === google.maps.DirectionsStatus.OK){
                            directionRenderer.setDirections(response);
                        }
                    })
                }
        }
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchLink = document.getElementById('searchLink');
                const searchForm = document.getElementById('searchBar');

                searchLink.addEventListener('click', function(e) {
                    searchForm.classList.toggle('show');
                });
            });
        </script>
        
        <script async
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDc8t67_v0mNHJg-ISUBvdKg2vihgVIZJU&loading=async&libraries=visualization">
        </script>

    </body>
</html>
