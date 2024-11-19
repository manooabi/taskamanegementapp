@extends('layouts.master')

@section('title', 'Task Management')

@section('content')

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="task_delete_id" id="task_id">
                    <h5>Are you sure you want to delete this task?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid px-4">
    <div class="card mt-4">
        <div class="card-header">
            <h4>View Tasks
                <a href="{{ url('/add-task') }}" class="btn btn-primary btn-sm float-end">Add Task</a>
            </h4>
        </div>
        <div class="card-body">
            <div id="alertMessage" class="alert d-none"></div>
            <table id="userTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Created At</th>
                        <th>Priority</th>
                        <th>Completed</th>
                        <th>Payment Status</th> 
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- AJAX-loaded rows will appear here -->
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div id="paginationControls" class="d-flex justify-content-between">
                <button id="prevPageBtn" class="btn btn-secondary" disabled>Previous</button>
                <span id="currentPageInfo">Page 1</span>
                <button id="nextPageBtn" class="btn btn-secondary" disabled>Next</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {
    const apiBaseUrl = "http://127.0.0.1:8000/api/tasks";
    let currentPage = 1;
    let lastPage = 1;
    const perPage = 5;

    // Function to fetch and display tasks
    function fetchTasks(page = 1) {
        $.ajax({
            url: apiBaseUrl + `?page=${page}&per_page=${perPage}`,
            type: "GET",
            success: function (response) {
                const taskTable = $("#userTable tbody");
                taskTable.empty(); // Clear existing rows
                response.data.forEach((task) => {
                    const paymentStatus = task.is_paid ? "Paid" : `<button class="btn btn-warning btn-sm payNowBtn" data-id="${task.id}">Pay Now</button>`;
                    taskTable.append(`
                        <tr>
                            <td>${task.id}</td>
                            <td>${task.title}</td>
                            <td>${task.description}</td>
                            <td>${task.user_id}</td>
                            <td>${new Date(task.due_date).toLocaleDateString()}</td>
                            <td>${new Date(task.created_at).toLocaleDateString()}</td>
                            <td>${task.priority}</td>
                            <td>
                                <input type="checkbox" role="switch" class="toggle-complete" data-on="Completed" 
                                       data-off="Pending" data-id="${task.id}" ${task.is_completed ? 'checked' : ''}>
                            </td>
                            <td>${paymentStatus}</td>
                            <td>
                                <a href="{{ url('/edit-task/${task.id}') }}" class="btn btn-success btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm deleteTaskBtn" value="${task.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                });
                $(".toggle-complete").bootstrapToggle();

                // Update pagination
                currentPage = response.current_page;
                lastPage = response.last_page;
                $("#currentPageInfo").text(`Page ${currentPage}`);
                $("#prevPageBtn").prop('disabled', currentPage === 1);
                $("#nextPageBtn").prop('disabled', currentPage === lastPage);
            },
            error: function (error) {
                showAlert('Error fetching tasks. Please try again later.', 'danger');
            },
        });
    }


        // Handle Pay Now button click
        $(document).on('click', '.payNowBtn', function () {
    const taskId = $(this).data('id');
    
    // Send AJAX POST request to the /checkout route
    $.ajax({
        url: '/checkout',  // Correct checkout route
        type: 'GET',  // Change to POST as it's a form submission
        data: {
            task_id: taskId, 
            _token: $("input[name='_token']").val(),  // Include CSRF token
        },
        success: function (response) {
            // Assuming the response contains the Stripe checkout URL
            if (response.url) {
                window.location.href = response.url;
            } else {
                showAlert('Error: No checkout URL returned.', 'danger');
            }
        },
        error: function (error) {
            showAlert('Error processing payment. Please try again later.', 'danger');
        }
    });
});
    // Handle change in task completion status
    $(document).on('change', '.toggle-complete', function () {
        const taskId = $(this).data('id');
        const completedStatus = $(this).prop('checked') ? true : false;
        $.ajax({
            url: `${apiBaseUrl}/${taskId}/complete`, // Assuming you have a route for updating task completion
            type: 'post',
            data: {
                completed: completedStatus,
                _token: $("input[name='_token']").val(),
            },
            success: function (response) {
                showAlert('Task status updated successfully.', 'success');
                fetchTasks(currentPage);  // Refresh the tasks list
            },
            error: function (error) {
                showAlert('Error updating task status. Please try again.', 'danger');
            },
        });
    });

    // Delete Task Event
    $(document).on("click", ".deleteTaskBtn", function () {
        const taskId = $(this).val();
        $("#task_id").val(taskId);
        $("#deleteModal").modal("show");
    });

    // Confirm Delete Button
    $("#confirmDeleteBtn").click(function () {
        const taskId = $("#task_id").val();
        $.ajax({
            url: `${apiBaseUrl}/${taskId}`,
            type: "DELETE",
            success: function (response) {
                $("#deleteModal").modal("hide");
                showAlert("Task deleted successfully.", "success");
                fetchTasks(currentPage);
            },
            error: function (error) {
                showAlert('Error deleting task. Please try again.', 'danger');
            },
        });
    });

    // Pagination controls
    $("#prevPageBtn").click(function () {
        if (currentPage > 1) {
            fetchTasks(currentPage - 1);
        }
    });

    $("#nextPageBtn").click(function () {
        if (currentPage < lastPage) {
            fetchTasks(currentPage + 1);
        }
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

    // Initial Fetch
    fetchTasks();
});
</script>
@endsection
