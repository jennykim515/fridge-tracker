<?php
    require "../config/start.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/navbar.css">
    <title>eatsafe</title>
    <style>
        .container {
            width: 75%;
        }
        #display-charts .col { 
            background-color: white;
            border-radius: 15px;
            margin: 10px;
        }
        .row {
            margin: auto;
            /* border: 1px solid red; */
            display: flex;
            justify-content: center;
        }
        #barChart, #myChart {
            max-height: 300px;
        }
        #account .col-12 {
            /* border: 1px solid red; */
            background-color: white;
            border-radius: 20px;
            text-align: center;
            padding: 15px;
            margin: 10px;
        }

        .row a {
            color: black;
            text-decoration: none;
        }
        .row a:hover {
            color: orange;
            background-color: black;
        }
    </style>
</head>
<body>
    <?php if( isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]): ?>
        <?php include 'nav.php'; ?>
            <div id="extra-space"></div>
        <div class="container">
            <h1>Welcome back, <?php echo $_SESSION["name"]; ?></h1>
            <p>Here's what's in your fridge today.</p>
            <br>
            <div class="row" id="display-charts">
                <div class="col col-lg-5 col-sm-12">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="col col-lg-5 col-sm-12">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            
            <br><br><hr><br>
            <h3>Your account</h3>
            <div class="row" id="account">
                <a href="logout.php" class="col-12 col-lg-3 col-sm-12">
                        Log Out
                </a>
                <a href="update_password.php" class="col-12 col-lg-3 col-sm-12">
                    Update Password
                </a>
                <a href="delete_account.php" class="col-12 col-lg-3 col-sm-12">
                        Delete Account
                </a>
            </div>
        </div>
    <?php else: ?>
        <?php echo '<meta http-equiv="refresh" content="0; URL=login.php" />';?>
    <?php endif;?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../javascript/toggle.js"></script>

<script>
    const category_labels = ["fruits", "vegetables", "dairy", "protein", "beverages", "condiments", "snacks", "takeout", "grains", "leftovers"];

    function ajaxGet(endpointUrl, returnFunction){
        var xhr = new XMLHttpRequest();
        xhr.open('GET', endpointUrl, true);
        xhr.onreadystatechange = function(){
            if (xhr.readyState == XMLHttpRequest.DONE) {
                if (xhr.status == 200) {
                    // When ajax call is complete, call this function, pass a string with the response
                    returnFunction( xhr.responseText );
                } else {
                    alert('AJAX Error.');
                    console.log(xhr.status);
                }
            }
        }
        xhr.send();
    };

    const data = {
        labels: [],
        datasets: [{
            label: 'My First Dataset',
            data: [],
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(66, 135, 245)',
            'rgb(117, 201, 132)',
            'rgb(134, 159, 196)',
            'rgb(237, 225, 147)',
            'rgb(237, 186, 147)',
            'rgb(240, 168, 221)'
            ],
            hoverOffset: 4
        }]
    };

    const bar_data = {
        labels: ["Expired", "Healthy"],
        datasets: [{
            label: 'Counter',
            data: [],
            backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            ],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
    };

    const bar_config = {
        type: 'bar',
        data: bar_data,
        options: {
            scales: {
            y: {
                beginAtZero: true
            }
            }
        },
    };
    

    
    
    ajaxGet("chart.php", function(results) {
        let JSresult = JSON.parse(results);
        console.log(JSresult);
        for(let i = 0; i < JSresult.length; i++) {
            let index = parseInt(JSresult[i].category_id) - 1; // index of category
            data.labels.push(category_labels[index]);
            data.datasets[0].data.push(parseInt(JSresult[i].total));
        }
        const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
    ajaxGet("num_expired.php", function(results) {
        let total = 0;
        for (let i in data.datasets[0].data) {
            total += data.datasets[0].data[i];
        }
        console.log("Total " + total);
        let JSresult = JSON.parse(results);
        
        let num_expired = (JSresult[0].num_expired);
        console.log("num expired " + num_expired);
        console.log(total - num_expired);
        // number of healthy foods
        bar_data.datasets[0].data.push(num_expired, total - num_expired);
        const barChart = new Chart(
        document.getElementById('barChart'),
        bar_config

    );
    })

    });

    
    
</script>
</body>
</html>