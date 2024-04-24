<?php 
include('config/db_connect.php');

if(isset($_POST['delete'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);

    $sql = "DELETE FROM pizzas WHERE id = $id_to_delete";

    if (mysqli_query($conn, $sql)) {
        // Success
        header('Location: index.php');
    } else {
        // Failure
        echo 'Query error: ' . mysqli_error($conn);
    }
}

$pizza = null; // Initialize $pizza variable

// Check if the 'id' parameter is set in the URL
if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Construct the SQL query
    $sql = "SELECT * FROM pizzas WHERE id = $id";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Fetch the result as an associative array
    $pizza = mysqli_fetch_assoc($result);

    // Free the result memory
    mysqli_free_result($result);

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Details</title>
    <style>
        body {
            background-color: #fafafa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h4 {
            color: #d33;
            margin-bottom: 10px;
            border-bottom: 2px solid #d33;
            padding-bottom: 5px;
        }
        .details {
            margin-bottom: 20px;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #333;
        }
        .delete-btn {
            background-color: #d33;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .delete-btn:hover {
            background-color: #b30000;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if($pizza): ?>
            <h4><?php echo htmlspecialchars($pizza['title']); ?></h4>
            <div class="details">
                <p class="detail-label">Created by:</p>
                <p class="detail-value"><?php echo htmlspecialchars($pizza['email']); ?></p>
            </div>
            <div class="details">
                <p class="detail-label">Created at:</p>
                <p class="detail-value"><?php echo date('F j, Y, g:i a', strtotime($pizza['created_at'])); ?></p>
            </div>
            <div class="details">
                <h5>Ingredients:</h5>
                <p><?php echo htmlspecialchars($pizza['ingredients']); ?></p>
            </div>
            <div class="details">
                <h5>Quantity:</h5>
                <p><?php echo htmlspecialchars($pizza['quantity']); ?></p>
            </div>

            <!-- Delete button -->
            <form action="details.php" method="POST">
                <input type="hidden" name="id_to_delete" value="<?php echo $pizza['id']; ?>">
                <input type="submit" name="delete" value="Delete" class="delete-btn">
            </form>
        <?php else: ?>
            <h4>No pizza found with the provided ID!</h4>
        <?php endif; ?>
    </div>
</body>
</html>
