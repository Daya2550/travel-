<?php include 'db.php'; ?>

<?php
$imageUploaded = "";

// Save or Update
if (isset($_POST['save'])) {
    $section = $_POST['section'];
    $key = $_POST['key_name'];
    $info = $_POST['info'];

    // Image upload
    $imageName = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $imageName)) {
            $imageUploaded = "<p style='color:blue;'>Image uploaded: $imageName</p>";
        } else {
            $imageUploaded = "<p style='color:red;'>Image upload failed.</p>";
        }
    }

    // Check if content exists
    $stmt = $conn->prepare("SELECT id FROM site_content WHERE section=? AND key_name=?");
    $stmt->bind_param("ss", $section, $key);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        if ($imageName != "") {
            $stmt = $conn->prepare("UPDATE site_content SET info=?, image=? WHERE section=? AND key_name=?");
            $stmt->bind_param("ssss", $info, $imageName, $section, $key);
        } else {
            $stmt = $conn->prepare("UPDATE site_content SET info=? WHERE section=? AND key_name=?");
            $stmt->bind_param("sss", $info, $section, $key);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO site_content (section, key_name, image, info) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $section, $key, $imageName, $info);
    }

    $stmt->execute();
    echo "<p style='color:green;'>Saved successfully.</p>";
    echo $imageUploaded;
    echo "<meta http-equiv='refresh' content='1'>";
}

// Delete
if (isset($_POST['delete'])) {
    $id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM site_content WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<p style='color:red;'>Deleted successfully.</p>";
    echo "<meta http-equiv='refresh' content='1'>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 8px 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #aaa; }
        th, td { padding: 10px; text-align: left; }
        img { max-width: 100px; height: auto; }
        nav a { margin-right: 15px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About Us</a>
    <a href="admin.php">Admin Panel</a>
</nav>

<h2>Admin Panel: Section, Key, Image, Info</h2>

<form method="POST" enctype="multipart/form-data">
    <label>Section:</label>
    <input type="text" name="section" placeholder="e.g., home, about" required><br>

    <label>Key Name:</label>
    <input type="text" name="key_name" placeholder="e.g., hero, company_bio" required><br>

    <label>Info / Description:</label>
    <textarea name="info" placeholder="Content or description" rows="4" required></textarea><br>

    <label>Upload Image:</label>
    <input type="file" name="image" accept="image/*"><br><br>

    <button name="save">Save / Update</button>
</form>

<?= $imageUploaded ?>

<hr>
<h3>All Entries</h3>
<table>
<tr>
    <th>ID</th>
    <th>Section</th>
    <th>Key</th>
    <th>Image</th>
    <th>Info</th>
    <th>Action</th>
</tr>
<?php
$result = $conn->query("SELECT * FROM site_content ORDER BY section, key_name");
while ($row = $result->fetch_assoc()) {
    $imgPath = "uploads/" . $row['image'];
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['section']}</td>
        <td>{$row['key_name']}</td>
        <td>";
            if (!empty($row['image']) && file_exists($imgPath)) {
                echo "<img src='$imgPath'>";
            } else {
                echo "<span style='color:red;'>No image</span>";
            }
        echo "</td>
        <td>{$row['info']}</td>
        <td>
            <form method='POST' style='display:inline'>
                <input type='hidden' name='delete_id' value='{$row['id']}'>
                <button name='delete' onclick=\"return confirm('Delete this?')\">Delete</button>
            </form>
        </td>
    </tr>";
}
?>
</table>

</body>
</html>
