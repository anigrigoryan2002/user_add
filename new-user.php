<?php
session_start();
include('config/db.php'); 

$email = $name = $phone = '';
$errors = array('email' => '', 'name' => '', 'phone' => '');
$emailExistsError = false; 

if (isset($_POST['submit'])) {

    if ($_POST['email'] == "") {
        $errors['email'] = 'An email is required <br />';
    } else {
        $email = trim($_POST['email']); 
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email must be a valid email address';
        } else {
            $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM users WHERE email = ?");
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $emailCount);
            mysqli_stmt_fetch($stmt);
            if ($emailCount > 0) {
                $errors['email'] = 'This email is already registered.';
                $emailExistsError = true; 
            }
            mysqli_stmt_close($stmt);
        }
    }

    if ($_POST['name'] == "") {
        $errors['name'] = 'A name is required <br />';
    } else {
        $name = trim($_POST['name']);
    }

    if ($_POST['phone'] == "") {
        $errors['phone'] = 'A phone number is required <br />';
    } else {
        $phone = trim($_POST['phone']);
        if (!preg_match('/^[0-9]{6,15}$/', $phone)) {
            $errors['phone'] = 'Phone number must be between 6 and 15 digits long and contain only numbers';
        }
    }

    if (!array_filter($errors) && !$emailExistsError) {
        $sql = "INSERT INTO users (name, email, phone) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $phone);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);  
            header('Location: success.php');
            exit();
        } else {
            $errors['general'] = 'Database error: Could not register user.';
        }
        
        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>Add User</title>
</head>
<body>
<section class="container grey-text">
    <h4 class="center">Add a User</h4>

    <form class="white" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">

        <label for="name">Your Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <div class="red-text"><?php echo $errors['name']; ?></div>

        <label for="email">Your Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <div class="red-text"><?php echo $errors['email']; ?></div>

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
        <div class="red-text"><?php echo $errors['phone']; ?></div>

        <?php if (isset($errors['general'])): ?>
            <div class="red-text"><?php echo $errors['general']; ?></div>
        <?php endif; ?>

        <div class="center">
            <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
        </div>
    </form>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    <?php if ($emailExistsError): ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'This email is already registered!',
        });
    <?php endif; ?>
</script>

</body>
</html>
