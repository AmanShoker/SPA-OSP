<?php
session_start();
?>
        <h2>Leave a review letting everyone know what you think about a product or service!</h2>
        <form action="addNewReview.php" method="post">
        <table>
                <tr> <th>Review</th> <th>Rating</th> </tr>
                <tr> <td> <input name="review" type="text" size="150" maxlength="150"> </td> <td> <input name="rating" type="number" value=1 min="1" max="5"> </td> </tr>
        </table>
        <input style="float:right; margin-right:5%;" type="submit" name="createReview" value="Create">
        </form>