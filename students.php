<?php
require 'conf.php'; // Include database connection

// Handle form submissions for adding, updating, or deleting students
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_student'])) {
        // Add student
        $name = $_POST['name'];
        $course = $_POST['course'];
        $stmt = $conn->prepare("INSERT INTO students (name, course) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $course);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['update_student'])) {
        // Update student
        $id = $_POST['id'];
        $name = $_POST['name'];
        $course = $_POST['course'];
        $stmt = $conn->prepare("UPDATE students SET name = ?, course = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $course, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_student'])) {
        // Delete student
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch student details
$students = [];
$result = $conn->query("SELECT id, name, course FROM students");
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
?>

<h2>Manage Students</h2>

<!-- Form to Add New Student -->
<div class="form-container">
    <h3>Add New Student</h3>
    <form method="post">
        <input type="text" name="name" placeholder="Student Name" required>
        <input type="text" name="course" placeholder="Course" required>
        <button type="submit" name="add_student">Add Student</button>
    </form>
</div>

<!-- Display Students Table -->
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Course</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($students as $student): ?>
        <tr>
            <td><?php echo htmlspecialchars($student['id']); ?></td>
            <td><?php echo htmlspecialchars($student['name']); ?></td>
            <td><?php echo htmlspecialchars($student['course']); ?></td>
            <td>
                <!-- Update Student Form -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                    <input type="text" name="course" value="<?php echo htmlspecialchars($student['course']); ?>" required>
                    <button type="submit" name="update_student">Update</button>
                </form>
                <!-- Delete Student Form -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">
                    <button type="submit" name="delete_student">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
