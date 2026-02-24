<header>
  <h1>MKM WAREHOUSE MS</h1>
  <div id="actions">
    <span class="user-email"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></span>
    <a class="logout" href="logout.php">LOGOUT</a>
  </div>
</header>

<!-- <header>
  <h1>MKM WAREHOUSE MS</h1>
  <div id="actions">
    <h1><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></h1>
    <a href="logout.php">LOGOUT</a>
  </div>
</header> -->