<?php
session_start();
require_once 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $task = trim($_POST['task'] ?? '');
    $due_date = $_POST['due_date'] ?? '';
    
    if (!empty($subject) && !empty($task) && !empty($due_date)) {
        try {
            // Insert into study_goals table
            $stmt = $pdo->prepare("INSERT INTO study_goals (student_id, title, description, target_date, status) VALUES (?, ?, ?, ?, 'Active')");
            $stmt->execute([$_SESSION['student_id'] ?? 1, $subject, $task, $due_date]);
            
            $success_message = "Study task added successfully!";
        } catch (PDOException $e) {
            $error_message = "Error adding task: " . $e->getMessage();
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}

// Get existing study tasks
$study_tasks = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM study_goals WHERE student_id = ? ORDER BY target_date ASC");
    $stmt->execute([$_SESSION['student_id'] ?? 1]);
    $study_tasks = $stmt->fetchAll();
} catch (PDOException $e) {
    // Table might not exist, that's okay for demo
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Study Planner</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1a1a1a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .title {
            text-align: center;
            margin-bottom: 40px;
            font-size: 3rem;
            font-weight: bold;
        }

        .title .purple {
            color: #8b5cf6;
        }

        .title .blue {
            color: #3b82f6;
        }

        .planner-card {
            background: #2d2d2d;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: white;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: none;
            border-radius: 10px;
            background: #3d3d3d;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.5);
            background: #4a4a4a;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #888;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        .due-date-input {
            position: relative;
        }

        .due-date-input input[type="date"] {
            padding-right: 40px;
        }

        .calendar-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
        }

        .add-button {
            width: 100%;
            padding: 15px;
            background: #8b5cf6;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .add-button:hover {
            background: #7c3aed;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.4);
        }

        .add-button:active {
            transform: translateY(0);
        }

        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .error {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .tasks-section {
            margin-top: 40px;
            width: 100%;
            max-width: 500px;
        }

        .tasks-title {
            text-align: center;
            margin-bottom: 20px;
            color: #8b5cf6;
            font-size: 1.5rem;
        }

        .task-item {
            background: #2d2d2d;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #7c3aed;
        }

        .task-subject {
            font-weight: bold;
            color: #8b5cf6;
            margin-bottom: 5px;
        }

        .task-description {
            color: #ccc;
            margin-bottom: 10px;
        }

        .task-date {
            color: #888;
            font-size: 0.9rem;
        }

        .no-tasks {
            text-align: center;
            color: #888;
            font-style: italic;
        }

        @media (max-width: 600px) {
            .planner-card {
                margin: 20px;
                padding: 30px 20px;
            }
            
            .title {
                font-size: 2rem;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <h1 class="title">
        <span class="purple">Student Study</span> <span class="blue">Planner</span>
    </h1>

    <div class="planner-card">
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="e.g. Mathematics" required>
            </div>

            <div class="form-group">
                <label for="task">Task</label>
                <textarea id="task" name="task" placeholder="Describe the study task..." required></textarea>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <div class="due-date-input">
                    <input type="date" id="due_date" name="due_date" placeholder="mm/dd/yyyy" required>
                    <i class="fas fa-calendar calendar-icon"></i>
                </div>
            </div>

            <button type="submit" class="add-button">Add To Planner</button>
        </form>
    </div>

    <?php if (!empty($study_tasks)): ?>
        <div class="tasks-section">
            <h2 class="tasks-title">Your Study Tasks</h2>
            <?php foreach ($study_tasks as $task): ?>
                <div class="task-item">
                    <div class="task-subject"><?php echo htmlspecialchars($task['title']); ?></div>
                    <div class="task-description"><?php echo htmlspecialchars($task['description']); ?></div>
                    <div class="task-date">Due: <?php echo date('F j, Y', strtotime($task['target_date'])); ?></div>
                </div>
            <?php endforeach; ?>
        </div> 
    <?php else: ?>
        <div class="tasks-section">
            <h2 class="tasks-title">Your Study Tasks</h2>
            <div class="no-tasks">No study tasks yet. Add your first task above!</div>
        </div>
    <?php endif; ?>

    <script>
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('due_date').min = today;

        // Add some interactive effects
        document.querySelectorAll('.form-group input, .form-group textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const subject = document.getElementById('subject').value.trim();
            const task = document.getElementById('task').value.trim();
            const dueDate = document.getElementById('due_date').value;

            if (!subject || !task || !dueDate) {
                e.preventDefault();
                alert('Please fill in all fields.');
                return false;
            }
        });
    </script>
</body>
</html>