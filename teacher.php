<?php
require 'includes/config.php';
if(!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['teacher','admin'])) { header('Location: index.php'); exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $subject_id = intval($_POST['subject_id']);
    $marks = intval($_POST['marks']);
    $term = $_POST['term'] ?? 'term1';
    $stmt = $pdo->prepare("INSERT INTO marks (student_id, subject_id, marks, term) VALUES (?,?,?,?)
        ON DUPLICATE KEY UPDATE marks = VALUES(marks), updated_at = NOW()");
    $stmt->execute([$student_id, $subject_id, $marks, $term]);
    $msg = "Saved.";
}

$students = $pdo->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC);
$subjects = $pdo->query("SELECT * FROM subjects")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Teacher Dashboard</title></head><body>
<h2>Teacher - Add / Update Marks</h2>
<?php if(!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
<form method="post">
  <label>Student:
    <select name="student_id">
    <?php foreach($students as $s) echo '<option value="'.$s['id'].'">'.htmlspecialchars($s['name'].' ('.$s['roll_no'].')').'</option>'; ?>
    </select>
  </label><br>
  <label>Subject:
    <select name="subject_id">
    <?php foreach($subjects as $sub) echo '<option value="'.$sub['id'].'">'.htmlspecialchars($sub['name']).'</option>'; ?>
    </select>
  </label><br>
  <label>Term: <input name="term" value="term1"></label><br>
  <label>Marks: <input name="marks" type="number"></label><br>
  <button type="submit">Save</button>
</form>
<p><a href="index.php">Logout</a></p>
</body></html>
