<?php
    require "config/start.php";
    if(isset($loggedin) && !empty($loggedin)) {
        $user_id = $_SESSION["id"];
        $statement = $mysqli->prepare("SELECT item.id AS item_id, item.name AS item_name, expire, received, quantity, users_id, category_id, category.name AS category_name
        FROM item
        JOIN category
        ON category.id = item.category_id
        WHERE users_id = ?
        ORDER BY DATEDIFF(expire, NOW()), category_id;
        ");
        $statement->bind_param("i", $user_id);
        $executed = $statement->execute();
        if(! $executed) {
            echo $mysqli->error;
        }
        $result = $statement->get_result();
        $statement->close();
        $mysqli->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/navbar.css">
    <title>eatsafe</title>
    <!-- bootstrap for form -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/navbar.css">
    <style>
        .hide {
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="hide" id="extra-space"></div>
    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]): ?>
        <div class="jumbotron d-flex align-items-center min-vh-100">
            <div class="container" style="width:50%;">
                <?php if(isset($loggedin) && !empty($loggedin)) : ?>
                    <form>
                    <div><h1>filter your search</h1></div>
                        <div class="form-group">
                            <label for="diet">Diet</label>
                            <select class="form-control" id="diet" name="diet">
                                <option>None</option>
                                <option>Vegetarian</option>
                                <option>Vegan</option>
                                <option>Pescetarian</option>
                                <option>Gluten Free</option>
                                <option>Ketogenic</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="intolerance">Allergies/Intolerances</label>
                            <select class="form-control" id="intolerance" name="intolerance">
                            <option>None</option> 
                            <option>Dairy</option> 
                            <option>Egg</option> 
                            <option>Peanut Allergy</option>
                            <option>Tree Nuts</option>
                            <option>Soy</option>
                            <option>Gluten</option>
                            <option>Seafood</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ingredients">Must-Include Ingredients</label>
                            <select multiple class="form-control" name="ingredients" id="ingredients">
                            <option>None</option>
                            <?php while ( $row = $result->fetch_assoc() ) : ?>
                                <option><?php echo $row["item_name"]?></option>
                            <?php endwhile; ?>
                            </select>
                        </div>
                        <input type="submit" value="Search">
                    </form>

                    <div class="container">
                        <div class="row" id="results_insert">
                                
                        </div>
                    </div>
                <?php endif;?>
            </div>
        </div>
        <?php else: ?>
            <div class="center">
                Please log in to access this page
            </div>
    <?php endif; ?>
    <script src="javascript/toggle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        function ajaxPost(endpointUrl, postData, returnFunction){
			var xhr = new XMLHttpRequest();
			xhr.open('GET', endpointUrl, true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function(){
				if (xhr.readyState == XMLHttpRequest.DONE) {
					if (xhr.status == 200) {
						returnFunction( xhr.responseText );
					} else {
						alert('AJAX Error.');
						console.log(xhr.status);
					}
				}
			}
			xhr.send(postdata);
		};

        document.querySelector("form").onsubmit = function(event) {
			event.preventDefault();
            let endpoint = "https://api.spoonacular.com/recipes/complexSearch?";

            let diet = document.querySelector("#diet").value;
            if(diet != "None") {
                endpoint += `diet=${diet}`;
            }
            let intolerance = document.querySelector("#intolerance").value;
            if(intolerance != "None") {
                if(diet != "None") {
                    endpoint += "&";
                }
                endpoint += `intolerances=${intolerance}`
            }
            let ingredients = document.querySelector("#ingredients");
            //check if ingredients has "NONE" in it
            
            endpoint += "includeIngredients=";
            for(let i = 0; i < ingredients.options.length; i++) {
                let this_ingredient = ingredients.options[i];
                if(this_ingredient.selected) {
                    this_ingredient = this_ingredient.value.replace(/\s/g, '+');
                    endpoint += this_ingredient;
                    endpoint += ",";
                }
            }
            endpoint = endpoint.slice(0, -1)
            
            endpoint += "&sort=popularity&number=20&apiKey=f9203658edae497fabe1c50db1d251c5";
            console.log(endpoint);
            apiCall(endpoint);
        }

        function apiCall(endpoint) {
            let httpRequest = new XMLHttpRequest();
            httpRequest.open("GET", endpoint);
            httpRequest.send();
            httpRequest.onreadystatechange = function() {
                //we got a response
                if(httpRequest.readyState == 4) {
                    //we got a successful response
                    if(httpRequest.status == 200) {
                        displayResults(httpRequest.responseText);
                    }
                    else {
                        alert("AJAX error");
                    }
                }
            }
        }
        function displayResults(text) {
            let resultJS = JSON.parse(text);
            // console.log(resultJS.results);
            let result = resultJS.results;
            document.querySelector("form").classList.add("hide")
            for(let i = 0; i < result.length; i++) {
                let item = result[i];
                let recipe_id = item.id;
                
                // apiCall(`https://api.spoonacular.com/recipes/716429/information?includeNutrition=false&id=${recipe_id}&apiKey=f9203658edae497fabe1c50db1d251c5`)


                let htmlString = `
                <div class="col-6">
                    <a href="details.php?id=${recipe_id}"><img src="${item.image}"></a>
                    <p>${item.title}</p>
                    <div class="hidden">${item.id}</div>
                </div>
                `;
                document.querySelector("#results_insert").innerHTML += htmlString;
            }            

        }
    </script>
</body>
</html>