<?php
  session_start();
  require_once "db.php";
  require_once "functions/auth.php";
  require "functions/wms.php";
  require_login();
  $page = "sku_management";

  // HANDLING FORM SUBMISSIONS
  $edit_sku = null;

  // CREATE
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_sku'])) {
    create_sku($_POST);
    header("Location: index.php");
    exit();
  }

  // UPDATE
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_sku'])) {
    update_sku($_POST['id'], $_POST);
    header("Location: index.php");
    exit();
  }

  // DELETE 
  if (isset($_GET['delete'])) {
    delete_sku($_GET['delete']);
    header("Location: index.php");
    exit();
  }

  // EDIT MODE
  if (isset($_GET['edit'])) {
    $edit_sku = get_sku($_GET['edit']);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SKU Management</title>
  <link rel="stylesheet" href="styles/normalize.css">
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
  <?php 
    include "components/header.php";
    include "components/nav.php";
    $skus = get_skus();
  ?>
  <main>
    <div id="title">
      <div class="title-left">
        <h2>SKU Management</h2>
        <a href="index.php?create=1" class="btn">+ CREATE SKU</a>
      </div>
      <h3><?= count($skus) ?> SKUs</h3>
    </div>

    <?php 
    if (isset($_GET['create']) || !empty($edit_sku)) {
    ?>
      <div class="form-container">
        <h3>
          <?php
          if ($edit_sku) {
            echo "Edit SKU";
          } else {
            echo "Create SKU";
          }
          ?>
        </h3>

        <form method="POST">
          <?php 
          if ($edit_sku) {
          ?>
            <input type="hidden" name="id" value="<?php echo $edit_sku['id']; ?>">
          <?php 
          }
          ?>

          <div class="form-section">
          <h4>General Information</h4>

            <div class="field">
              <label>Ficha</label>
              <input type="text" name="ficha"
              value="<?php
              if (isset($edit_sku['ficha'])) {
                echo $edit_sku['ficha'];
              } else {
                echo '';
              } 
              ?>" required>
            </div>

            <div class="field">
              <label>SKU</label>
              <input type="text" name="sku"
              value="<?php
              if (isset($edit_sku['sku'])) {
                echo $edit_sku['sku'];
              } else {
                echo '';
              } 
              ?>" required>
            </div>

            <div class="field">
              <label>Description</label>
              <input type="text" name="description"
              value="<?php
              if (isset($edit_sku['description'])) {
                echo $edit_sku['description'];
              } else {
                echo '';
              } 
              ?>" required>
            </div>

            <div class="field">
              <label>Unit of Measure</label>
              <select name="uom_primary" required>
                <option value="">Select</option>

                <option value="PALLET"
                  <?php 
                  if (isset($edit_sku) && isset($edit_sku['uom_primary']) && $edit_sku['uom_primary'] === 'PALLET') {
                    echo "selected";
                  } 
                  ?>>
                  PALLET
                </option>
                <option value="BUNDLE"
                  <?php 
                  if (isset($edit_sku) && isset($edit_sku['uom_primary']) && $edit_sku['uom_primary'] === 'BUNDLE') {
                    echo "selected";
                  } 
                  ?>>
                  BUNDLE
                </option>
              </select>
            </div>

            <div class="field">
              <label>Pieces</label>
              <input type="number" name="piece_count"
              value="<?php
              if (isset($edit_sku['piece_count'])) {
                echo $edit_sku['piece_count'];
              } else {
                echo '';
              } 
              ?>" required>
            </div>
          </div>

          <div class="form-section">
            <h4>Dimensions & Weight</h4>

            <div class="field">
              <label>Length (in)</label>
              <input type="number" name="length_inches"
              value="<?php
              if (isset($edit_sku['length_inches'])) {
                echo $edit_sku['length_inches'];
              } else {
                echo '';
              } 
              ?>" required>
            </div>

            <div class="field">
              <label>Width (in)</label>
              <input type="number" name="width_inches"
              value="<?php
              if (isset($edit_sku['width_inches'])) {
                echo $edit_sku['width_inches'];
              } else {
                echo '';
              } 
              ?>" required>
            </div>

            <div class="field">
              <label>Height (in)</label>
              <input type="number" name="height_inches"
              value="<?php
              if (isset($edit_sku['height_inches'])) {
                echo $edit_sku['height_inches'];
              } else {
                echo '';
              } 
              ?>" required>
            </div>

            <div class="field">
              <label>Weight (lbs)</label>
              <input type="number" name="weight_lbs"
              value="<?php
              if (isset($edit_sku['weight_lbs'])) {
                echo $edit_sku['weight_lbs'];
              } else {
                echo '';
              } 
              ?>" required>
            </div>
          </div>

          <button type="submit" name="<?php
            if ($edit_sku) {
              echo "update_sku";
            } else {
              echo "create_sku";
            }
          ?>">
            <?php
            if ($edit_sku) {
              echo "Update SKU";
            } else {
              echo "Create SKU";
            }
            ?>
          </button>

          <a href="index.php" class="btn-cancel">Cancel</a>
        </form>
      </div>
    <?php 
    }
    ?>

    <table>
      <tr>
        <th>SKU</th>
        <th>DESCRIPTION</th>
        <th>UNIT OF MEASURE</th>
        <th>PIECES</th>
        <th>DIMENSIONS</th>
        <th>WEIGHT</th>
        <th>ACTIONS</th>
      </tr>
      
      <?php foreach ($skus as $sku) : ?>
        <tr>
          <td><?= $sku['sku']; ?></td>
          <td><?= $sku['description']?></td>
          <td><?= $sku['uom_primary']?></td>
          <td><?= $sku['piece_count']?></td>
          <td><?= $sku['length_inches']?>in x <?= $sku['width_inches']?>in x <?= $sku['height_inches']?>in</td>
          <td><?= $sku['weight_lbs']?>lbs</td>
          <td>
            <a href="index.php?edit=<?= $sku['id'] ?>" class="btn-small">EDIT</a>
            <a href="index.php?delete=<?= $sku['id'] ?>"
            onclick="return confirm('Are you sure you want to delete this SKU?')"
            class="btn-small delete">DELETE</a>
          </td>
        </tr>
      <?php endforeach ?>

      <?php if (empty($skus)) : ?>
        <tr><td colspan="6">No SKUs found.</td></tr>
      <?php endif; ?>
    </table>
  </main>
</body>
</html>