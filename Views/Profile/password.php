<?php include "Views/templates/navbar.php"; ?>
<div class="container mt-4 py-2">
    <h3 class="fw-bold text-center text-uppercase">Reset Password</h3>

    <div class="card mt-5 rounded-0 shadow-lg">
        <div class="card-body px-5 py-5">
            <div class="d-flex justify-content-center">
                <form class="col-md-6" id="formPassword">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary col-4">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "Views/templates/footer.php"; ?>