<?php 
// Include header
include('templates/header.php');

// Include database connection
include('config/db_connect.php');

$errors = array('email' => '', 'title' => '', 'ingredients' => '', 'quantity' => ''); // Array to store validation errors
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
$title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
$ingredients = isset($_POST['ingredients']) ? htmlspecialchars($_POST['ingredients']) : '';
$quantity = isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '';

$formHasErrors = false; // Initialize $formHasErrors variable

if(isset($_POST['submit'])) {
    // Validate form fields...

    if (!$formHasErrors) {
        // Proceed with form submission
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
        $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

        // Create SQL
        $sql = "INSERT INTO pizzas(title, email, ingredients, quantity) VALUES ('$title', '$email', '$ingredients', '$quantity')";

        // Save to DB and check
        if (mysqli_query($conn, $sql)) {
            // Redirect to prevent form resubmission
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo 'Query Error: ' . mysqli_error($conn);
            exit();
        }
    }
}

// Fetch pizzas from the database
$sql = 'SELECT title, ingredients, id FROM pizzas ORDER BY created_at';
$result = mysqli_query($conn, $sql);
$pizzas = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Free the result memory
mysqli_free_result($result);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pizzas</title>
    <!-- Add your CSS link or internal styles here -->
    <link rel="stylesheet" href="path_to_your_css_file.css">
</head>
<body>
<h4 class="center blue-text text-darken-2">Pizzas!</h4>

<div class="container" style="background-color: #ADD8E6;">
    <div class="container" style="padding: 20px;">
        <div class="container" style="border: 1px solid #ccc;">
            


        <div class="row">
            <?php foreach($pizzas as $pizza): ?>
                <div class="col s6 md3">
                    <div class="card z-depth-0">
                        <div class="card-content center">
                            <h6><?php echo htmlspecialchars($pizza['title']); ?></h6>
                            <ul>
                                <?php 
                                // Split ingredients and display only the first two
                                $ingredients = explode(',', $pizza['ingredients']);
                                for ($i = 0; $i < min(2, count($ingredients)); $i++): ?>
                                    <li><?php echo htmlspecialchars($ingredients[$i]); ?></li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                        <div class="card-action right-align">
                            <a class="brand-text" href="details.php?id=<?php echo $pizza['id']; ?>">More Info</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (count($pizzas) >= 3): ?>
                <p>There are 3 or more Pizzas</p>
            <?php else: ?>
                <p>There are less than 3 Pizzas</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- No include statement for add.php content -->

<?php include('templates/footer.php'); ?>
</body>
</html>
