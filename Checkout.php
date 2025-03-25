<?php
session_start();
?>

        <div class="centre">
            <div class="cart_card">
                <h1>Items in Cart</h1>
                <?php
                $records = array_values($_POST);
                echo "<table class='cart_table'>";
                echo "<tr><th>Name</th><th>Quantity</th><th>Price</th></tr>";
                $subTotal = 0;
                foreach ($records as $record) {
                    echo "<tr><td>$record[0]</td><td>x$record[1]</td><td>$record[2]</td></tr>";
                    $temp = substr($record[2], 1);
                    $subTotal += $temp;
                }
                echo "</table>";
                echo "<div class='total_price'>SUBTOTAL: $$subTotal</div>";
                ?>   
            </div>
        </div>
        <div class="centre">
            <div class="info_card">
            <section>
            <h1>Payment Information</h1>
            <form method='get' action='ProcessPayment.php'>
                <label for='branches'>Select Branch Location:</label>
                <select id='branches' name='branches' onchange='initMap()'>
                    <option value='Downtown Toronto Branch'>Downtown Toronto Branch</option>
                    <option value='Etobicoke Branch'>Etobicoke Branch</option>
                    <option value='Mississauga Branch'>Mississauga Branch</option>
                </select><br><br>
                <label for='deliveryDate'> Select Delivery Date:</label>
                <input type="date" name="deliveryDate" id="deliveryDate" required>
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber" required>
                <label for="expiryDate">Expiry Date:</label>
                <input type="month" id="expiryDate" name="expiryDate" required>
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
                <input type="hidden" name="cartItems" value="<?php echo htmlspecialchars(serialize($records)); ?>">
                <button type="submit">Pay</button>
            </form>
        </section>
                <div id="map"></div>
            </div>
        </div>


        <script>
        const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
        document.getElementById('deliveryDate').setAttribute('min', today);
        </script>

        <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDc8t67_v0mNHJg-ISUBvdKg2vihgVIZJU&loading=async&libraries=visualization&callback=initMap">
        </script>
        <script>
                var userLat;
                var userLng;
                var userPositionMarker;
                var map;

                var selectedBranchLat;
                var selectedBranchLong;
                var selectedBranchMarker;

                var directionRenderer;

                var options;
                var mapLoads = 0;

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
