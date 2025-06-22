<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Homepage – Tour Packages & Vehicle Gallery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }

        /* Common Card Styles */
        .card-container, .gallery-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .card, .vehicle-card {
            border: 1px solid #ccc;
            border-radius: 12px;
            width: 300px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card img, .vehicle-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .card-text {
            margin: 6px 0;
            color: #555;
        }

        .card-actions {
            padding: 15px;
            text-align: center;
        }

        .card-actions a {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .card-actions a:hover {
            background: #218838;
        }

        .vehicle-card p {
            margin: 0;
            padding: 10px;
            text-align: center;
            background: #f4f4f4;
            font-weight: bold;
            color: #555;
        }

        hr {
            margin: 50px 0;
        }
    </style>
</head>
<body>

<!-- Tour Packages Section -->
<h1>Explore Our Tour Packages</h1>

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
            <div class='card-text'><strong>Description:</strong> {$row['description']}</div>
        </div>
        <div class='card-actions'>
            <a href='book.php?id={$row['id']}'>Book</a>
        </div>
    </div>";
}
?>
</div>

<!-- Separator -->
<hr>

<!-- Vehicle Gallery Section -->
<h1>Our Vehicle Gallery</h1>

<?php
$categories = ['SUV', 'Sedan', 'Bike', 'Van'];

foreach ($categories as $category) {
    echo "<h2>$category</h2>";
    echo "<div class='gallery-container'>";

    $result = $conn->query("SELECT * FROM vehicles WHERE category='$category'");
    while ($row = $result->fetch_assoc()) {
        echo "<div class='vehicle-card'>
                <img src='vehicle_uploads/{$row['image']}' alt='{$category}'>
                <p>{$row['category']}</p>
              </div>";
    }

    echo "</div>";
}
?>

</body>
</html>
