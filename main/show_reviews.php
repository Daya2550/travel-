<?php include "db.php"; ?>
<?php
$result = $conn->query("SELECT * FROM reviews ORDER BY id DESC");
$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feedback Slideshow</title>
    <style>
        .slide { display: none; text-align: center; margin-bottom: 30px; }
        img { max-width: 300px; height: auto; }
    </style>
</head>
<body>
<a href="index.php">⬅ Back to Home</a>
<h2>User Reviews</h2>
<?php foreach ($reviews as $r): ?>
    <div class="slide">
        <h3><?= htmlspecialchars($r['name']) ?> (<?= $r['email'] ?>)</h3>
        <p><strong>Trip:</strong> <?= $r['trip_id'] ?> - <?= $r['destination'] ?> (<?= $r['trip_date'] ?>)</p>
        <p><strong>Rating:</strong> <?= $r['rating'] ?>/5</p>
        <p><?= htmlspecialchars($r['info']) ?></p>
        <?php if ($r['image']) echo "<img src='{$r['image']}'><br>"; ?>
        <p><strong>Feedback Date:</strong> <?= $r['date'] ?></p>
    </div>
<?php endforeach; ?>
<script>
let index = 0;
const slides = document.querySelectorAll(".slide");
function showSlides() {
    slides.forEach(s => s.style.display = "none");
    index = (index + 1) % slides.length;
    slides[index].style.display = "block";
    setTimeout(showSlides, 3000);
}
showSlides();
</script>
</body>
</html>