<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
<?php foreach($stores as $store){ ?>
    <div class="col-lg-6 col-md-6 col-sm-12 float-right text-right my-4 pb-0">
        <a href="<?php echo base_url('AdminControllerCashier/wholesale/'.$store->id); ?>" class="btn btn-success btn-block py-3">
            <h3>فرع <?php echo $store->name; ?></h3>
            <p><?php echo $store->address; ?></p>
        </a>
    </div>
<?php } ?>
</div>