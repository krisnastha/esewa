<?php
include 'database.php';

// Fetching data from the product table
$sql = "SELECT * FROM foods";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esewa Integration</title>
    <link rel="stylesheet" href="esewa.css">
</head>

<body>
   

    <!-- Main Content -->
    <main class="main-content">
        <h2 class="section-title">Our Products</h2>
        <section class="product-list">
            <?php
            if ($result->num_rows > 0) {
                // Output data for each row
                while ($row = $result->fetch_assoc()) {
                    echo '<article class="product-card">';
                    echo '<img alt="product-image" src="' . $row["image"] . '" class="product-image" />';
                    echo '<h2 class="product-title">' . ($row["title"]) . '</h2>';
                    echo '<p class="product-description">' .($row["description"]) . '</p>';
                    echo '<p class="product-price">Rs.' . ($row["price"]) . '</p>';
                    echo '<div class="product-buttons">';
                    echo "<form action='payment.php' method='POST'>";
                    echo "<input type='hidden' name='product_id' value='" . $row["id"] . "'>";
                    echo "<input type='submit' class='product-button buy-now' value='Buy'>";
                    echo "</form>";
                    echo '</div>';
                    echo '</article>';
                }
            } else {
                echo "<p>No products found.</p>";
            }
            ?>
        </section>
    </main>

</body>

</html>

<?php
$conn->close();
?>