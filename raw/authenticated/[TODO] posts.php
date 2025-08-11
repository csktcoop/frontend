<?php
session_start();

redirectUnauthenticatedUserToLoginPage();

// Do action 1st because it change Users
checkAction();

$users = getUsers();
unset($users['unknownUserEmail']);

function deleteUser(string $email): void
{
    $user = getLoggedInUser();
    unset($_SESSION['user'][$email]);
    if ($user['email'] === $email) {
        $_SESSION['currentUser'] = 'unknownUserEmail';
        redirectUnauthenticatedUserToLoginPage();
    }
}

function editUser(string $email, string $newEmail, string $password): bool
{
    if ( ! isset($_SESSION['user'][$email]) ) {
        // User not found
        return false;
    }

    return addUser($email, $password, $newEmail);
}

function addUser(string $email, string $password, string $newEmail = ''): bool
{

    if (! $email || ! $password) {
        // Invalid input
        return false;
    }

    if ($newEmail) {
        $_SESSION['user'][$newEmail] = [
            'email'    => $newEmail,
            'password' => $password,
        ];

        $user = getLoggedInUser();
        if ($user['email'] === $email) {
            $_SESSION['currentUser'] = $newEmail;
        }

        unset($_SESSION['user'][$email]);
    } else {
        $_SESSION['user'][$email] = [
            'email'    => $email,
            'password' => $password,
        ];
    }

    return true;
}

function checkAction(): void
{
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'addUser':
            addUser($_POST['email'] ?? '', $_POST['password'] ?? '');
            break;
        case 'editUser':
            editUser($_POST['email'] ?? '',$_POST['newEmail'] ?? '', $_POST['password'] ?? '');
            break;
        case 'deleteUser':
            deleteUser($_POST['email'] ?? '');
            break;

        default:
            # code...
            break;
    }
}


function getUsers(): array
{
    return $_SESSION['user'] ?? [];
}

function getLoggedInUser(): array
{
    $currentUser = $_SESSION['currentUser'];

    return $_SESSION['user'][$currentUser] ?? $_SESSION['user']['unknownUserEmail'];
}

function redirectUnauthenticatedUserToLoginPage(): void
{
    $currentUser = $_SESSION['currentUser'] ?? 'unknownUserEmail';
    if ('unknownUserEmail' === $currentUser) {
        header('Location: login.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
  <!-- https://getbootstrap.com/docs/5.1/examples/ -->
  <head>
    <!-- HEAD META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Users</title>

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
            <li><a href="login.php" class="nav-link px-2 text-white">Login</a></li>
            <li><a href="users.php" class="nav-link px-2 text-secondary">Users</a></li>
            <li><a href="#" class="nav-link px-2 text-white">Pricing</a></li>
            <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>
            <li><a href="#" class="nav-link px-2 text-white">About</a></li>
          </ul>

          <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
            <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
          </form>

          <div class="text-end">
            <a href="logout.php" class="btn btn-outline-light me-2">Logout</a>
          </div>
        </div>
      </div>
    </header>
    <!-- HEADER -->

    <div class="d-flex">
    <!-- MAIN -->
    <main class="flex-fill">
      <!-- Add User Form -->
      <section class="py-5 text-center container">
        <div class="row py-lg-5">
          <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">Edit User</h1>
            <form>
                <!-- TODO: CSRF Token hidden input here -->
                <input type="hidden"
                name="action" id="idAction"
                value="addUser">

                <div class="mb-3">
                    <label for="idEmail" class="form-label">Email address</label>
                    <input type="email"
                    name="email" id="idEmail"
                    class="form-control" aria-describedby="emailHelp"
                    required>
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="idNewEmail" class="form-label">New Email address</label>
                    <input type="email"
                    name="newEmail" id="idNewEmail"
                    class="form-control" aria-describedby="emailHelp"
                    >
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
                formaction="users.php"
                formmethod="post"
                formenctype="multipart/form-data"
                id="idSubmitButton"
                class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </section>

      <!-- List Users -->
      <div class="album py-5 bg-light">
        <div class="container">

          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

<?php
// $users = [];
if (! $users) {
    ?>
            <div class="col">
              <div class="card shadow-sm">
                <div class="card-body">
                  <p class="card-text">No User</p>
                </div>
              </div>
            </div>
<?php
} // if no user
    ?>

<?php
foreach ($users as $theUser) {
    ?>
            <div class="col">
              <div class="card shadow-sm">
                <div class="card-body">
                  <p class="card-text"><?php echo $theUser['email'] ?? ''; ?></p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <button type="button"
                      onclick="document.getElementById('idAction').value='editUser';document.getElementById('idEmail').value='<?php echo $theUser['email'] ?? ''; ?>';document.getElementById('idPassword').value='<?php echo $theUser['password'] ?? ''; ?>';document.forms[0].scrollIntoView();"
                      class="btn btn-sm btn-outline-secondary">Edit</button>
                      <button type="button"
                      onclick="document.getElementById('idAction').value='deleteUser';document.getElementById('idEmail').value='<?php echo $theUser['email'] ?? ''; ?>';document.getElementById('idPassword').removeAttribute('required');document.getElementById('idSubmitButton').click();"
                      class="btn btn-sm btn-outline-secondary">Delete</button>
                    </div>
                    <small class="text-muted">9 mins</small>
                  </div>
                </div>
              </div>
            </div>
<?php
} // if users
    ?>

          </div>
        </div>
      </div>
    </main>
    <!-- MAIN -->
  </div>

    <div class="text-white bg-dark">
      <!-- FOOTER -->
      <div class="container">
        <footer class="py-5">
          <div class="row">
            <div class="col-2">
              <h5>Section</h5>
              <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Features</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Pricing</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">About</a></li>
              </ul>
            </div>

            <div class="col-2">
              <h5>Section</h5>
              <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Features</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Pricing</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">About</a></li>
              </ul>
            </div>

            <div class="col-2">
              <h5>Section</h5>
              <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Features</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Pricing</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">About</a></li>
              </ul>
            </div>

            <div class="col-4 offset-1">
              <form>
                <h5>Subscribe to our newsletter</h5>
                <p>Monthly digest of whats new and exciting from us.</p>
                <div class="d-flex w-100 gap-2">
                  <label for="newsletter1" class="visually-hidden">Email address</label>
                  <input id="newsletter1" type="text" class="form-control" placeholder="Email address">
                  <button class="btn btn-primary" type="button">Subscribe</button>
                </div>
              </form>
            </div>
          </div>

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
