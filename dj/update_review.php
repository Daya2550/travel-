<?php include "db.php";
$id = $_GET['id'];

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

    $updateQuery = "UPDATE reviews SET 
        name='$name', email='$email', phone='$phone',
        trip_id='$trip_id', destination='$destination',
        trip_date='$trip_date', rating='$rating',
        info='$info', date='$date'";

    if ($_FILES['image']['name']) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir);
        $imagePath = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
        $updateQuery .= ", image='$imagePath'";
    }

    $updateQuery .= " WHERE id=$id";
    $conn->query($updateQuery);
    header("Location: admin.php");
}

$result = $conn->query("SELECT * FROM reviews WHERE id=$id");
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head><title>Edit Review</title></head>
<body>
<a href="admin.php">⬅ Back to Admin</a>
<h2>Edit Review</h2>
<form method="POST" enctype="multipart/form-data">
    Name: <input type="text" name="name" value="<?= $row['name'] ?>"><br><br>
    Email: <input type="email" name="email" value="<?= $row['email'] ?>"><br><br>
    Phone: <input type="text" name="phone" value="<?= $row['phone'] ?>"><br><br>
    Trip ID: <input type="text" name="trip_id" value="<?= $row['trip_id'] ?>"><br><br>
    Destination: <input type="text" name="destination" value="<?= $row['destination'] ?>"><br><br>
    Trip Date: <input type="date" name="trip_date" value="<?= $row['trip_date'] ?>"><br><br>
    Rating (1-5): <input type="number" name="rating" min="1" max="5" value="<?= $row['rating'] ?>"><br><br>
    Feedback:<br>
    <textarea name="info"><?= $row['info'] ?></textarea><br><br>
    <?php if ($row['image']) echo "<img src='{$row['image']}' width='150'><br>"; ?>
    Upload New Image: <input type="file" name="image"><br><br>
    Feedback Date: <input type="date" name="date" value="<?= $row['date'] ?>"><br><br>
    <button type="submit">Update Review</button>
</form>
</body>
</html>