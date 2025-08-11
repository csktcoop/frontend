<?php
// Assume using PHP Session instead of MySQL
// WP already have Post Scheduler

session_start();

$user = checkLogin();

function checkLogin(): void
{
    $defaultUser = 'unknownUserEmail';
    $currentUser = $_SESSION['currentUser'] ?? $defaultUser;

    [$email, $password] = getLoginDataFromPost();

    $email = $email ?: $currentUser;

    $user = [
        'email'    => $email,
        'password' => $password,
    ];
    if ($email && 'unknownUserEmail' !== $email && $password) {
        // TODO check DB for user
        $_SESSION['user'][$email] = $user;
        $_SESSION['currentUser']  = $email;

        redirectToUsersPage();
    }

    $_SESSION['user'][$email] = $user;
    $_SESSION['currentUser']  = $email;
}

function redirectToUsersPage(): void
{
    header('Location: users.php');
    exit;
}

function getLoginDataFromPost(): array
{
    $defaultUser = 'unknownUserEmail';
    return [
        $_POST['email'] ?? $defaultUser,
        $_POST['password'] ?? '',
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
  <!-- https://getbootstrap.com/docs/5.1/examples/ -->
  <head>
    <!-- HEAD META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <!-- HEAD STYLE -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  </head>
  <body>
<!-- STATE
<?php
print_r($_SESSION);
?>
-->

    <!-- HEADER -->
    <header class="p-3 bg-dark text-white">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
          <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
          </a>

          <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="login.php" class="nav-link px-2 text-secondary">Login</a></li>
            <li><a href="users.php" class="nav-link px-2 text-white">Users</a></li>
            <li><a href="#" class="nav-link px-2 text-white">Pricing</a></li>
            <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 text-white">About</a></li>
          </ul>

          <div class="text-end">
            <a href="login.php" class="btn btn-outline-light me-2">Login</a>
          </div>
        </div>
      </div>
    </header>
    <!-- HEADER -->

    <div class="d-flex">
    <!-- MAIN -->
    <main class="flex-fill">
      <section class="py-5 text-center container">
        <div class="row py-lg-5">
          <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">Login</h1>
            <form>
                <!-- TODO: CSRF Token hidden input here -->

                <div class="mb-3">
                    <label for="idEmail" class="form-label">Email address</label>
                    <input type="email"
                    name="email" id="idEmail"
                    class="form-control" aria-describedby="emailHelp"
                    required>
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="idPassword" class="form-label">Password</label>
                    <input type="password"
                    name="password" id="idPassword"
                    class="form-control"
                    required>
                </div>
                <button type="submit"
                formaction="login.php"
                formmethod="post"
                formenctype="multipart/form-data"
                class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </section>
    </main>
    <!-- MAIN -->
  </div>

    <div class="text-white bg-dark">
      <!-- FOOTER -->
      <div class="container">
        <footer class="py-5">
          <div class="d-flex justify-content-between py-4 my-4 border-top">
            <p>© 2021 Company, Inc. All rights reserved.</p>
            <ul class="list-unstyled d-flex">
              <li class="ms-3"><a class="link-dark" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#twitter"></use></svg></a></li>
              <li class="ms-3"><a class="link-dark" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#instagram"></use></svg></a></li>
              <li class="ms-3"><a class="link-dark" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#facebook"></use></svg></a></li>
            </ul>
          </div>
        </footer>
      </div>
      <!-- FOOTER -->
    </div>

    <!-- BOTTOM SCRIPT -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    </script>
  </body>
</html>
