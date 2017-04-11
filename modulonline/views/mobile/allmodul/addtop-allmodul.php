<style type="text/css">
    /*.col-md-2{
        margin: 20px;
        padding: 0;
    }*/


.row{position: relative;}
.post-list{ 
    margin-bottom:20px;
}
div.list-item {
    border-bottom: 4px solid #7ad03a;
    margin: 25px 15px 2px;
    padding: 20px 12px;
    /*background-color:#F1F1F1;*/
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    min-height: 150px;
    /*width: 29%;*/
    float: left;
}
div.list-item p {
    margin: .5em 0;
    padding: 2px;
    font-size: 13px;
    line-height: 1.5;
}
.list-item a {
    text-decoration: none;
    padding-bottom: 2px;
    color: #0074a2;
    -webkit-transition-property: border,background,color;
    transition-property: border,background,color;-webkit-transition-duration: .05s;
    transition-duration: .05s;
    -webkit-transition-timing-function: ease-in-out;
    transition-timing-function: ease-in-out;
}
.list-item a:hover{text-decoration:underline;}
.list-item h2{font-size:25px; font-weight:bold;text-align: left;}

/* search & filter */
.post-search-panel input[type="text"]{
    width: 220px;
    height: 28px;
    color: #333;
    font-size: 16px;
}
.post-search-panel select{
    height: 34px;
    color: #333;
    font-size: 16px;
}

/* Pagination */
div.pagination {
    font-family: "Lucida Sans Unicode", "Lucida Grande", LucidaGrande, "Lucida Sans", Geneva, Verdana, sans-serif;
    padding:2px;
    margin: 20px 10px;
    float: right;
}

div.pagination a {
    margin: 2px;
    padding: 0.5em 0.64em 0.43em 0.64em;
    background-color: #FD1C5B;
    text-decoration: none; /* no underline */
    color: #fff;
}
div.pagination a:hover, div.pagination a:active {
    padding: 0.5em 0.64em 0.43em 0.64em;
    margin: 2px;
    background-color: #FD1C5B;
    color: #fff;
}
div.pagination span.current {
        padding: 0.5em 0.64em 0.43em 0.64em;
        margin: 2px;
        background-color: #f6efcc;
        color: #6d643c;
    }
div.pagination span.disabled {
        display:none;
    }
.pagination ul li{display: inline-block;}
.pagination ul li a.active{opacity: .5;}

/* loading */
.loading{position: absolute;left: 0; top: 0; right: 0; bottom: 0;z-index: 2;background: rgba(255,255,255,0.7);}
.loading .content {
    position: absolute;
    transform: translateY(-50%);
     -webkit-transform: translateY(-50%);
     -ms-transform: translateY(-50%);
    top: 50%;
    left: 0;
    right: 0;
    text-align: center;
    color: #555;
}

.clear{
    clear: both;
}

</style>
<script>
function searchFilter(page_num) {
    page_num = page_num?page_num:0;
    var keywords = $('#keywords').val();
    var sortBy = $('#sortBy').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>index.php/modulonline/ajaxPaginationData/'+page_num,
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy,
        beforeSend: function () {
            $('.loading').show();
        },
        success: function (html) {
            $('#postList').html(html);
            $('.loading').fadeOut("slow");
        }
    });
}
</script>