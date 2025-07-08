<?php include 'includes/header.php';
?>

<div class="container py-4">
    <h2 class="mb-4 text-light">Employee Management</h2>

    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-success btnColor" data-bs-toggle="modal" data-bs-target="#addModal">➕ Add Employee</button>
        <input type="text" class="form-control w-25" placeholder="Search..." id="searchInput">
    </div>

    <table class="table table-hover table-bordered" id="employeeTable">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Image</th>
                <th>Name <span class="sort-icon fa-solid fa-sort" data-sort="name"></span></th>
                <th>Email <span class="sort-icon fa-solid fa-sort" data-sort="email"></span></th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div id="paginationControls" class="mt-3 d-flex justify-content-end"></div>
</div>
<?php include 'includes/modals.php'; ?>
<?php include 'includes/footer.php'; ?>