<?php include "db.php";
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM reviews WHERE id=" . $_GET['delete']);
    header("Location: admin.php");
}
$result = $conn->query("SELECT * FROM reviews ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Admin Panel</title></head>
<body>
<a href="index.php">⬅ Back to Home</a>
<h2>Admin – Manage Feedback</h2>
<table border="1" cellpadding="10">
    <tr><th>Name</th><th>Email</th><th>Trip</th><th>Rating</th><th>Date</th><th>Actions</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['trip_id'] ?> - <?= $row['destination'] ?></td>
        <td><?= $row['rating'] ?>/5</td>
        <td><?= $row['date'] ?></td>
        <td>
            <a href="update_review.php?id=<?= $row['id'] ?>">Edit</a> |
            <a href="admin.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this review?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>