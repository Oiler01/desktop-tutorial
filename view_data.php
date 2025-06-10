<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scrap_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ลบข้อมูลถ้ามีการส่ง ID มาทาง POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM contacts WHERE id = $delete_id");
}

// ดึงคำค้นหาจาก GET
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT id, name, phone, items, created_at FROM contacts 
        WHERE name LIKE '%$search%' OR phone LIKE '%$search%' OR items LIKE '%$search%'
        ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการข้อมูลผู้ติดต่อ</title>
  <style>
    body { font-family: Arial, sans-serif; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .search-box { margin-top: 20px; }
  </style>
</head>
<body>
  <h2>จัดการข้อมูลผู้ติดต่อ</h2>

  <form method="get" class="search-box">
    <input type="text" name="search" placeholder="ค้นหาด้วยชื่อ เบอร์ หรือรายการ" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">ค้นหา</button>
  </form>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ชื่อ</th>
          <th>เบอร์โทร</th>
          <th>รายการของ</th>
          <th>เวลาที่ติดต่อ</th>
          <th>ลบ</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($row['items'])); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
              <form method="post" onsubmit="return confirm('ยืนยันการลบข้อมูลนี้?');">
                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                <button type="submit">ลบ</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>ไม่มีข้อมูลที่ตรงกับคำค้นหา</p>
  <?php endif; ?>

  <?php $conn->close(); ?>
</body>
</html>
