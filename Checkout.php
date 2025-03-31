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

            <div class="info_card">
            <section>
            <h1>Payment Information</h1>
            <form ng-submit='submitForm()' ng-controller='checkoutFormCtrl' id="checkoutForm">
                <label for='branches'>Select Branch Location:</label>
                <select id='branches' name='branches' onchange='initMap()'>
                    <option value='Downtown Toronto Branch'>Downtown Toronto Branch</option>
                    <option value='Etobicoke Branch'>Etobicoke Branch</option>
                    <option value='Mississauga Branch'>Mississauga Branch</option>
                </select><br>
                <label for="shippingMethod">Shipping Method:</label>
                <select id="shippingMethod" name="shippingMethod">
                <option value="standard">Standard (Free)</option>
                <option value="express">Express ($50)</option>
                </select><br>
                <label for='deliveryDate'> Select Delivery Date:</label>
                <input type="date" name="deliveryDate" id="deliveryDate" required>
                <label for='paymentOption'> Select Payment Option:</lable>
                <select name="paymentOption" id="paymentOption" required>
                    <option value="" disabled selected>...</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Gift Card">Gift Card</option>
                    <option value="Cash">Cash</option>
                </select>
                <div id="paymentFields">
                </div>
                <input type="hidden" name="shippingCost" value="">
                <input type="hidden" name="cartItems" value="<?php echo $cartInfo; ?>">
                <button type="submit">Pay</button>
            </form>
        </section>
                <div id="map"></div>
            </div>
        </div>

        <script>
            
        </script>


        <script>
        const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
        document.getElementById('deliveryDate').setAttribute('min', today);
        </script>