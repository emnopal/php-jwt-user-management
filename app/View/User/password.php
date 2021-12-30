<div class="container col-xl-10 col-xxl-8 px-4 py-5">
        <div class="col-md-10 mx-auto col-lg-5">
            <div class="row align-items-center g-lg-5 py-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold lh-1 mb-3">Password</h1>
                </div>
            <form class="p-4 p-md-5 border rounded-3 bg-light" method="post" action="/users/password">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" placeholder="username" disabled value="<?= $model['user']['username'] ?? '' ?>">
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="old" type="password" class="form-control" id="old"
                           placeholder="old">
                    <label for="old">Old Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="new" type="password" class="form-control" id="new"
                           placeholder="new">
                    <label for="new">New Password</label>
                </div>
                <?php if(isset($model['error'])) {?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $model['error']; ?>
                    </div>
                <?php } ?>
                <div style="justify-content:center;align-items:center;display:flex;">
                    <button class="w-50 btn btn-lg btn-primary" type="submit" style="margin-right:5px">Change Password</button>
                    <a href="/" class="w-50 btn btn-lg btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>