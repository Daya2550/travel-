<?php
$conn = new mysqli("host", "user", "pass", "database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getSectionContent($conn, $section) {
    $data = [];
    $stmt = $conn->prepare("SELECT key_name, image, info FROM site_content WHERE section = ?");
    $stmt->bind_param("s", $section);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[$row['key_name']] = [
            'image' => $row['image'],
            'info' => $row['info']
        ];
    }
    return $data;
}

$home = getSectionContent($conn, "home");
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $home['hero']['info'] ?? 'Welcome to Travel Site' ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        img { max-width: 100%; height: auto; }
        nav a { margin-right: 15px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="admin.php">Admin Panel</a>
</nav>

<h1><?= $home['hero']['info'] ?? '' ?></h1>

<?php
$heroImage = $home['hero']['image'] ?? '';
$imgPath = "uploads/" . $heroImage;
if (!empty($heroImage) && file_exists($imgPath)) {
    echo "<img src='$imgPath' alt='Hero Image'>";
} else {
    echo "<p style='color:red;'>Image not found: $imgPath</p>";
}
?>

<h2>Welcome</h2>
<p><?= $home['welcome']['info'] ?? '' ?></p>

<h2>Featured Services</h2>
<p><?= $home['features']['info'] ?? '' ?></p>

<h2>Call to Action</h2>
<p><?= $home['cta']['info'] ?? '' ?></p>

</body>
</html>
