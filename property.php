<?php
// property.php - Single property view
 
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
    <title><?php echo $property['title']; ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: #f8f8f8; color: #333; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .images { display: flex; flex-wrap: wrap; gap: 10px; }
        .images img { width: 100%; max-width: 400px; height: auto; border-radius: 8px; }
        .details { margin-top: 20px; }
        .details h1 { font-size: 28px; }
        .details p { font-size: 16px; line-height: 1.5; }
        .book-btn { background: #ff385c; color: white; border: none; padding: 15px 30px; border-radius: 4px; cursor: pointer; font-size: 18px; margin-top: 20px; transition: background 0.3s; }
        .book-btn:hover { background: #e61e4d; }
        @media (max-width: 768px) { .images { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $property['title']; ?></h1>
        <div class="images">
            <?php
            $images = json_decode($property['images'], true);
            foreach ($images as $img) {
                echo '<img src="' . $img . '" alt="Property Image">';
            }
            ?>
        </div>
        <div class="details">
            <p><strong>Location:</strong> <?php echo $property['location']; ?></p>
            <p><strong>Price:</strong> $<?php echo $property['price_per_night']; ?>/night</p>
            <p><strong>Type:</strong> <?php echo $property['property_type']; ?></p>
            <p><strong>Amenities:</strong> <?php echo implode(', ', json_decode($property['amenities'], true)); ?></p>
            <p><strong>Description:</strong> <?php echo $property['description']; ?></p>
            <p><strong>Rating:</strong> <?php echo $property['rating']; ?> (<?php echo $property['review_count']; ?> reviews)</p>
            <button class="book-btn" onclick="window.location='book.php?id=<?php echo $id; ?>&check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out; ?>'">Book Now</button>
        </div>
    </div>
</body>
</html>
