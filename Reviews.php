        <?php
        session_start();
        Require "connect.php";
        Require "ReviewTableController.php";
        $RTC = New ReviewTableController();
        $reviews = $RTC->getAllReviews($conn);
        if ($reviews->num_rows > 0){
            echo "<table>";
            echo "<tr> <th>Review</th> <th>Rating</th> </tr>";
            while($review = $reviews->fetch_assoc()){
                $review_line = $review["review"];
                $rating = $review["RN"];
                echo "<tr> <td>$review_line</td> <td>$rating/5</td> </tr>";
            }
            echo "</table>";
        }
        else{
            echo "<h2>No user reviews exist</h2>";
        }
        ?>