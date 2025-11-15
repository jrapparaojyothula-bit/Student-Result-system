<?php
require 'includes/config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') { header('Location: index.php'); exit; }
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT s.id FROM students s WHERE s.user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$student) { echo 'Student record missing.'; exit; }
$student_id = $student['id'];

$sql = "SELECT sub.name, m.marks, m.term FROM marks m
        JOIN subjects sub ON sub.id = m.subject_id
        WHERE m.student_id = ? ORDER BY m.term, sub.name";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Student - Results</title></head><body>
<h2>Your Marks</h2>
<table border="1" cellpadding="6"><tr><th>Subject</th><th>Term</th><th>Marks</th></tr>
<?php foreach($rows as $r): ?>
  <tr><td><?php echo htmlspecialchars($r['name']); ?></td><td><?php echo htmlspecialchars($r['term']); ?></td><td><?php echo $r['marks']; ?></td></tr>
<?php endforeach; ?>
</table>
<p><a href="index.php">Logout</a></p>
</body></html>
