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
        <a href="{{ url('admin/add-task') }}" class="btn btn-primary btn-sm float-end">Add Task</a>
      </h4>
    </div>
    <div class="card-body">
      <div id="alertMessage" class="alert d-none"></div>
      <table id="myDataTable" class="table table-bordered">
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
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- AJAX-loaded rows will appear here -->
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {
    const apiBaseUrl = "http://127.0.0.1:8000/api/tasks";

    // Function to fetch and display tasks
    function fetchTasks() {
        $.ajax({
            url: apiBaseUrl,
            type: "GET",
            success: function (response) {
                const taskTable = $("#myDataTable tbody");
                taskTable.empty(); // Clear existing rows
                response.data.forEach((task) => {
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
                            <input type="checkbox" class="toggle-complete" data-id="${task.id}" ${task.completed ? 'checked' : ''}>
                        </td>
                            <td>
                                <a href="{{ url('admin/edit-task/${task.id}') }}" class="btn btn-success btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm deleteTaskBtn" value="${task.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function (error) {
                showAlert('Error fetching tasks. Please try again later.', 'danger');
            },
        });
    }
    $(document).on('change', '.toggle-complete', function () {
        const taskId = $(this).data('id');
        const completedStatus = $(this).prop('checked') ? 1 : 0;

        $.ajax({
            url: `${apiBaseUrl}/${taskId}/complete`, // Assuming you have a route for updating task completion
            type: 'post',
            data: {
                completed: completedStatus,
                _token: $("input[name='_token']").val(),
            },
            success: function (response) {
                showAlert('Task status updated successfully.', 'success');
                fetchTasks();  // Refresh the tasks list
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
                fetchTasks();
            },
            error: function (error) {
                showAlert('Error deleting task. Please try again.', 'danger');
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

    // Initial Fetch
    fetchTasks();
});
</script>
@endsection
