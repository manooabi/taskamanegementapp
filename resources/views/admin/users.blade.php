@extends('layouts.master')

@section('title', 'Users Management')

@section('content')

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="deleteForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_delete_id" id="user_id">
          <h5>Are you sure you want to delete this user?</h5>
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
      <h4>View Users
        <a href="{{ url('admin/add-user') }}" class="btn btn-primary btn-sm float-end">Add User</a>
      </h4>
    </div>
    <div class="card-body">
      <div id="alertMessage" class="alert d-none"></div>
      <table id="userTable" class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
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
    const apiBaseUrl = "http://127.0.0.1:8000/api/users";

    // Function to fetch and display users
    function fetchUsers() {
        $.ajax({
            url: apiBaseUrl,
            type: "GET",
            success: function (response) {
                const userTable = $("#userTable tbody");
                userTable.empty(); // Clear existing rows
                response.data.data.forEach((user) => {
                    userTable.append(`
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${new Date(user.created_at).toLocaleDateString()}</td>
                        </tr>
                    `);
                });
            },
            error: function (error) {
                showAlert('Error fetching users. Please try again later.', 'danger');
            },
        });
    }

    // Delete User Event
    $(document).on("click", ".deleteUserBtn", function () {
        const userId = $(this).val();
        $("#user_id").val(userId);
        $("#deleteModal").modal("show");
    });

    // Confirm Delete Button
    $("#confirmDeleteBtn").click(function () {
        const userId = $("#user_id").val();
        $.ajax({
            url: `${apiBaseUrl}/${userId}`,
            type: "DELETE",
            success: function (response) {
                $("#deleteModal").modal("hide");
                showAlert("User deleted successfully.", "success");
                fetchUsers();
            },
            error: function (error) {
                showAlert('Error deleting user. Please try again.', 'danger');
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
    fetchUsers();
});
</script>
@endsection
