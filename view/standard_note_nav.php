<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Navigation Bar</title>
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Link to Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<style>
  .navbar-custom {
    padding: 1px;
  }
  .navbar-custom .bi {
    color: white; /* White icons */
    font-size: 1.2rem; /* Icon size */
  }
  /* Add custom spacing if needed */
  .nav-link:not(:last-child) {
    margin-right: 1rem; /* Spacing between buttons */
  }
</style>
</head>
<body>

<nav class="navbar navbar-expand navbar-custom">
  <div class="container-fluid">
    <!-- Left aligned return button -->
    <a class="navbar-brand" href="<?php echo $web_root; ?>notes/">
      <i class="bi bi-arrow-left"></i> <!-- Left-pointing arrow -->
    </a>

    <!-- Right aligned icon buttons -->
    <div class="navbar-nav">
      <a class="nav-link" href="#">
        <i class="bi bi-share"></i> <!-- Share/connect icon -->
      </a>
      <a class="nav-link" href="<?php echo $web_root; ?>Notes/pin_or_unpin_note/<?php echo $note->id; ?>">
                <?php if ($note->pinned) { ?>
                    <i class="bi bi-pin-fill"></i> <!-- Filled pin icon -->
                <?php } else { ?>
                    <i class="bi bi-pin"></i> <!-- Pin or thumbtack -->
                <?php } ?>
            </a>
      <a class="nav-link" href="#">
        <i class="bi bi-download"></i> <!-- Download icon -->
      </a>
      <a class="nav-link" href="#">
        <i class="bi bi-pencil"></i> <!-- Pencil for editing -->
      </a>
    </div>
  </div>
</nav>


</body>
</html>
<div class="container mt-3">

<?php include('utils/util_dates.php'); ?>
<div class="container py-2">
    <!-- Main Form for Title -->
    <form>
        <!-- Title Field -->
        <div class="mb-3">
            <label for="titleInput" class="form-label">Title</label>
            <input type="text" class="form-control" id="titleInput" 
                   placeholder="Enter title" value="<?= htmlspecialchars($note->title) ?>" readonly>
        </div>
    </form> <!-- End of Main Form -->