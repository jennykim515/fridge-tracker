<?php
    // returns number of expired foods
    require('../config/start.php');
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
        $id = $_SESSION["id"];
        $sql = "SELECT SUM(quantity) AS num_expired
        FROM item
        WHERE users_id = " . $id . " AND DATEDIFF(expire, NOW()) < 0;";

        $results = $mysqli->query($sql);
        if ( !$results ) {
            echo $mysqli->error;
            exit();
        }
        $results_array = [];
        while($row = $results->fetch_assoc()) {
            array_push($results_array, $row);
        }
        echo json_encode($results_array);
    }
    $mysqli->close();
?>
