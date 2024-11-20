@extends('layouts.master')

@section('title', 'Create Task')

@section('content')
<div class="container-fluid px-4">
    <div class="card mt-4">
        <div class="card-header">
            <h4>Create Task</h4>
            <a href="{{ url('/tasks') }}" class="btn btn-primary btn-sm float-end">Back to Tasks</a>
        </div>
        <div class="card-body">
            <form id="createTaskForm">
                @csrf
                         <div class="mb-3">
                            <label for="title">Task Title</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                 
                        <div class="mb-3">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                        </div>
               
                        <div class="mb-3">
                            <label for="due_date">Due Date</label>
                            <input type="date" id="due_date" name="due_date" class="form-control" required>
                        </div>
      

                   
                        <div class="mb-3">
                            <label for="priority">Priority</label>
                            <select id="priority" name="priority" class="form-control" required>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
            

                    <div class="mb-3">
                            <label for="user_id">Assigned To</label>
                            <select id="user_id" name="user_id" class="form-control" required>
                                <!-- User options will be dynamically loaded here -->
                            </select>
                        </div>
            
                        <div class="mb-3">
    <label for="is_completed" data-bs-toggle="tooltip" title="Action not allowed during creation ">Completed</label>
    <div class="form-check form-check-inline">
        <input type="checkbox" id="is_completed"  name="is_completed" value="false" class="form-check-input" disabled>
    </div>
</div>

<div class="mb-3">
    <label for="is_paid"data-bs-toggle="tooltip" title="Action not allowed during creation ">Paid</label>
    <div class="form-check form-check-inline">
        <input type="checkbox" id="is_paid" name="is_paid" value="false" class="form-check-input" disabled>
    </div>
</div>
                
             

                <button type="submit" class="btn btn-primary">Create Task</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        const apiBaseUrl = "http://127.0.0.1:8000/api/tasks";
        const usersApiUrl = "http://127.0.0.1:8000/api/users";

        // Fetch users for the "Assigned To" dropdown
        function fetchUsers() {
            $.ajax({
                url: usersApiUrl,
                type: "GET",
                success: function (response) {
                    const userSelect = $("#user_id");
                    userSelect.empty(); // Clear existing options

                    // Add default option
                    userSelect.append('<option value="" selected>Select a user</option>');

                    // Loop through users and append to the dropdown
                    response.data.data.forEach(function (user) {
                        userSelect.append(`
                            <option value="${user.id}">${user.name}</option>
                        `);
                    });
                },
                error: function (error) {
                    console.error("Error fetching users:", error);
                }
            });
        }

        // Handle task form submission
        $("#createTaskForm").on("submit", function (e) {
            e.preventDefault();

            const taskData = {
                title: $("#title").val(),
                description: $("#description").val(),
                due_date: $("#due_date").val(),
                priority: $("#priority").val(),
                user_id: $("#user_id").val(), // Add selected user ID
                // is_completed: $("#is_completed").prop('checked') ? true : false, // Defaulting to false
                // is_paid: $("#is_paid").prop('checked') ? true : false, // Defaulting to false
                _token: $("input[name='_token']").val(),
            };

            $.ajax({
                url: apiBaseUrl,
                type: "POST",
                data: taskData,
                success: function (response) {
                    if (response.success) {
                        showAlert("Task created successfully.", "success");
                        window.location.href = "{{ url('/tasks') }}";
                    } else {
                        showAlert("Error creating task. Please try again.", "danger");
                    }
                },
                error: function (error) {
                    showAlert("Error creating task. Please try again.", "danger");
                },
            });
        });

        // Function to display alert messages
        function showAlert(message, type) {
            const alertBox = $("#alertMessage");
            alertBox
                .removeClass("d-none alert-success alert-danger")
                .addClass(`alert-${type}`)
                .text(message);
            setTimeout(() => {
                alertBox.addClass("d-none");
            }, 3000);
        }

        // Initial function call to load users in dropdown
        fetchUsers();
    });
</script>
@endsection
