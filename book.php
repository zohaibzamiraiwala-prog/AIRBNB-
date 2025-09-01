<?php
// book.php - Booking form
 
include 'db.php';
 
$id = $_GET['id'] ?? 0;
$check_in = $_GET['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? '';
 
$stmt = $pdo->prepare("SELECT * FROM properties WHERE id = :id");
$stmt->execute([':id' => $id]);
$property = $stmt->fetch();
 
if (!$property) die('Property not found.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo $property['title']; ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: #f8f8f8; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        form { display: flex; flex-direction: column; gap: 15px; }
        input { padding: 12px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #ff385c; color: white; border: none; padding: 15px; border-radius: 4px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #e61e4d; }
        @media (max-width: 768px) { .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Your Stay</h1>
        <form method="POST" action="confirm_booking.php">
            <input type="hidden" name="property_id" value="<?php echo $id; ?>">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="tel" name="phone" placeholder="Your Phone" required>
            <input type="date" name="check_in" value="<?php echo $check_in; ?>" required>
            <input type="date" name="check_out" value="<?php echo $check_out; ?>" required>
            <button type="submit">Confirm Booking</button>
        </form>
    </div>
</body>
</html>
