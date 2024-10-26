<button?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body { background:linear-gradient(#000000,#000000,#000000) }
        .login-container { max-width: 400px; margin: 50px auto; position: relative;}
        .login-form { padding: 20px; border-radius: 5px; background-color: #13015d; color: white;}
        #spacer{
            position: absolute;
            top: 366px; /* Adjust as needed to align visually with the container */
            left: 270px;
            width: 100px;
            height: 40px;
        }
    </style>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
        <!-- Display Success or Error messages -->
        <?php if (isset($_SESSION['signup_success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['signup_success']; 
                unset($_SESSION['signup_success']); 
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['signup_error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['signup_error']; 
                unset($_SESSION['signup_error']); 
                ?>
            </div>
        <?php endif; ?>

        <!-- Sign-up form -->
       <div class="login-container">
        <div class="login-form">
            <h2 class="text-center">Create Account</h2>
            <div class="container mt-5">
                <form action="signup.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-success">Sign Up</button>
                </form>
            </div>
            <button onclick="window.location.href='login.html'" id="spacer" class="btn btn-primary">Sign in</button>
        </div>
    </div>
    </div>
</body>
</html>
