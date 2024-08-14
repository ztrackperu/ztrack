<?php include "Views/templates/navbar.php"; ?>
<div class="container mt-4 py-2">
    <div class="row">
        <div class="col-md-3 mt-2">
            <div class="card">
                <div class="card-body text-center" id="profileInformation">
                    <img src="https://via.placeholder.com/150" alt="profile" class="img-fluid rounded-circle">
                    <h4 id="userName">Username</h4>

                    <div class="mt-4">
                        <h6 class="font-bold">FullName:</h6>
                        <p id="fullname">FullNameUser</p>
                    </div>
                    <div class="mt-2">
                        <h6 class="font-bold">Email:</h6>
                        <p id="email">EmailUser</p>
                    </div>
                    <div class="mt-2">
                        <h6 class="font-bold">Phone:</h6>
                        <p id="phone">PhoneUser</p>
                    </div>
                    <div class="mt-2">
                        <h6 class="font-bold">Address:</h6>
                        <p id="address">AddressUser</p>
                    </div>
                    <div class="mt-2">
                        <h6 class="font-bold">Company:</h6>
                        <p id="company">CompanyUser</p>
                    </div>
                    <div class="mt-2">
                        <h6 class="font-bold">City:</h6>
                        <p id="city">CityUser</p>
                    </div>
                    <div class="mt-2">
                        <h6 class="font-bold">Country:</h6>
                        <p id="country">CountryUser</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="font-bold">Edit Profile</h4>
                        <button type="button" class="btn btn-outline-dark">
                            <i class="ri-pencil-fill"></i>
                        </button>
                    </div>
                    <form id="formProfile" class="py-2">
                        <div class="form-group">
                            <div class="mt-2">
                                <label for="username">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" readonly>
                            </div>
                            <div class="mt-2">
                                <label for="username">Lastname</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname" readonly>
                            </div>
                            <div class="mt-2">
                                <label for="username"><i class="ri-mail-fill"></i> Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" readonly>
                            </div>
                            <div class="mt-2">
                                <label for="username"><i class="ri-phone-fill"></i> Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Example: +51 999 999 999" readonly>
                            </div>
                            <!--
                            <div class="mt-2">
                                <label for="username"><i class="bi bi-geo-alt"></i> Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Address" readonly>
                            </div>
                            <div class="mt-2">
                                <label for="username"><i class="bi bi-building"></i> Company</label>
                                <input type="text" class="form-control" id="company" name="company" placeholder="Company" readonly>
                            </div>
                            <div class="mt-2">
                                <label for="username"><i class="bi bi-buildings"></i> City</label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="City" readonly>
                            </div>
                            <div class="mt-2">
                                <label for="username"><i class="bi bi-globe-americas"></i> Country</label>
                                <input type="text" class="form-control" id="country" name="country" placeholder="Country" readonly>
                            </div>
                            -->
                            <div class="mt-4 d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-danger col-4">Cancel</button>
                                <button type="submit" class="btn btn-primary col-4">Save</button>
                            </div>

                            <div class="mt-4 border rounded px-3 py-3">
                                <h5 clas="fw-bold">Equipos Asignados</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <th scope="col">PWR</th>
                                            <th scope="col">Reefer ID</th>
                                            <th scope="col">Tipo</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><i class="bi bi-plug-fill"></i></td>
                                                <td>123456</td>
                                                <td>Reefer</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-plug-fill"></i></td>
                                                <td>123456</td>
                                                <td>Reefer</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-plug-fill"></i></td>
                                                <td>123456</td>
                                                <td>Reefer</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include "Views/templates/footer.php"; ?>