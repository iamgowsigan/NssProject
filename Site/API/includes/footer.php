<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <?= date('Y', strtotime("-1 year", time())); ?> -
                <?= date('Y'); ?> ©
                <?= $projectname; ?>
            </div>
            <div class="col-md-6">
                <div class="text-md-right footer-links d-none d-md-block">
                    <a href="<?= $shopurl; ?>"><?php mylan("About ", " حول"); ?></a>
                    <a href="<?= $shopurl; ?>"><?php mylan("Support ", " الدعم"); ?></a>
                    <a href="<?= $shopurl; ?>"><?php mylan("Contact Us ", " اتصل بنا"); ?></a>
                </div>
            </div>
        </div>
    </div>
</footer>