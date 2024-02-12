<?php
// Assuming you have a database connection established

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password using password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Save $email and $hashedPassword in the database
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);
    
    if ($stmt->execute()) {
        // Registration successful
        echo "Registado com sucesso!";
    } else {
        // Registration failed
        echo "Registo nao efetuado! - Verifique os campos";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resgitar</title>
</head>
<body>
    <h2>Registar</h2>
    <form method="post" action="signup.php">
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <input type="submit" value="Signup">
    </form>
</body>
</html>
