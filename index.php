<?php
// index.php - Homepage
 
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airbnb Clone - Home</title>
    <style>
        /* Amazing CSS: Modern, real-looking, responsive */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: #f8f8f8; color: #333; }
        header { background: linear-gradient(135deg, #ff385c, #e61e4d); color: white; padding: 40px 20px; text-align: center; }
        .search-bar { max-width: 800px; margin: 0 auto; background: white; border-radius: 8px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: flex; flex-wrap: wrap; gap: 10px; }
        .search-bar input { flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 4px; min-width: 200px; }
        .search-bar button { background: #ff385c; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; transition: background 0.3s; }
        .search-bar button:hover { background: #e61e4d; }
        .featured { padding: 40px 20px; }
        .listings { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; max-width: 1200px; margin: 0 auto; }
        .listing { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .listing:hover { transform: translateY(-5px); }
        .listing img { width: 100%; height: 200px; object-fit: cover; }
        .listing-info { padding: 15px; }
        .listing-info h3 { margin: 0 0 10px; font-size: 18px; }
        .listing-info p { margin: 5px 0; color: #666; }
        /* Filters */
        .filters { max-width: 800px; margin: 20px auto; display: flex; flex-wrap: wrap; gap: 10px; }
        .filters select, .filters input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        /* Responsive */
        @media (max-width: 768px) { .search-bar { flex-direction: column; } .filters { flex-direction: column; } }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Airbnb Clone</h1>
        <form id="searchForm" class="search-bar" method="GET" action="listings.php">
            <input type="text" name="location" placeholder="Destination" required>
            <input type="date" name="check_in" required>
            <input type="date" name="check_out" required>
            <button type="submit">Search</button>
        </form>
    </header>
    <section class="featured">
        <h2>Featured Listings</h2>
        <div class="listings">
            <?php
            $stmt = $pdo->query("SELECT * FROM properties LIMIT 3");
            while ($row = $stmt->fetch()) {
                $images = json_decode($row['images'], true);
                echo '<div class="listing">
                    <img src="' . $images[0] . '" alt="' . $row['title'] . '">
                    <div class="listing-info">
                        <h3>' . $row['title'] . '</h3>
                        <p>' . $row['location'] . ' - $' . $row['price_per_night'] . '/night</p>
                        <p>Rating: ' . $row['rating'] . ' (' . $row['review_count'] . ' reviews)</p>
                        <button onclick="window.location=\'property.php?id=' . $row['id'] . '\'">View</button>
                    </div>
                </div>';
            }
            ?>
        </div>
    </section>
    <!-- Filters on homepage for quick search, but main in listings -->
    <section class="filters">
        <h3>Quick Filters</h3>
        <!-- Can extend form, but for now, placeholder -->
    </section>
</body>
</html>
