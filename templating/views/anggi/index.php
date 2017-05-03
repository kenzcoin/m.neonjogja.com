<!DOCTYPE html>
<html>
<head>

    <title>{judul_halaman}</title>
    <meta content="IE=edge" http-equiv="x-ua-compatible">
    <meta content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>

    <!-- Icons -->
    <link rel="shortcut icon" href="<?= base_url('assets/back/img/favicon.png') ?>">

    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" media="all" rel="stylesheet" type="text/css">
    <!-- Styles -->
    
    <link href="<?= base_url('assetsnew/css/keyframes.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assetsnew/css/materialize.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assetsnew/css/swiper.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assetsnew/css/swipebox.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assetsnew/css/style.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assetsnew/css/stylenew.css') ?>" rel="stylesheet" type="text/css">

    <!-- style -->
    <link rel="stylesheet" href="<?= base_url('assetsnew/css/sweetalert.css');?>"> 
    <script>
        var base_url = "<?php echo base_url();?>" ;
    </script>
</head>
<body>


    <?php
    if (!$files) {
        
    } else {
        foreach ($files as $key) {

        include ($key);

        }
    }
    ?>



    <script src="<?= base_url('assetsnew/js/jquery-2.1.0.min.js') ?>"></script>
    <script src="<?= base_url('assetsnew/js/jquery.swipebox.min.js') ?>"></script>   
    <script src="<?= base_url('assetsnew/js/materialize.min.js') ?>"></script> 
    <script src="<?= base_url('assetsnew/js/swiper.min.js') ?>"></script>  
    <script src="<?= base_url('assetsnew/js/jquery.mixitup.min.js') ?>"></script>
    <script src="<?= base_url('assetsnew/js/jquery.smoothState.min.js') ?>"></script>
    <script src="<?= base_url('assetsnew/js/masonry.min.js') ?>"></script>
    <script src="<?= base_url('assetsnew/js/chart.min.js') ?>"></script>
    <script src="<?= base_url('assetsnew/js/functions.js') ?>"></script>
    <script src="<?= base_url('assetsnew/js/sweetalert-dev.js');?>"></script>
</body>
</html>
<script>
    $(document).ready(function () {
        Macy.init({
          container: '#macy-container',
          trueOrder: false,
          waitForImages: false,
          margin: 24,
          columns: 3,
          breakAt: {
            1200: 5,
            940: 3,
            520: 2,
            400: 1
        }
    });

        $("#formkomen").submit(function (e) {
            e.preventDefault();
            var isiKomen = $("#isiKomen").val();
            console.log(isiKomen);
            var videoID = <?= $this->uri->segment(3) ?>;
            if (isiKomen=="") {
                $('#info .lengkapi').removeClass('hide');
                $('#info .sukses').addClass('hide');
                $('#info .gagal').addClass('hide');
            }else{
             $.ajax({
                type: "POST",
                url: '<?php echo base_url() ?>index.php/video/addkomen',
                data: {isiKomen: isiKomen, videoID: videoID},
                success: function (data)
                {
                    swal({   title: "Komen Berhasil ditambahkan",   
                     type: "info",   
                     showCancelButton: false,   
                     confirmButtonColor: "#8BDCF7",   
                     confirmButtonText: "Ok!",   
                     closeOnConfirm: false }, 
                     function(){   
                        window.location = '<?php echo base_url() ?>'+"video/seevideo/"+videoID;
                        ; });
                },
                error: function ()
                {
                    $('#info .lengkapi').removeClass('hide');
                    $('#info .sukses').addClass('hide');
                    $('#info .gagal').removeClass('hide');
                }
            }); 
         }

     });
    });
</script> 
   