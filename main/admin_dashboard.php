<?php include 'db.php'; ?>

<?php
// -------- DELETE TOUR PACKAGE --------
if (isset($_GET['delete_package'])) {
    $id = $_GET['delete_package'];
    $conn->query("DELETE FROM tour_packages WHERE id=$id");
    header("Location: admin_dashboard.php");
    exit();
}

// -------- UPDATE TOUR PACKAGE --------
$edit_mode = false;
if (isset($_GET['edit_package'])) {
    $edit_mode = true;
    $id = $_GET['edit_package'];
    $result = $conn->query("SELECT * FROM tour_packages WHERE id=$id");
    $row = $result->fetch_assoc();
}

// -------- ADD/UPDATE TOUR PACKAGE --------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_package'])) {
    $trip_name = $_POST['trip_name'];
    $distance = $_POST['distance'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, 'uploads/' . $image);
    } else {
        $image = isset($row['image']) ? $row['image'] : '';
    }

    if ($edit_mode) {
        $sql = "UPDATE tour_packages SET 
                trip_name='$trip_name',
                distance='$distance',
                price='$price',
                description='$description',
                image='$image'
                WHERE id=$id";
    } else {
        $sql = "INSERT INTO tour_packages (trip_name, distance, price, description, image)
                VALUES ('$trip_name', '$distance', '$price', '$description', '$image')";
    }

    if ($conn->query($sql)) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// -------- DELETE VEHICLE IMAGE --------
if (isset($_GET['delete_vehicle'])) {
    $delete_id = $_GET['delete_vehicle'];
    $result = $conn->query("SELECT image FROM vehicles WHERE id = $delete_id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = 'vehicle_uploads/' . $row['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    $conn->query("DELETE FROM vehicles WHERE id = $delete_id");
    header("Location: admin_dashboard.php");
    exit();
}

// -------- ADD VEHICLE IMAGE --------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_vehicle'])) {
    $category = $_POST['category'];
    $image = $_FILES['vehicle_image']['name'];
    $image_tmp = $_FILES['vehicle_image']['tmp_name'];
    $upload_dir = 'vehicle_uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    move_uploaded_file($image_tmp, $upload_dir . $image);
    $conn->query("INSERT INTO vehicles (category, image) VALUES ('$category', '$image')");
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Tours & Vehicles</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        button {
            background: #007BFF;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .card-container, .gallery-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card, .vehicle-card {
            border: 1px solid #ccc;
            border-radius: 10px;
            width: 270px;
            background: white;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card img, .vehicle-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .card-body, .details {
            padding: 12px;
        }
        .card-title {
            font-size: 20px;
            font-weight: bold;
        }
        .card-text {
            margin: 5px 0;
        }
        .actions a {
            color: white;
            text-decoration: none;
            padding: 6px 10px;
            background: #dc3545;
            border-radius: 5px;
            font-size: 14px;
        }
        .actions a.edit {
            background: #28a745;
            margin-right: 8px;
        }
    </style>
</head>
<body>

<!-- TOUR PACKAGE FORM -->
<h2><?= $edit_mode ? 'Edit' : 'Add' ?> Tour Package</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="trip_name" placeholder="Trip Name" required value="<?= $edit_mode ? $row['trip_name'] : '' ?>">
    <input type="text" name="distance" placeholder="Distance" required value="<?= $edit_mode ? $row['distance'] : '' ?>">
    <input type="text" name="price" placeholder="Price" required value="<?= $edit_mode ? $row['price'] : '' ?>">
    <textarea name="description" placeholder="Description"><?= $edit_mode ? $row['description'] : '' ?></textarea>
    <input type="file" name="image" <?= $edit_mode ? '' : 'required' ?>>
    <button type="submit" name="save_package"><?= $edit_mode ? 'Update' : 'Add' ?> Package</button>
</form>

<!-- TOUR PACKAGE LIST -->
<h2>All Tour Packages</h2>
<div class="card-container">
<?php
$result = $conn->query("SELECT * FROM tour_packages");
while ($row = $result->fetch_assoc()) {
    echo "<div class='card'>
            <img src='uploads/{$row['image']}' alt='Trip Image'>
            <div class='card-body'>
                <div class='card-title'>{$row['trip_name']}</div>
                <div class='card-text'><strong>Price:</strong> ₹{$row['price']}</div>
                <div class='card-text'><strong>Distance:</strong> {$row['distance']}</div>
                <div class='card-text'>{$row['description']}</div>
            </div>
            <div class='actions' style='padding: 10px; text-align:center;'>
                <a class='edit' href='admin_dashboard.php?edit_package={$row['id']}'>Edit</a>
                <a href='admin_dashboard.php?delete_package={$row['id']}' onclick=\"return confirm('Delete this package?')\">Delete</a>
            </div>
        </div>";
}
?>
</div>

<hr>

<!-- ADD VEHICLE FORM -->
<h2>Add Vehicle Image</h2>
<form method="post" enctype="multipart/form-data">
    <select name="category" required>
        <option value="">Select Category</option>
        <option value="SUV">SUV</option>
        <option value="Sedan">Sedan</option>
        <option value="Bike">Bike</option>
        <option value="Van">Van</option>
    </select>
    <input type="file" name="vehicle_image" required>
    <button type="submit" name="upload_vehicle">Upload Vehicle</button>
</form>

<!-- VEHICLE GALLERY -->
<h2>Vehicle Gallery</h2>
<?php
$categories = ['SUV', 'Sedan', 'Bike', 'Van'];
foreach ($categories as $category) {
    echo "<h3>$category</h3>";
    echo "<div class='gallery-container'>";
    $result = $conn->query("SELECT * FROM vehicles WHERE category='$category'");
    while ($row = $result->fetch_assoc()) {
        echo "<div class='vehicle-card'>
                <img src='vehicle_uploads/{$row['image']}' alt='Vehicle'>
                <div class='details'>
                    <p>{$row['category']}</p>
                </div>
                <div class='actions' style='padding: 10px; text-align:center;'>
                    <a href='admin_dashboard.php?delete_vehicle={$row['id']}' onclick=\"return confirm('Delete this vehicle?')\">Delete</a>
                </div>
              </div>";
    }
    echo "</div>";
}
?>

</body>
</html>
