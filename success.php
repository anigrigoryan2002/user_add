<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <title>Success</title>
</head>
<body>
    <div class="container center">
        <h4>User Added Successfully!</h4>
        <p>The user has been successfully added to the system.</p>
        <a href="new-user.php" class="btn brand">Add Another User</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'User added successfully!',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
</body>
</html>
