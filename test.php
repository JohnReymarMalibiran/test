<?php
include 'db.php'; // Include the database connection

// Fetch tasks from the database
$stmt = $conn->prepare("SELECT * FROM tasks");
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar">
        <h2>Todo List</h2>
        <a href="#">New Task</a>
    </div>
    <div class="content">
        <h3>New Task</h3>
        <form action="add_task.php" method="POST">
            <input type="text" name="task_name" placeholder="Task" required>
            <button type="submit">Add Task</button>
        </form>

        <h3>Task Lists</h3>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <?php if ($task['is_completed'] == 0): ?>
                    <li>
                        <?= htmlspecialchars($task['task_name']) ?>
                        <a href="complete_task.php?id=<?= $task['id'] ?>" class="complete-btn">Complete</a>
                        <a href="delete_task.php?id=<?= $task['id'] ?>" class="delete-btn">Delete</a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <h3>Completed Tasks</h3>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <?php if ($task['is_completed'] == 1): ?>
                    <li><?= htmlspecialchars($task['task_name']) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
