<?php
include 'db.php'; // Or directly use your existing connection block

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | Travel Site</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        img { max-width: 100%; height: auto; margin-top: 10px; }
        nav ul { list-style: none; padding: 0; display: flex; gap: 15px; }
        nav li { display: inline; }
        nav a { text-decoration: none; font-weight: bold; color: #333; }
        nav a:hover { text-decoration: underline; }
        h1, h3 { color: #1a73e8; }
    </style>
</head>
<body>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="admin.php">Admin Panel</a></li>
    </ul>
</nav>

<h1>About Our Company</h1>

<h3>Company Bio</h3>
<p><?= htmlspecialchars($about['company_bio']['info'] ?? '') ?></p>
<?php if (!empty($about['company_bio']['image']) && file_exists('uploads/' . $about['company_bio']['image'])): ?>
    <img src="uploads/<?= htmlspecialchars($about['company_bio']['image']) ?>" alt="Company Bio">
<?php endif; ?>

<h3>Our Journey</h3>
<p><?= htmlspecialchars($about['timeline']['info'] ?? '') ?></p>
<?php if (!empty($about['timeline']['image']) && file_exists('uploads/' . $about['timeline']['image'])): ?>
    <img src="uploads/<?= htmlspecialchars($about['timeline']['image']) ?>" alt="Timeline">
<?php endif; ?>

<h3>Awards</h3>
<p><?= htmlspecialchars($about['awards']['info'] ?? '') ?></p>
<?php if (!empty($about['awards']['image']) && file_exists('uploads/' . $about['awards']['image'])): ?>
    <img src="uploads/<?= htmlspecialchars($about['awards']['image']) ?>" alt="Awards">
<?php endif; ?>

</body>
</html>
