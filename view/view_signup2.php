<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="card my-5">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Sign Up</h2>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php foreach ($errors as $error): ?>
                                    <p><?php echo htmlspecialchars($error); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form action="index.php?controller=main&action=signup" method="post">
                            <div class="mb-3">
                                <label for="mail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="mail" name="mail" value="<?php echo isset($mail) ? htmlspecialchars($mail) : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Sign Up</button>
                            </div>
                            <div class="d-grid">
                                <a class="btn btn-outline-danger" href="index.php?controller=main&action=login">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>


