<!DOCTYPE html>
<html>
<head>
    <title>Task Completed</title>
</head>
<body>
    <h1>Task Completed</h1>
    <p>Hello,</p>
    <p>The following task has been marked as completed:</p>
    <p><strong>Title:</strong> {{ $task->title }}</p>
    <p><strong>Description:</strong> {{ $task->description }}</p>
    <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
    <p>Thank you!</p>
</body>
</html>
