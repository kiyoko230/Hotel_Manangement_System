<!DOCTYPE HTML>
<head>
<link href="addRoom.css" rel="stylesheet" type="text/css">
</head>
<html>
  <body>
     <fieldset>
      <legend>Add Room:</legend>
        <form id ="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
          <label for="room_type">Room Type:</label><br>
          <!--radio button goes here--> 
          <input type="radio" id="deluxe" name="roomtype" value="Deluxe" checked>
          <label for="deluxe">Deluxe</label>
          <input type="radio" id="standard" name="roomtype" value="Standard">
          <label for="standard">Standard</label><br><br>

          <label for="roomnumber"><span>Room Number:</span></label>
          <input type="text" id="roomnumber" name="roomnumber" value="<?php echo isset($_POST['roomnumber']) ? $_POST['roomnumber'] : ''; ?>"><br><br>

          <label for="roomdetail"><span>Details:</span></label>
          <textarea id="detail" name="detail"
          rows="7" cols="25"><?php echo isset($_POST['detail']) ? $_POST['detail'] : ''; ?></textarea> <br><br>

          <label for="roomprice"><span>Price:</span></label>
          <input type="text" id="roomprice" name="roomprice" value="<?php echo isset($_POST['roomprice']) ? $_POST['roomprice'] : ''; ?>"><br><br>

          <label for="roomunit"><span>Units:</span></label>
          <input type="text" id="roomunit" name="roomunit" value="<?php echo isset($_POST['roomunit']) ? $_POST['roomunit'] : ''; ?>"><br><br>

          <input type="submit" value="Save">
        </form>
        <a href="index.php"><button id="back">Back</button></a>
     </fieldset>
     <?php
    //connect to the database
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "room";

    //create connection
    $mysqli = mysqli_connect($server, $username, $password, $dbname);

    //check connection
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //validate add room form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $roomtype = trim($_POST["roomtype"]);
        $roomnumber = trim($_POST["roomnumber"]);
        $details = trim($_POST["detail"]);
        $price = trim($_POST["roomprice"]);
        $unit = trim($_POST["roomunit"]);
        $checkroomnumber = "SELECT * FROM add_room WHERE roomnumber = '$roomnumber'";
        $result = mysqli_query($mysqli, $checkroomnumber);
        if(empty($roomnumber) || empty($details) || empty($price) ||
        empty($unit) || !preg_match("/^[0-9]{3}$/", $roomnumber) || (!preg_match('/^\d+(\.\d{1,2})?$/', $price) && $price < 0)  || !preg_match('/^[1-9]|10$/', $unit) || mysqli_num_rows($result) > 0){    
          if(!preg_match("/^[0-9]{3}$/", $roomnumber)){
              echo"<p class='err'>The roomnumber must be three digits range from 0 to 9.<br>Example: 001</p>";
          }
          if(empty($details)){
            echo"<p class='err'>Please enter the details regarding the room!</p>";
          }
          if(mysqli_num_rows($result) > 0) {
              echo"<p class='err'>Room number not entered or already exist in database.</p>";
          }
          if (!preg_match('/^\d+(\.\d{1,2})?$/', $price) && $price < 0) {
            echo"<p class='err'>Please enter the room price!</p>";
          }
          if (!preg_match('/^[1-9]|10$/', $unit)) {
          echo"<p class='err'>Our hotel only have unit range from 1 to 10.</p>";
          }
        }
        //submit when form has no incorrect input
        else{
          $addroom = "INSERT INTO add_room VALUES('$roomtype', '$roomnumber', '$details', '$price', '$unit')";

          if(mysqli_query($mysqli, $addroom)){
              echo"<p>Room successfully added to database!!</p>";
          }
          else{
              echo"<p>Failed to Add Room!!!</p>";
          }
        }
      }
    //close connection
    $mysqli->close();
    ?>

  </body>
</html>

