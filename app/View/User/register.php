<div class="container col-xl-10 col-xxl-8 px-4 py-4">
        <div class="col-md-10 mx-auto col-lg-5">
            <div class="row align-items-center g-lg-5 py-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold lh-1 mb-3">Register</h1>
                </div>
            <form class="p-4 p-md-5 border rounded-3 bg-light" method="post" action="/users/register">
                <div class="form-floating mb-3">
                    <input name="username" type="text" class="form-control" id="username" placeholder="username" value="<?= $_POST['username'] ?? '' ?>">
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="fullName" type="text" class="form-control" id="fullName" placeholder="fullName" value="<?= $_POST['fullName'] ?? '' ?>">
                    <label for="fullName">Full Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="email" type="text" class="form-control" id="email" placeholder="email" value="<?= $_POST['email'] ?? '' ?>">
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="password" type="password" class="form-control" id="password" placeholder="password">
                    <label for="password">Password</label>
                </div>
                <?php if(isset($model['error'])) {?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $model['error']; ?>
                        </div>
                <?php } ?>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>
            </form>
                <div class="text-center" style="margin-top: 20px">
                    <h6>Already have an account? <a href="/users/login">Login Here!</a></h6>
                </div>
        </div>
    </div>
</div>