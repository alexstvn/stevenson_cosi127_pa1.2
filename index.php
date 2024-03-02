<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Bootstrap JS dependencies -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COSI 127b</title>
</head>
<body>
    <div class="container">
        <h1 style="text-align:center">COSI 127b</h1><br>
        <h3 style="text-align:center">PA1.2: Connecting Front-End to MySQL DB</h3><br>
    </div>
    <!-- DISPLAY ALL MOVIES -->
    <div class="container">
        <h1>Movies</h1>
        <!-- SHOW ALL MOVIES BUTTON -->
        <form id="viewAllMoviesForm" method="post" action="index.php">
            <div class="input-group mb-3">
                <button class="btn btn-outline-secondary" type="submit" name="allMoviesSubmitted" id="button-addon2">View All Movies</button>
            </div>
        </form>
        <?php
           
            // PRODUCE QUERY ONCE BUTTON HAS BEEN SUBMITTED
            // need to produce columns in if statement, otherwise will go underneath actors
            if(isset($_POST['allMoviesSubmitted']))
            {
                 // we will now create a table from PHP side 
                echo "<table class='table table-md table-bordered'>";
                echo "<thead class='thead-dark' style='text-align: center'>";

                // initialize table headers
                // YOU WILL NEED TO CHANGE THIS DEPENDING ON TABLE YOU QUERY OR THE COLUMNS YOU RETURN
                echo "<tr>
                        <th class='col-md-2'>ID</th>
                        <th class='col-md-2'>Movie Name</th>
                        <th class='col-md-2'>Box Office Collection</th>
                        <th class='col-md-2'>Rating</th>
                        <th class='col-md-2'>Production</th>
                        <th class='col-md-2'>Budget</th>
                        </tr></thead>";
                // generic table builder. It will automatically build table data rows irrespective of result
                class MovieTableRows extends RecursiveIteratorIterator {
                    function __construct($it) {
                        parent::__construct($it, self::LEAVES_ONLY);
                    }

                    function current() {
                        return "<td style='text-align:center'>" . parent::current(). "</td>";
                    }

                    function beginChildren() {
                        echo "<tr>";
                    }

                    function endChildren() {
                        echo "</tr>" . "\n";
                    }
                }
                // SQL CONNECTIONS
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                try {
                    // We will use PDO to connect to MySQL DB. This part need not be 
                    // replicated if we are having multiple queries. 
                    // initialize connection and set attributes for errors/exceptions
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // prepare statement for executions. This part needs to change for every query
                    $stmt = $conn->prepare("SELECT m.mpid, mp.name, m.boxoffice_collection, mp.rating, mp.production, mp.budget
                                            FROM movie m 
                                            INNER JOIN motionpicture mp ON m.mpid = mp.id");

                    // execute statement
                    $stmt->execute();

                    // set the resulting array to associative. 
                    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                    // for each row that we fetched, use the iterator to build a table row on front-end
                    foreach(new MovieTableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                        echo $v;
                    }
                }
                catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                echo "</table>";
                // destroy our connection
                $conn = null;
                
            }
        ?>
    </div>
    <!-- DISPLAY ALL ACTORS -->
    <div class="container">
        <h1>Actors</h1>
        <form id="viewAllActorsForm" method="post" action="index.php">
            <div class="input-group mb-3">
                <button class="btn btn-outline-secondary" type="submit" name="viewAllActorsSubmitted" id="button-addon2">View All Actors</button>
            </div>
        </form>
        <?php
            // we want to check if the submit button has been clicked (in our case, it is named Query)
            if(isset($_POST['viewAllActorsSubmitted'])) {
                $role_name = "actor";
            } else {
                $role_name = "";
            }

            // we will now create a table from PHP side 
            echo "<table class='table table-md table-bordered'>";
            echo "<thead class='thead-dark' style='text-align: center'>";
            echo "<tr>
                    <th class='col-md-2'>ID</th>
                    <th class='col-md-2'>Name</th>
                    <th class='col-md-2'>Role</th>
                    <th class='col-md-2'>Nationality</th>
                    <th class='col-md-2'>Gender</th>
                    </tr></thead>";

            // generic table builder. It will automatically build table data rows irrespective of result
            class ActorTableRows extends RecursiveIteratorIterator {
                function __construct($it) {
                    parent::__construct($it, self::LEAVES_ONLY);
                }

                function current() {
                    return "<td style='text-align:center'>" . parent::current(). "</td>";
                }

                function beginChildren() {
                    echo "<tr>";
                }

                function endChildren() {
                    echo "</tr>" . "\n";
                }
            }

            // SQL CONNECTIONS
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "COSI127b";

            try {
                // We will use PDO to connect to MySQL DB. This part need not be 
                // replicated if we are having multiple queries. 
                // initialize connection and set attributes for errors/exceptions
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // prepare statement with conditional role filtering
                $query = "SELECT p.id, p.name, r.role_name, p.nationality, p.gender 
                            FROM people p 
                            INNER JOIN role r ON p.id = r.pid";

                // FILTERS OUT TO BE ONLY ACTORS - adds onto previous query
                if (!empty($role_name)) {
                    $query .= " WHERE r.role_name = ?";
                }

                $stmt = $conn->prepare($query);

                // bind role name parameter if necessary
                if (!empty($role_name)) {
                    $stmt->bindParam(1, $role_name);
                }

                $stmt->execute();

                // set the resulting array to associative. 
                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                // for each row that we fetched, use the iterator to build a table row on front-end
                foreach(new ActorTableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                    echo $v;
                }
            }
            catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            echo "</table>";
            // destroy our connection
            $conn = null;
        
        ?> 
    </div>
    <!-- USERS LIKING MOVIES -->
    <div class="container">
        <h1>Users Liking Movies</h1>
        <!-- USER LIKES FORM -->
        <form id="usersLikeMoviesForm" method="post" action="index.php">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Enter user's email" name="uemail" id="uemail">
                <input type="text" class="form-control" placeholder="Enter movie ID" name="movieid" id="movieid">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" name="usersLikesSubmitted" id="button-addon2">Like Movie</button>
                </div>
            </div>
        </form>

        <!-- PHP CODE -->
        <?php
            
            // CREATE TABLE 
            echo "<table class='table table-md table-bordered'>";
            echo "<thead class='thead-dark' style='text-align: center'>";
            echo "<tr>
                    <th class='col-md-2'>User Email</th>
                    <th class='col-md-2'>Movie ID</th>
                    <th class='col-md-2'>Movie Name</th>
                </tr></thead>";

            // generic table builder. It will automatically build table data rows irrespective of result
            class LikesMovieTableRows extends RecursiveIteratorIterator {
                function __construct($it) {
                    parent::__construct($it, self::LEAVES_ONLY);
                }

                function current() {
                    return "<td style='text-align:center'>" . parent::current(). "</td>";
                }

                function beginChildren() {
                    echo "<tr>";
                }

                function endChildren() {
                    echo "</tr>" . "\n";
                }
            }

            // SQL CONNECTIONS
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "COSI127b";

            try {
                // We will use PDO to connect to MySQL DB. This part need not be 
                // replicated if we are having multiple queries. 
                // initialize connection and set attributes for errors/exceptions
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // CHECK BUTTON CLICK
                if(isset($_POST['usersLikesSubmitted'])) {
                    // Retrieve user email and movie ID from the form
                    $user_email = $_POST['uemail'];
                    $movie_id = $_POST['movieid'];
            
                    try {
                        // Checks user's existence
                        $query_user = "SELECT * FROM user WHERE email = :email";
                        $stmt_user = $conn->prepare($query_user);
                        $stmt_user->bindParam(':email', $user_email);
                        $stmt_user->execute();
                        $user_exists = $stmt_user->rowCount() > 0;
            
                        // Checks movie existence by ID
                        $query_movie = "SELECT * FROM motionpicture WHERE id = :movie_id";
                        $stmt_movie = $conn->prepare($query_movie);
                        $stmt_movie->bindParam(':movie_id', $movie_id);
                        $stmt_movie->execute();
                        $movie_exists = $stmt_movie->rowCount() > 0;
            
                        // Inserting/updating table to include new likes-relationship
                        if ($user_exists && $movie_exists) {
                            // Insert into likes table
                            $insert_query = "INSERT INTO likes (uemail, mpid) VALUES (:uemail, :mpid)";
                            $stmt_insert = $conn->prepare($insert_query);
                            $stmt_insert->bindParam(':uemail', $user_email);
                            $stmt_insert->bindParam(':mpid', $movie_id);
                            $stmt_insert->execute();
                            echo "<p>Movie liked successfully!</p>";
                        } else {
                            echo "<p>User email or movie ID is invalid!</p>";
                        }
                    } catch (PDOException $e) {
                        // SQL error if duplicate (bc of primary key being unique)
                        if ($e->getCode() === '23000') {
                            echo "<p>This user has already liked this movie.</p>";
                        } else {
                            echo "<p>Error: " . $e->getMessage() . "</p>";
                        }
                    }
                }
                
                // prepare statement with conditional role filtering
                //produces actual table
                $query = "SELECT l.uemail, l.mpid, mp.name 
                            FROM likes l 
                            INNER JOIN motionpicture mp ON mp.id = l.mpid
                            INNER JOIN movie m ON m.mpid=l.mpid";

                $stmt = $conn->prepare($query);

                $stmt->execute();

                // set the resulting array to associative. 
                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                // for each row that we fetched, use the iterator to build a table row on front-end
                foreach(new LikesMovieTableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                    echo $v;
                }
            }
            catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            echo "</table>";
            // destroy our connection
            $conn = null;
        
        ?> 
    </div>
</body>
</html>
