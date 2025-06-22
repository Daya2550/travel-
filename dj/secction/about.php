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

$about = getSectionContent($conn, "about");
?>

<!DOCTYPE html>
<html>
<head>
    <title>About Us | Travel Site</title>
</head>
<body>
    <h1>About Our Company</h1>

    <h3>Company Bio</h3>
    <p><?= $about['company_bio']['info'] ?? '' ?></p>
    <?php if (!empty($about['company_bio']['image'])): ?>
        <img src="uploads/<?= $about['company_bio']['image'] ?>" width="400">
    <?php endif; ?>

    <h3>Our Journey</h3>
    <p><?= $about['timeline']['info'] ?? '' ?></p>
    <?php if (!empty($about['timeline']['image'])): ?>
        <img src="uploads/<?= $about['timeline']['image'] ?>" width="400">
    <?php endif; ?>

    <h3>Awards</h3>
    <p><?= $about['awards']['info'] ?? '' ?></p>
    <?php if (!empty($about['awards']['image'])): ?>
        <img src="uploads/<?= $about['awards']['image'] ?>" width="400">
    <?php endif; ?>

      <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="admin.php">Services</a></li>
</body>
</html>
