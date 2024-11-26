<?php
session_start();
require_once('./function.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'] ?? ''; 
  $password = $_POST['password'] ?? '';

  $isValid = checkUser($email, $password);

  if ($isValid) {
    $_SESSION['loggedin'] = true; // Set session variable for login status
    $_SESSION['user_email'] = $email; // Optional: store user email or other info
    $message = "Login successful!";
    header('Location: adminPage.php'); // Redirect to the homepage or admin panel
    exit;
  } else {
    $message = "Invalid email or password.";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="icon" type="image/x-icon" href="assets/images/shababs-logo.ico">

</head>

<body>

  <form method="post">
    <section class="vh-100 gradient-custom">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card bg-dark text-white" style="border-radius: 1rem;">
              <div class="card-body p-5 text-center">

                <div class="mb-md-5 mt-md-4 pb-5">
                  <a class="logo" href="./index.php">
                    <?php $imagePath = '/assets/images/shababs-logo.jpg'; ?>
                    <img src="<?php echo $imagePath; ?>" alt="Logo" style="width:100px;height:100px;" />
                  </a>
                  <style>
                    img {
                      margin-bottom: 15px;
                    }
                  </style>
                  <h2 class="fw-bold mb-2 text-uppercase">Shababs Chef</h2>
                  <p class="text-white-50 mb-5">Gib deine Anmeldedaten ein G!!</p>

                  <?php if (!empty($message)): ?>
                    <div class="alert alert-info"><?= $message ?></div>
                  <?php endif; ?>

                  <div data-mdb-input-init class="form-outline form-white mb-4">
                    <input type="email" name="email" id="typeEmailX" class="form-control form-control-lg" required />
                    <label class="form-label" for="typeEmailX">Email</label>
                  </div>

                  <div data-mdb-input-init class="form-outline form-white mb-4">
                    <input type="password" name="password" id="typePasswordX" class="form-control form-control-lg" required />
                    <label class="form-label" for="typePasswordX">Password</label>
                  </div>

                  <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>

                  <div class="d-flex justify-content-center text-center mt-4 pt-1">
                    <a href="#!" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#!" class="text-white"><i class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                    <a href="#!" class="text-white"><i class="fab fa-google fa-lg"></i></a>
                  </div>

                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</body>

</html>