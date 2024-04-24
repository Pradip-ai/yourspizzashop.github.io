<?php 
// Connect to the database
$conn = mysqli_connect('localhost', 'pradip', 'Test1234', 'your_pizza');

// Check the connection
if (!$conn) {
    echo 'Connection Error: '. mysqli_connect_error();
    exit(); // If there's a connection error, exit immediately
}

$errors = array('email' => '', 'title' => '', 'ingredients' => '', 'quantity' => ''); // Array to store validation errors
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
$title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
$ingredients = isset($_POST['ingredients']) ? htmlspecialchars($_POST['ingredients']) : '';
$quantity = isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '';

$formHasErrors = false; // Initialize $formHasErrors variable

if(isset($_POST['submit'])) {
    // Check form validation...
    
    if (empty($_POST['email'])) {
        $errors['email'] = 'An Email is required <br />';
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email must be a valid email address.';
        }
    }

    // Check Title
    if (empty($_POST['title'])) {
        $errors['title'] = 'A title is required <br />';
    } else {
        $title = $_POST['title'];
        if (!preg_match('/^[a-zA-Z\s]+$/', $title)) {
            $errors['title'] = 'Title must contain letters and spaces only.';
        }
    }

    // Check ingredients
    if (empty($_POST['ingredients'])) {
        $errors['ingredients'] = 'At least one ingredient is required <br />';
    } else {
        $ingredients = $_POST['ingredients'];
        if (!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)) {
            $errors['ingredients'] = 'Ingredients must contain comma separated values.';
        }
    }

    // Check quantity of pizzas
    if (empty($_POST['quantity']) || !is_numeric($_POST['quantity']) || $_POST['quantity'] < 1) {
        $errors['quantity'] = 'Please select a valid quantity of pizzas <br />';
    }

    // Display errors
    foreach ($errors as $error) {
        if (!empty($error)) {
            $formHasErrors = true;
            break;
        }
    }

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
            // Set success message
            $success_message = 'Pizza added successfully!';
        } else {
            echo 'Query Error: ' . mysqli_error($conn);
            exit(); // Make sure to exit after printing the error
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Pizza Shop</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style type="text/css">
        /* Custom brand colors */
        .brand {
            background: #66CCFF !important;
        }

        .brand-text {
            color: #66CCFF !important;
            font-family: 'Roboto', sans-serif;
        }

        /* Form styling */
        form {
            max-width: 460px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            font-family: 'Roboto', sans-serif;
        }

        /* Button styling */
        .btn.brand {
            background-color: #66CCFF !important;
            border-radius: 20px !important;
            font-weight: bold;
        }
    </style>
</head>
<body class="grey lighten-4">
    <div class="container colorful-container">
        <a href="add_pizza.php" class="btn brand z-depth-0 right">Add a Pizza</a>
    </div>

    <nav class="white z-depth-0">
        <div class="container">
            <a href="#" class="brand-logo brand-text">Your Pizza Shop</a>
        </div>
    </nav>

    <section class="container">
        <h4 class="center teal-text text-darken-3">Add a Pizza</h4>

        <form class="white" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email ?>">
            <div class="red-text"><?php echo $errors['email']; ?></div>
            
            <label for="title">Pizza Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $title ?>">
            <div class="red-text"><?php echo $errors['title']; ?></div>
            
            <label for="ingredients">Ingredients (comma separated):</label>
            <input type="text" id="ingredients" name="ingredients" value="<?php echo $ingredients ?>">
            <div class="red-text"><?php echo $errors['ingredients']; ?></div>
            
            <label for="quantity">Number of Pizzas:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="<?php echo $quantity ?>">
            <div class="red-text"><?php echo $errors['quantity']; ?></div>

            <div class="center">
                <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
            </div>
            
            <!-- Display success message -->
            <?php if (isset($success_message)): ?>
                <div style="color: #0099FF; font-size: larger;"><?php echo $success_message; ?></div>
            <?php endif; ?>
        </form>
    </section>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare

