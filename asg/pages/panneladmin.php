<?php
include '../backend/functions.php'; // Ensure the file with the getAllUsers function is included

// Fetch all users from the database
$users = getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once("../includes/head.php"); ?>

<body>
    <?php include_once("../includes/header.php"); ?>
    <?php include_once("../includes/nav.php"); ?>

    <?php if (getCurrentUserRole() != 4) { ?>
        <div class="container my-5">
            <div class="alert alert-warning text-center" role="alert">
                <h2 class="section-heading mb-3">
                    <span class="section-heading-upper">Vous n'êtes pas administrateur</span>
                    <br>
                    <span class="section-heading-lower">Redirection vers la page de connexion...</span>
                </h2>
            </div>
            <?php
            echo "<script type='text/javascript'>
                    setTimeout(function() {
                        window.location.href = '../pages/index.php';
                    }, 5000);
                  </script>";
            ?>
        </div>

    <?php } else { ?>
        <section class="page-section cta">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 mx-auto">
                        <div class="cta-inner bg-faded text-center rounded">
                            <h2 class="section-heading mb-4">
                                <h2 class="section-heading mb-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover align-middle">
                                            <thead class="table-dark text-center">
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Role</th>
                                                    <th scope="col">CP</th>
                                                    <th scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($users)): ?>
                                                    <?php foreach ($users as $user): ?>
                                                        <tr id="user-<?php echo $user['id']; ?>">
                                                            <td class="text-center"><?php echo htmlspecialchars($user['id']); ?>
                                                            </td>
                                                            <td contenteditable="true"
                                                                onblur="updateUser(<?php echo $user['id']; ?>, 'username', this.innerText)">
                                                                <?php echo htmlspecialchars($user['username']); ?>
                                                            </td>
                                                            <td contenteditable="true"
                                                                onblur="updateUser(<?php echo $user['id']; ?>, 'email', this.innerText)">
                                                                <?php echo htmlspecialchars($user['email']); ?>
                                                            </td>
                                                            <td contenteditable="true"
                                                                onblur="updateUser(<?php echo $user['id']; ?>, 'role', this.innerText)">
                                                                <?php echo htmlspecialchars($user['role']); ?>
                                                            </td>
                                                            <td contenteditable="true"
                                                                onblur="updateUser(<?php echo $user['id']; ?>, 'cp', this.innerText)">
                                                                <?php echo htmlspecialchars($user['cp']); ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <button class="btn btn-danger btn-sm"
                                                                    onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">No users found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                </h2>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
    </section>

    <?php include_once("../includes/footer.php"); ?>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="../js/scripts.js"></script>
    <!-- Custom AJAX Script to handle inline updates and deletes -->
    <script>
        function updateUser(userId, field, value) {
            fetch('../backend/functions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: userId, field: field, value: value })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Update successful');
                    } else {
                        console.error('Update failed:', data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch('../backend/functions.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: userId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('User deleted');
                            document.getElementById('user-' + userId).remove();
                        } else {
                            console.error('Deletion failed:', data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>

</html>