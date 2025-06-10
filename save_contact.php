<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scrap_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if (isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['items'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $items = $_POST['items'];

    $sql = "INSERT INTO contacts (name, phone, items) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $phone, $items);

    if ($stmt->execute()) {
        echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึก: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ข้อมูลไม่ครบถ้วน";
}

$conn->close();
?>
