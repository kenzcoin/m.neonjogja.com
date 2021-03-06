<div class="page-content grid-row">

 <main>

  <div class="modal fade " tabindex="-1" role="dialog" id="myModal">
   <div class="modal-dialog" role="document">
    <div class="modal-content">
     <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Pilih Bab Yang Akan Kamu Tanyakan</h4>
     </div>
     <div class="modal-body">

      <form class="form-group" id="formlatihan" method="post" onsubmit="return false;">
       <div class="alert alert-dismissable alert-danger" id="info" hidden="true" >
        <button type="button" class="close" onclick="hideme()" >×</button>
        <strong>Terjadi Kesalahan</strong> <br>Silahkan Lengkapi Data.
       </div>

       <p class="has-success">
        <label>Matapelajaran</label>
        <select class="form-control" name="mapel" id="mapelSelect">
         <option value=0>-Pilih Matapelajaran-</option>
         <?php foreach ($mapel as $mapel_item): ?>
          <option value=<?=$mapel_item['tingpelID'] ?>><?=$mapel_item['napel'] ?></option>  
         <?php endforeach ?>
        </select>
       </p>

       <p class="has-success">
        <label>Bab</label>
        <select class="form-control" name="tingkat" id="babSelect"  ><option value=0>-Pilih Bab-</option></select>
       </p>

       <p class="has-success">
        <label >Sub Bab</label>
        <select class="form-control" name="subab" id="subSelect"  ><option value=0>-Pilih Sub-</option></select>                       
       </p>


       <div class="modal-footer bg-color-3">
        <button type="button" class="cws-button bt-color-1 border-radius alt small" data-dismiss="modal">Batal</button>
        <button type="button" class="cws-button bt-color-2 border-radius alt small buat-btn">Buat Pertanyaan</button>
       </div>

      </form>     
     </div>
    </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
  </div>



 </main>
 <br>

 <hr class="divider-color">

</div>

<script type="text/javascript">
 $('#mapelSelect').change(function () {
  var idMapel = $(this).val();
  load_bab(idMapel);
 });

 $('#babSelect').change(function () {
  var idbab = $(this).val();
  load_sub(idbab);
 });

    //fungsi untuk ngeload bab berdasarkan tingkat-pelajaran id
    function load_bab(tingPelId) {
     $('#babSelect').find('option').remove();
     $('#babSelect').append('<option value=0>Bab Pelajaran</option>');
     var babID;
     $.ajax({
      type: "POST",
      url: "<?php echo base_url() ?>index.php/matapelajaran/get_bab_by_tingpel_id/" + tingPelId,
      success: function (data) {
       $.each(data, function (i, data) {
        $('#babSelect').append("<option value='" + data.id + "'>" + data.judulBab + "</option>");
        load_sub(data.id);  
       });
      }
     });
    }

    // //fungsi untuk ngeload bab berdasarkan tingkat-pelajaran id
    function load_sub(babID) {
     var babID;
     $.ajax({
      type: "POST",
      url: "<?php echo base_url() ?>videoback/getSubbab/" + babID,
      success: function (data) {
       $('#subSelect').html('<option value=0>-- Pilih Sub Bab Pelajaran  --</option>');
       $.each(data, function (i, data) {
       console.log(data);
        $('#subSelect').append("<option value='" + data.id + "'>" + data.judulSubBab + "</option>");
       });
      }
     });
    }

    function mulai() {
      var mapel= $('#mapelSelect').val();
      var subab= $('#subSelect').val();
      var bab= $('#babSelect').val();
     if (mapel == 0 || subab == 0 || bab == 0) {
      $('#info').show();
     }else{
       $('.buat-btn').text('proses...');
       window.location = "<?php echo base_url() ?>konsultasi/bertanya/" + subab;
     }
  }



          function hideme(){
           $('#info').hide();
          }
          $('.buat-btn').click(function () {
           mulai();
          });
         </script>