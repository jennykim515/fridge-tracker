<?php
    require('../config/start.php');
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
        $id = $_SESSION["id"];
        $sql = "SELECT SUM(quantity) AS total, category_id
        FROM item
        WHERE users_id = " . $id . "
        GROUP BY category_id;";
        $results = $mysqli->query($sql);
        if ( !$results ) {
            echo $mysqli->error;
            exit();
        }
    
        $results_array = [];
        while($row = $results->fetch_assoc()) {
            // echo $row;
            array_push($results_array, $row);
        }

        echo json_encode($results_array);
    }
    $mysqli->close();
?>