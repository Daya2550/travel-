<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>All Bookings</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f2f2f2; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #007BFF; color: white; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>All Bookings</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Type</th>
        <th>Vehicle</th>
        <th>Pickup</th>
        <th>Drop</th>
        <th>Notes</th>
        <th>Date</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM bookings ORDER BY booking_date DESC");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['email']}</td>
            <td>{$row['booking_type']}</td>
            <td>{$row['vehicle_type']}</td>
            <td>{$row['pickup_location']}</td>
            <td>{$row['drop_location']}</td>
            <td>{$row['additional_notes']}</td>
            <td>{$row['booking_date']}</td>
        </tr>";
    }
    ?>
</table>

</body>
</html>
