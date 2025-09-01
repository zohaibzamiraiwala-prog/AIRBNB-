<?php
// confirm_booking.php - Process booking
 
include 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $property_id = $_POST['property_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
 
    // Check availability (pro check)
    $avail = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE property_id = :id AND (check_in <= :check_out AND check_out >= :check_in) AND status != 'Cancelled'");
    $avail->execute([':id' => $property_id, ':check_in' => $check_in, ':check_out' => $check_out]);
    if ($avail->fetchColumn() > 0) {
        echo "Property not available for these dates.";
        exit;
    }
 
    // Get price
    $stmt = $pdo->prepare("SELECT price_per_night FROM properties WHERE id = :id");
    $stmt->execute([':id' => $property_id]);
    $price = $stmt->fetchColumn();
    $days = (strtotime($check_out) - strtotime($check_in)) / (60*60*24);
    $total = $price * $days;
 
    // Create or get user
    $userStmt = $pdo->prepare("INSERT IGNORE INTO users (name, email, phone) VALUES (:name, :email, :phone)");
    $userStmt->execute([':name' => $name, ':email' => $email, ':phone' => $phone]);
    $user_id = $pdo->lastInsertId() ?: $pdo->query("SELECT id FROM users WHERE email='$email'")->fetchColumn();
 
    // Book
    $bookStmt = $pdo->prepare("INSERT INTO bookings (property_id, user_id, check_in, check_out, total_price, status) VALUES (:pid, :uid, :cin, :cout, :total, 'Confirmed')");
    $bookStmt->execute([':pid' => $property_id, ':uid' => $user_id, ':cin' => $check_in, ':cout' => $check_out, ':total' => $total]);
 
    // Confirmation
    echo "<h1>Booking Confirmed!</h1><p>Thank you, $name. Your booking for dates $check_in to $check_out is confirmed. Total: $$total.</p>";
    echo "<script>setTimeout(() => { window.location='index.php'; }, 5000);</script>"; // JS redirect
} else {
    header('Location: index.php');
}
?>
