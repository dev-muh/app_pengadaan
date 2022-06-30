  <div class="col-xs-4">
    <div class="form-group">
      <label for="qty">Barcode</label>
      <input onkeydown="ck_it()" onpaste="ck_it()" type="number" min="1" class="form-control" id="bc_item" req-add-item placeholder="Input code" value="<?php echo !empty($items) ? $items[0]->barcode : ''; ?>">
    </div>
  </div>
  <div class="col-xs-4">
    <div class="form-group">
      <label for="item_select">Select Item</label>
      <div class="input-group" style="width: 100%;">
        
        <select id="s_item" class="form-control" onchange="change_it($(this))">
          <?php if(!empty($items)){ ?>
                  <option disabled="disabled" selected="selected">--Select Item--</option>
              <?php foreach ($items as $key => $value) { ?>
                  <?php 
                    $val =  $value->id_item . '|' .
                            $value->barcode . '|' .
                            $value->item_name . '|' .
                            $value->qty;
                  ?>
                  <option value="<?php echo $val; ?>"><?php echo $value->item_name . ' - ' . $value->barcode; ?></option>
              <?php } ?>
          <?php } ?>
        </select>
      </div>
    </div>
  </div>
  <div class="col-xs-2">
    <div class="form-group">
      <label for="qty_item">Qty</label>
      <input onkeydown="enter_it(event)" type="number" min="1" class="form-control" id="qty_item" req-add-item placeholder="Qty">
    </div>
  </div>
  <div class="col-xs-2">
    <div class="form-group">
      <label for="">ACTION</label><br>
      <button onclick="enter_it(); " type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Add Item"><span class="glyphicon glyphicon-plus"></span></button>
    </div>
  </div>


  <script type="text/javascript">
    $('#bc_item').focus(function(){
        $('#bc_item').val('');
    });
  </script>
