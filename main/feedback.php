<?php include "db.php"; ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $trip_id = $_POST['trip_id'];
    $destination = $_POST['destination'];
    $trip_date = $_POST['trip_date'];
    $rating = $_POST['rating'];
    $info = $_POST['info'];
    $date = $_POST['date'];

    $imagePath = "";
    if ($_FILES['image']['name']) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir);
        $imagePath = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
    }

    $sql = "INSERT INTO reviews (name, email, phone, trip_id, destination, trip_date, rating, info, image, date)
            VALUES ('$name', '$email', '$phone', '$trip_id', '$destination', '$trip_date', '$rating', '$info', '$imagePath', '$date')";
    $conn->query($sql);
    echo "<p style='color:green;'>✅ Feedback submitted!</p>";
}
?>
<!DOCTYPE html>
<html>
<head><title>Submit Feedback</title></head>
<body>
<a href="index.php">⬅ Back to Home</a>
<h2>Submit Your Feedback</h2>
<form method="POST" enctype="multipart/form-data">
    Name: <input type="text" name="name"><br><br>
    Email: <input type="email" name="email"><br><br>
    Phone: <input type="text" name="phone"><br><br>
    Trip ID: <input type="text" name="trip_id"><br><br>
    Destination: <input type="text" name="destination"><br><br>
    Trip Date: <input type="date" name="trip_date"><br><br>
    Rating (1-5): <input type="number" name="rating" min="1" max="5"><br><br>
    Feedback:<br>
    <textarea name="info" required></textarea><br><br>
    Photo: <input type="file" name="image"><br><br>
    Feedback Date: <input type="date" name="date" required><br><br>
    <button type="submit">Submit</button>
</form>
</body>
</html>