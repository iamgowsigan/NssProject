
<div class="col-12">  
<!---Success Message--->  
<?php if($msg){ ?>

<div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>Success - </strong><?php echo htmlentities($msg); ?>
</div>
<?php } ?>

<!---Error Message--->
<?php if($error){ ?>

<div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>Error - </strong>  </strong><?php echo htmlentities($error); ?>
</div>

<?php } ?>

<!---Warning Message--->
<?php if($warning){ ?>

<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>Warning - </strong> </strong><?php echo htmlentities($warning); ?>
</div>

<?php } ?>

</div>
