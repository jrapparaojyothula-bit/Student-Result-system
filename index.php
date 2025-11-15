<?php
require 'includes/config.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT id, password_hash, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($password, $user['password_hash'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if($user['role'] === 'student') header('Location: student.php');
        else header('Location: teacher.php');
        exit;
    } else {
        $error = "Login failed";
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Student Result - Login</title></head><body>
<h2>Login</h2>
<?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
  <label>Username: <input name="username"></label><br>
  <label>Password: <input name="password" type="password"></label><br>
  <button type="submit">Login</button>
</form>
</body></html>
