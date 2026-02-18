<?php
  session_start();
  require_once "db.php";
  $page = "login";
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_email'] = $user['email'];
      header("location: index.php");
      exit;
    } else {
      // login failed
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="styles/normalize.css">
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
  <main class="login">
    <form class="card" method="POST">
      <h1>Login</h1>
      <label for="email">Email</label>
      <input type="text" id="email" name="email">
      <label for="password">Password</label>
      <input type="password" id="password" name="password">
      <button>Login</button>
    </form>
  </main>
</body>
</html>