<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danmark's To-Do List</title>
    <style>
        body {
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(to right, #a1c4fd, #c2e9fb);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 500px;
            height: 600px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 20px;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.5);
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            font-weight: 700;
            color: #333;
            letter-spacing: 1px;
        }

        input {
            width: calc(100% - 20px);
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            outline: none;
            background: #f7f7f7;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ffcc00;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            background: #ffdb4d;
            transform: scale(1.05);
        }

        ul {
            list-style: none;
            padding: 0;
            flex-grow: 1;
            overflow-y: auto;
            max-height: 250px;
        }

        li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        li:hover {
            background: rgba(255, 255, 255, 0.6);
        }

        .completed {
            text-decoration: line-through;
            color: gray;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Danmark's To-Do List</h2>
    <input type="text" id="taskInput" placeholder="Add a new task...">
    <button onclick="addTask()">Add Task</button>

    <ul id="taskList"></ul>

    <div class="recently-deleted">
        <h3>Recently Deleted</h3>
        <ul id="deletedList"></ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", loadTasks);

    function addTask() {
        let input = document.getElementById("taskInput");
        let taskText = input.value.trim();
        if (taskText === "") return;

        let li = createTaskElement(taskText);
        document.getElementById("taskList").appendChild(li);
        saveTasks();
        input.value = "";
    }

    function createTaskElement(taskText, isCompleted = false) {
        let li = document.createElement("li");
        li.innerHTML = `
            <span class="${isCompleted ? 'completed' : ''}" onclick="toggleComplete(this)">${taskText}</span>
            <span class="delete" onclick="removeTask(this)">×</span>
        `;
        return li;
    }

    function toggleComplete(element) {
        element.classList.toggle("completed");
        saveTasks();
    }

    function removeTask(element) {
        let deletedTask = element.parentElement;
        let taskText = deletedTask.querySelector("span").textContent;
        deletedTask.remove();

        let li = document.createElement("li");
        li.innerHTML = `
            <span>${taskText}</span>
            <span class='restore' onclick='restoreTask(this)'>↩</span>
            <span class='delete-now' onclick='deleteNow(this)'>❌</span>
        `;
        document.getElementById("deletedList").appendChild(li);
        saveTasks();
    }

    function restoreTask(element) {
        let restoredTask = element.parentElement;
        let taskText = restoredTask.querySelector("span").textContent; 
        restoredTask.remove();

        let li = createTaskElement(taskText);
        document.getElementById("taskList").appendChild(li);
        saveTasks();
    }

    function deleteNow(element) {
        element.parentElement.remove();
        saveTasks();
    }

    function saveTasks() {
        let tasks = [];
        document.querySelectorAll("#taskList li").forEach(li => {
            tasks.push({ text: li.querySelector("span").textContent, completed: li.querySelector("span").classList.contains("completed") });
        });
        let deletedTasks = [];
        document.querySelectorAll("#deletedList li").forEach(li => {
            deletedTasks.push(li.querySelector("span").textContent);
        });

        localStorage.setItem("tasks", JSON.stringify(tasks));
        localStorage.setItem("deletedTasks", JSON.stringify(deletedTasks));
    }

    function loadTasks() {
        let tasks = JSON.parse(localStorage.getItem("tasks")) || [];
        let deletedTasks = JSON.parse(localStorage.getItem("deletedTasks")) || [];

        tasks.forEach(task => {
            let li = createTaskElement(task.text, task.completed);
            document.getElementById("taskList").appendChild(li);
        });

        deletedTasks.forEach(taskText => {
            let li = document.createElement("li");
            li.innerHTML = `
                <span>${taskText}</span>
                <span class='restore' onclick='restoreTask(this)'>↩</span>
                <span class='delete-now' onclick='deleteNow(this)'>❌</span>
            `;
            document.getElementById("deletedList").appendChild(li);
        });
    }
</script>

</body>
</html>