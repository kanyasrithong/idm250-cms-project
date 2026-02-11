<?php
  require_once "db.php";
  $page = "login";
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
    <form class="card">
      <h1>Login</h1>
      <label for="username">Username</label>
      <input type="text" id="username">
      <label for="password">Password</label>
      <input type="password" id="password">
      <button>Login</button>
    </form>
  </main>
</body>
</html>