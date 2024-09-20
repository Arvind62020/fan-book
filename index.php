<?php

$host = 'localhost'; // or your host
$db = 'your_database_name';
$user = 'your_username';
$pass = 'your_password';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $image = $conn->real_escape_string($_POST['image']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float) $_POST['price']; // Ensure price is a float

    // Insert data into the database
    $sql = "INSERT INTO resorts (image, title, description, price) VALUES ('$image', '$title', '$description', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Layout</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <div class="logo">MyLogo</div>
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="pincode" placeholder="Enter PIN Code" class="pincode" required>
                <input type="submit" value="Search">
            </form>
        
            <div class="results">
                <?php
                if (isset($_GET['pincode'])) {
                    // Sanitize input
                    $pincode = intval($_GET['pincode']); 
        
                    // Database connection
                    $conn = new mysqli("localhost", "username", "password", "database");
        
                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
        
                    // Prepare and execute the SQL statement
                    $stmt = $conn->prepare("SELECT area, state FROM pincodes WHERE pincode = ?");
                    $stmt->bind_param("i", $pincode);
                    $stmt->execute();
                    $result = $stmt->get_result();
        
                    // Check if results were found
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<p>Pincode: ' . htmlspecialchars($pincode) . '<br>';
                            echo 'Area: ' . htmlspecialchars($row['area']) . '<br>';
                            echo 'State: ' . htmlspecialchars($row['state']) . '</p>';
                        }
                    } else {
                        echo "<p>No results found for this PIN code.</p>";
                    }
        
                    $stmt->close();
                    $conn->close();
                }
                ?>
            </div>
            <input type="text" placeholder="Search products..." class="searchbox" id="searchInput">

    <div class="results" id="resultsContainer"></div>

    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                let query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: 'search_categories.php',
                        type: 'GET',
                        data: { q: query },
                        success: function(data) {
                            $('#resultsContainer').html(data);
                        }
                    });
                } else {
                    $('#resultsContainer').empty();
                }
            });
        });
    </script>
    <?php
    if (isset($_GET['q'])) {
        $query = $conn->real_escape_string($_GET['q']);
        
        // Prepare and execute the SQL statement
        $sql = "SELECT category_name FROM categories WHERE category_name LIKE '%$query%'";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div>' . htmlspecialchars($row['category_name']) . '</div>';
            }
        } else {
            echo '<div>No categories found.</div>';
        }
    }
    
    $conn->close();
    ?>
        </div>
        <div class="nav-links">
            <a href="#">Category</a>
            <a href="#">Login</a>
            <a href="#">Signup</a>
            <a href="#">Cart</a>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="#">Menu</a></li>
            <li><a href="#">Recipe</a></li>
            <li><a href="#">Brand Ambassador</a></li>
            <li><a href="#">Student Ambassador</a></li>
            <li><a href="#">Gift Card</a></li>
            <li><a href="#">Quick Pass</a></li>
            <li><a href="#">Refer a Friend</a></li>
            <li><a href="#">Our Online Service</a></li>
        </ul>
    </nav>
    <header></header>
        <h1>Explore Puja by Category</h1>
    </header>
    <section class="categories">
        <div class="category">Love and Relationship</div>
        <div class="category">Good Health</div>
        <div class="category">Child Education</div>
        <div class="category">Legal</div>
        <div class="category">Money and Career</div>
        <div class="category">House and Property</div>
        <div class="category">Black Magic and Evil Eye</div>
        <div class="category">Mental Stress and Depression</div>
        <div class="category">Peace and Prosperity</div>
        <div class="category">Nakshatra and Grah Shanti</div>
        <div class="category">Festive</div>
    </section>
    
    <?php
// Connect to the database (as before)
// ...

// Get the ID from the URL (for example, resort.php?id=1)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the input

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT image, title, description, price FROM resorts WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" means the parameter is an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data for the fetched row
        $row = $result->fetch_assoc();
        echo '<div class="card">';
        echo '    <div class="card-image">';
        echo '        <img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '">';
        echo '    </div>';
        echo '    <div class="card-content">';
        echo '        <h3>' . htmlspecialchars($row['title']) . '</h3>';
        echo '        <p>' . htmlspecialchars($row['description']) . '</p>';
        echo '        <div class="price">$' . htmlspecialchars($row['price']) . '/night</div>';
        echo '        <a href="#" class="book-now">Book Now</a>';
        echo '    </div>';
        echo '</div>';
    } else {
        echo "No results found.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>

    <footer>
        <div class="footer-sections">
            <div class="company-info">
                <h3>Our Company</h3>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">FQA</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Seller</a></li>
                    <li><a href="#">Press Release</a></li>
                </ul>
            </div>

            <div class="social-media">
                <h3>Find Us On</h3>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">LinkedIn</a></li>
                    <li><a href="#">YouTube</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Pinterest</a></li>
                </ul>
            </div>

            <div class="support">
                <h3>Get in Touch</h3>
                <p>Phone Support: +1 (224) 366-0987</p>
                <p>General Enquiry: <a href="mailto:hello@enquiry.com">hello@enquiry.com</a></p>
                <p>Order Support: <a href="#">Order Support</a></p>
                <p>Stories Support: <a href="#">Stories Support</a></p>
            </div>

            <div class="download">
                <h3>Download Our App</h3>
                <ul>
                    <li><a href="#">Download iOS App</a></li>
                    <li><a href="#">Download Android App</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2024 Company Name. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
