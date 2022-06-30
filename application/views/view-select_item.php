
    <select id="item_select" onchange="ch_select($(this).val())">
      <?php if(!array_key_exists('status', $items)){?>
            <option selected="selected" disabled="disabled">Pilih Barang</option>
        <?php foreach ($items as $key => $value) { ?>
            <?php 
              $val =  $value->ID_ITEM . '|' .
                      $value->barcode . '|' .
                      $value->nama_item . '|' .
                      $value->qty . '|' .
                      $value->satuan;
            ?>
            <option value="<?php echo $val; ?>"><?php echo $value->nama_item; ?></option>
        <?php } ?>
      <?php } ?>
    </select>


<script>
  $(function(){
      $('#item_select').select2(opt_item);
      setTimeout(function(){
          $('#item_select').data('select2').open();
          $('.select2-dropdown.bigdrop').css('top','1px');
      },100);
  });
</script>

<style type="text/css">
  .select2-dropdown.bigdrop {
    z-index: 99999999999999;
  }
</style>