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
            }     
            else if (window.location.href.includes('added=False')){
                alert("Item Already contained in shopping cart");
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
                <li><a href="#">Reviews</a></li>
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

                        <form id="searchBar" action="Search.php" method="GET">
                            <input type="text" name="searchQuery" placeholder="Search by Order-Id">
                            <button type="submit" class="search">Search</button>
                        </form>
                    </li>
                <?php endif; ?>

                <li id="signIn-Up">
                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="SignOut.php" class="signOut">Sign Out</a>
                    <?php else: ?>
                        <a href="SignIn.html" class="sign-in">Sign-in</a>
                        <a href="SignUp.html" class="sign-up">Sign Up</a>
                    <?php endif; ?>
                </li>
            </ul>
        </header>
        
        <div ng-view></div>
        <script>
        var app = angular.module('ospApp', ['ngRoute']);
            app.config(function($routeProvider) {
                $routeProvider
                .when('/', {
                templateUrl : 'Homepage.php'})
                .when('/aboutus', {
                templateUrl : 'AboutUsPage.php'})
                .when('/catalogue', {
                templateUrl : 'Catalogue.php'})
                .when('/shoppingCart', {
                templateUrl : 'ShoppingCart.php'})
                .when('/checkout', {
                templateUrl : 'Checkout.php'})
                .when('/processPayment', {
                templateUrl : 'ProcessPayment.php'})
                .otherwise({redirectTo: '/'});
                });


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
    </body>
</html>
