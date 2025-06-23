<?php include 'db.php';

$selected_package = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $res = $conn->query("SELECT * FROM tour_packages WHERE id = $id");
    if ($res->num_rows > 0) {
        $selected_package = $res->fetch_assoc();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_type = $_POST['booking_type'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $vehicle_type = $_POST['vehicle_type'];
    $notes = $_POST['additional_notes'];
    $package_id = $booking_type === 'package' ? $_POST['package_id'] : "NULL";
    $pickup = $booking_type === 'custom' ? $_POST['pickup'] : null;
    $drop = $booking_type === 'custom' ? $_POST['drop'] : null;

    $stmt = $conn->prepare("INSERT INTO bookings (name, phone, email, booking_type, package_id, vehicle_type, pickup_location, drop_location, additional_notes)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissss", $name, $phone, $email, $booking_type, $package_id, $vehicle_type, $pickup, $drop, $notes);
    $stmt->execute();

    echo "<script>alert('Booking successful!'); window.location='homepage.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Booking</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 30px; }
        form { max-width: 600px; margin: auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        input, select, textarea { width: 100%; padding: 10px; margin-bottom: 15px; }
        button { background: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; }
        .hidden { display: none; }
    </style>
</head>
<body>

<h2>Book Your Vehicle</h2>
<form method="POST">
    <label>Type of Booking:</label>
    <select name="booking_type" onchange="toggleForm(this.value)" required>
        <option value="">-- Select --</option>
        <option value="package">As Per Package</option>
        <option value="custom">By Own Route</option>
    </select>

    <div id="common-fields">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="vehicle_type" placeholder="Vehicle Type (e.g., SUV, Sedan)" required>
        <textarea name="additional_notes" placeholder="Additional Notes"></textarea>
    </div>

    <div id="package-fields" class="hidden">
        <input type="hidden" name="package_id" value="<?= $selected_package ? $selected_package['id'] : '' ?>">
        <p><strong>Package:</strong> <?= $selected_package ? $selected_package['trip_name'] : 'No Package Selected' ?></p>
    </div>

    <div id="custom-fields" class="hidden">
        <input type="text" name="pickup" placeholder="Pickup Location">
        <input type="text" name="drop" placeholder="Drop Location">
    </div>

    <button type="submit">Submit Booking</button>
</form>

<script>
function toggleForm(type) {
    document.getElementById('package-fields').style.display = (type === 'package') ? 'block' : 'none';
    document.getElementById('custom-fields').style.display = (type === 'custom') ? 'block' : 'none';
}
</script>

</body>
</html>
