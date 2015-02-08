<?php 
   try
   {
      $user = "php";
      $password = "php-pass";
      $db = new PDO("mysql:host=127.0.0.1;dbname=phonedb", $user, $password);
   }
   catch (PDOException $e)
   {
      echo "ERROR: " . $e->getMessage();
      die();
   }

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Assignment 3 - PHP DB Access</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   
   <!-- Main CSS Style sheet for general styling -->
   <link rel="stylesheet" href="../css/main.css">
   
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
   
   <script src="../js/main.js"></script>
   
</head>
<body>
	<div class="container">
      <?php
         function createModal($lat,$lon,$id)
         {
            $googleAPIKey = "AIzaSyB-NY8Tr6mZJB9Wr_c2qlBptlAYF3vzx8o";
            echo "<div class='modal fade' id='myModal".$id."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                     <div class='modal-dialog'>
                        <div class='modal-content'>
                           <div class='modal-header'>
                             <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                             <h4 class='modal-title' id='myModalLabel'>Phone $id</h4>
                           </div>
                           <div class='modal-body' id='map'>
                              <iframe id='map' width='100%' height='500' frameborder='0' style='border:0' src='https://www.google.com/maps/embed/v1/search?key=AIzaSyB-NY8Tr6mZJB9Wr_c2qlBptlAYF3vzx8o&q=".$lat.",".$lon."'></iframe>
                           </div>
                           <div class='modal-footer'>
                             <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                           </div>
                        </div>
                     </div>
                  </div>";
         }
      ?>
      <div class="col-sm-12 content-container">
         <div class="col-sm-8">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <h2>Username: (try "show all")</h2><p><input type="text" class="form-control" name="username" placeholder="Username"></p>
         </div>
         <div class="col-sm-3">
            <br/>
            <br/>
            <input type="submit" class="btn btn-default btn-lg" value="Query!"><br/>
            </form>
         </div>
      </div>
      
      <?php
         if($_SERVER["REQUEST_METHOD"] == "POST")
         {
            $queryUserName = $_POST["username"];
            if ($_POST["username"] == "show all")
            {
               $queryStatement = $db->query("SELECT username, account_id FROM user");
            }
            else
            {
               $queryStatement = $db->query("SELECT username, account_id FROM user WHERE username = \"".$_POST["username"]."\"");
            }
            while ($row = $queryStatement->fetch(PDO::FETCH_ASSOC))
            {
               echo "<div class='row'>";
               echo "<div class='col-sm-3 content-container'>";
               echo "<h2>Username: ".$row['username']."</h2>";
               echo "<h3>Account:  ".$row['account_id']."</h2>";
               echo "</div>"; #end the user column
               
               echo "<div class='col-sm-8 content-container'>";
               echo "<h3>List of available Phones on Account</h3>";
               echo "<table>\n";
               echo "<tr>\n";
               echo "<th>Phone ID</th>";
               echo "<th>Phone Name</th>";
               echo "<th>Phone I/O</th>";
               echo "</tr>\n";
               foreach($db->query("SELECT p.id, p.name, p.connection FROM user u JOIN phone p ON p.account_id = u.account_id WHERE u.username = \"".$row['username']."\"") as $phone)
               {
                  echo "<tr>\n";
                  echo "<td>".$phone['id']."</td>\n";
                  echo "<td>";
              
                  $phoneLocQ = $db->query("SELECT l.latitude, l.longitude FROM phone p JOIN locationhistory l ON p.id = l.phone_id WHERE p.id=".$phone['id']);
                  $location = $phoneLocQ->fetch(PDO::FETCH_ASSOC);
                  if($location)
                  {
                     createModal($location['latitude'],$location['longitude'],$phone['id']);
                     echo "<a href='#myModal".$phone['id']."' data-toggle='modal'>".$phone['name']."</a>";
                  }
                  else
                  {
                     echo $phone['name'];
                  }
                  echo "</td>\n";
                  echo "<td>";
                  if ($phone['connection'] == 1)
                  {
                     echo "<span class='glyphicon glyphicon-ok' style='color:green'/>";
                  }
                  else
                  {
                     echo "<span class='glyphicon glyphicon-remove' style='color:red'/>";
                  }
                  echo "</tr>\n";
               }
               echo "</table>\n";
               echo "</div></div><hr/>"; #second </div> to close row.
               
            }
         }
      ?>
      
	</div>
</html>
