<?php
session_start();
include("../config/db.php");

// ================= ADMIN LOGIN CHECK =================
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// ================= DELETE USER =================
if(isset($_GET['delete'])){
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM users WHERE id=$delete_id");
    header("Location: manage_users.php");
    exit();
}

// ================= FETCH USERS =================
$result = mysqli_query($conn,"SELECT * FROM users ORDER BY created_at DESC");

// Total users
$total_users = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center">
        <h2>👥 Manage Users</h2>
        <span class="badge bg-primary fs-6">Total Users: <?php echo $total_users; ?></span>
    </div>

    <div class="card shadow mt-3">
        <div class="card-body">

            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Registered Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                if($total_users > 0){
                    while($row = mysqli_fetch_assoc($result)){
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['id']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure to delete this user?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No Users Found</td></tr>";
                }
                ?>

                </tbody>
            </table>

        </div>
    </div>

    <br>
    <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>

</div>

</body>
</html>