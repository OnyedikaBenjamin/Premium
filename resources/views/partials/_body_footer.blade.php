    <?php
    $app = \App\Models\AppSetting::first();
    ?>
    <footer class="iq-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 ">
                    <span class="mr-1">
                {!!$app->site_copyright !!}
                    </span>
                </div>
            </div>
        </div>
    </footer>