<?php
session_start();
?>

        <div class="centre">
            <div class="cart_card">
                <h1>Items in Cart</h1>
                <?php
                $cartInfo = $_SESSION['cartInformation'];
                echo $cartInfo;
                $_SESSION['cartInformation'] = "";
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