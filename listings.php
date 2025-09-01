<?php
// listings.php - Property listing page
 
include 'db.php';
 
$location = $_GET['location'] ?? '';
$check_in = $_GET['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? '';
$price_min = $_GET['price_min'] ?? 0;
$price_max = $_GET['price_max'] ?? 10000;
$type = $_GET['type'] ?? '';
$amenities = $_GET['amenities'] ?? '';
$sort = $_GET['sort'] ?? 'price_asc';
 
$where = "WHERE 1=1";
$params = [];
if ($location) { $where .= " AND location LIKE :location"; $params[':location'] = "%$location%"; }
if ($price_min) { $where .= " AND price_per_night >= :price_min"; $params[':price_min'] = $price_min; }
if ($price_max) { $where .= " AND price_per_night <= :price_max"; $params[':price_max'] = $price_max; }
if ($type) { $where .= " AND property_type = :type"; $params[':type'] = $type; }
if ($amenities) { $where .= " AND amenities LIKE :amenities"; $params[':amenities'] = "%$amenities%"; } // Simple JSON search
 
// Availability check (simple, no overlap check for minimal)
if ($check_in && $check_out) {
    // Pro: Check no overlapping bookings
    $where .= " AND id NOT IN (SELECT property_id FROM bookings WHERE (check_in <= :check_out AND check_out >= :check_in) AND status != 'Cancelled')";
    $params[':check_in'] = $check_in;
    $params[':check_out'] = $check_out;
}
 
$order = match($sort) {
    'price_asc' => 'price_per_night ASC',
    'price_desc' => 'price_per_night DESC',
    'rating_desc' => 'rating DESC',
    default => 'id DESC'
};
 
$sql = "SELECT * FROM properties $where ORDER BY $order";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <style>
        /* Reuse and extend CSS from index, amazing and responsive */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: #f8f8f8; color: #333; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        .filters { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
        .filters input, .filters select { padding: 10px; border: 1px solid #ddd; border-radius: 4px; flex: 1; min-width: 150px; }
        .filters button { background: #ff385c; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        .listings { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .listing { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .listing:hover { transform: translateY(-5px); }
        .listing img { width: 100%; height: 200px; object-fit: cover; }
        .listing-info { padding: 15px; }
        .listing-info h3 { margin: 0 0 10px; }
        .listing-info p { margin: 5px 0; }
        @media (max-width: 768px) { .filters { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>Available Properties</h1>
        <form class="filters" method="GET">
            <input type="hidden" name="location" value="<?php echo $location; ?>">
            <input type="hidden" name="check_in" value="<?php echo $check_in; ?>">
            <input type="hidden" name="check_out" value="<?php echo $check_out; ?>">
            <input type="number" name="price_min" placeholder="Min Price" value="<?php echo $price_min; ?>">
            <input type="number" name="price_max" placeholder="Max Price" value="<?php echo $price_max; ?>">
            <select name="type">
                <option value="">Any Type</option>
                <option value="Apartment" <?php if($type=='Apartment') echo 'selected'; ?>>Apartment</option>
                <option value="House" <?php if($type=='House') echo 'selected'; ?>>House</option>
                <option value="Villa" <?php if($type=='Villa') echo 'selected'; ?>>Villa</option>
                <option value="Cabin" <?php if($type=='Cabin') echo 'selected'; ?>>Cabin</option>
            </select>
            <input type="text" name="amenities" placeholder="Amenities (e.g., Pool)" value="<?php echo $amenities; ?>">
            <select name="sort">
                <option value="price_asc" <?php if($sort=='price_asc') echo 'selected'; ?>>Price Low to High</option>
                <option value="price_desc" <?php if($sort=='price_desc') echo 'selected'; ?>>Price High to Low</option>
                <option value="rating_desc" <?php if($sort=='rating_desc') echo 'selected'; ?>>Best Rated</option>
            </select>
            <button type="submit">Apply Filters</button>
        </form>
        <div class="listings">
            <?php
            while ($row = $stmt->fetch()) {
                $images = json_decode($row['images'], true);
                echo '<div class="listing">
                    <img src="' . $images[0] . '" alt="' . $row['title'] . '">
                    <div class="listing-info">
                        <h3>' . $row['title'] . '</h3>
                        <p>' . $row['location'] . ' - $' . $row['price_per_night'] . '/night</p>
                        <p>Rating: ' . $row['rating'] . ' (' . $row['review_count'] . ' reviews)</p>
                        <p>' . $row['description'] . '</p>
                        <button onclick="window.location=\'property.php?id=' . $row['id'] . '&check_in=' . $check_in . '&check_out=' . $check_out . '\'">View & Book</button>
                    </div>
                </div>';
            }
            if ($stmt->rowCount() == 0) echo '<p>No properties found.</p>';
            ?>
        </div>
    </div>
</body>
</html>
