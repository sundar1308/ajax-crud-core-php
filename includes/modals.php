<!--  ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="employeeForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control">
                    <small class="text-danger" id="err_name"></small>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                    <small class="text-danger" id="err_email"></small>
                </div>
                <div class="mb-3">
                    <label>Profile Image</label>
                    <input type="file" name="profileImg" id="profileImg" class="form-control">
                    <small class="text-danger" id="err_profileImg"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btnColor">Add Employee</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <div class="spinner-border text-primary mx-auto" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editEmployeeForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" id="edit_name">
                    <small class="text-danger" id="edit_error_name"></small>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" id="edit_email">
                    <small class="text-danger" id="edit_error_email"></small>
                </div>
                <div class="mb-3">
                    <label>Change Profile Image</label>
                    <input type="file" name="profileImg" class="form-control">
                    <small class="text-danger" id="edit_error_profileImg"></small>
                </div>
                <div class="mb-3">
                    <img id="edit_preview" src="" width="80" class="rounded shadow-sm">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn  btnColor">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Employee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="viewImage" src="" class="rounded mb-3" width="100" />
                <p><strong>Name:</strong> <span id="viewName"></span></p>
                <p><strong>Email:</strong> <span id="viewEmail"></span></p>
                <p><strong>Created:</strong> <span id="viewCreated"></span></p>
                <p><strong>Updated:</strong> <span id="viewUpdated"></span></p>
            </div>
        </div>
    </div>
</div>