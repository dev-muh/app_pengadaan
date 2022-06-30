
<div class="konten" style="display: flow-root;">
<br>
<!-- <input type="hidden" id="id_bill" value="<?php echo $id_bill ?>"> -->
<input type="hidden" id="BASE_URL" value="<?php echo base_url(); ?>">
<script>
  //var mode_pos = '<?php //echo $mode; ?>';
  //var pos_type = '<?php //echo $pos_type; ?>';
</script>


<div class="container col-lg-8">
  <div class="row">
      <div class="col-lg-12 ">
          <div class="panel-group">
              <div class="panel panel-default">
                <div class="panel-heading">Select Customer<div class="btn btn-warning btn-sm pull-right btn-flat">X</div></div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-lg-6" >
                          <label class="radio-inline"><input id="walkincus" onclick="pilihCus(this)" class="walkincus" checked type="radio" name="optradio" value="walkincus">Walk in Customer</label>
                          <label class="radio-inline"><input id="guest" onclick="pilihCus(this)" class="guest" type="radio" name="optradio" value="guest">Guest</label>
                      </div>
                  </div>
                </div>
              </div>
          </div>

          <div class="panel-group">
              <div class="panel panel-default">
                <div class="panel-heading">Item List <p class="pull-right">Bill No : <b class="vBillNumber">-</b> </p><p class="pull-right" style="margin-right: 10px;"> Bill Date : <b class="vFullDate">-</b></p></div>
                  <div class="panel-body">
                    <form role="form" name="" class="" id="">
                      
                      <div class="form-group col-lg-4">
                        <label for="name" control-label">Item :</label>
                        <select  id="selectItem" class="form-control select2" >
                            <?php
                              foreach ($item as $item) {
                                    echo '<option value="'. 
                                    $item->ID_ITEM . '>'.
                                    $item->nama_item . '>' . 
                                    $item->selling_price . '>' .
                                    $item->barcode .
                                    '" ';
                                    echo '>'.$item->nama_item.' - '. $item->selling_price .'</option>';
                              }
                            ?>
                        </select>
                      </div> 
                      <div class="form-group col-lg-3">
                        <label for="barcode" control-label">Barcode :</label>
                        <input placeholder="Input atau Scan Barcode" type="number" class="form-control validate-number" id="barcode" name="barcode" placeholder="" onkeydown="sItem(this); enter(event);">
                      </div> 
                      <div class="form-group col-lg-2">
                        <label class="lbl-qty" for="qty" control-label>Qty * :</label>
                        <input  placeholder="Quantity" type="number" class="form-control validate-number" id="qty" name="qty" placeholder="" onkeydown="enter(event);">
                      </div> 
                      <div class="form-group col-lg-2">
                        <label for="disc" control-label >Disc (%) :</label>
                        <input placeholder="Discount (%)" type="number" class="form-control validate-number" id="disc" name="disc" placeholder="" >
                      </div> 
                      <div class="form-group col-lg-1">
                        <label for="act" control-label">ACTION</label>
                        <div class="btn btn-primary glyphicon glyphicon-plus" onclick="addItem()"></div>
                      </div> 


                      <table class="table table-hover tb_item">
                        <thead>
                          <tr>
                            <th>Item Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Disc %</th>
                            <th>Disc Amount</th>
                            <th>Nett</th>
                            <th>Act</th>
                          </tr>
                        </thead>
                        <tbody class="bd_item bd_tb_item">
                          
                        </tbody>
                      </table>

                      <div class="container col-lg-12">
                        <!-- <div class="row"> -->
                          <div class="col-lg-6 pull-left form-horizontal">
                            <!-- <div class="panel-group"> -->
                                <!-- <div class="panel panel-default"> -->
                                  <!-- <div class="panel-heading">Remark</div> -->
                                    <!-- <div class="panel-body"> -->
                                      <div class="row">
                                        <div class="form-group">
                                          <label for="comment">Remark Item :</label>
                                          <textarea placeholder="Input Remark For Item" class="form-control" rows="2" id="remark_item"></textarea>
                                        </div>
                                    </div>
                                  <!-- </div> -->
                                <!-- </div> -->
                            <!-- </div> -->
                          </div>
                        <!-- </div> -->
                        <!-- <div class="row"> -->
                            <div class="col-lg-4 pull-right form-horizontal">
                              <div class="form-group dempet">
                                <label for="name" class="col-sm-5 control-label">Total Bill :</label>
                                <label for="name" class="col-sm-7 control-label pull-left totalBill"></label>
                              </div> 
<!--                               <div class="form-group dempet">
                                <label for="tax" class="col-sm-5 control-label">Tax :</span></label>
                                <input type="text" name="tax" class="col-sm-5 tax pull-right" style="margin-top: 2%; margin-right: 5%; text-align: right;">
                              </div> -->


                              <div class="form-group">
                                <label for="ttlAll" class="col-sm-5 control-label">Total :</span></label>
                                <label for="ttlAll" class="col-sm-7 control-label ttlAll"></span></label>
                              </div>
                            </div>
                        </div>
                      <!-- </div> -->

                      
                    </form>
                  </div>
              </div>
          </div>


          <div class="panel-group">
              <div class="panel panel-default">
                <div class="panel-heading">Payment</div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="form-group col-lg-6">
                        <label for="name" control-label">Payment Status :</label>
                        <select  onchange="pilihPaymentStat(this)" id="payment_status" class="form-control pay_stat" >
                                <option style="display: none;" class="optPayStat_Room" value="ROOM" selected="">Charge in Room</option>
                                <option class="optPayStat_Direct" value="DIRECT" selected="">Direct</option>
                        </select>
                      </div>

                      <div class="form-group col-lg-4 pull-right">
                        <label for="paid" control-label">Paid :</label><br>
                        <input  type="checkbox" class="same_bill" > Same as Bill
                        <input placeholder="Input Paid" type="text" class="form-control paid validate-number" id="paid" name="paid" placeholder="" onclick="format(this)" onkeydown="sum(this)">
                      </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 dispResRoomHandle" style="display: none;">
                            <label for="usr">Select Guest :</label>
                            <select  id="res_room_number" class="form-control select2" >
                              <?php
                                foreach ($guest as $guest) {
                                      echo '<option value="'. 
                                      $guest->id_checkin . '>'.
                                      $guest->id_room . '>'.
                                      $guest->room_number . '>'.
                                      $guest->room_type . '>'.
                                      $guest->guest_name . '>'.
                                      $guest->guest_phone .
                                      '">'. $guest->room_number .' - '. $guest->guest_name.'</option>';
                                }
                              ?>
                            </select>

                            <style>
                              .select2 {
                                width:100%!important;
                              }
                            </style>

                        </div>
                    </div>

                    <div class="row">
                       
                      <div class="form-group pay_type col-lg-6">
                        <label for="name" control-label">Payment Type :</label>
                        <select  id="type_payment" class="form-control" > 
                            <?php
                              foreach ($pay_type as $pay) {
                                    echo '<option value="'. 
                                    $pay->id . '>' .
                                    $pay->code . '>' .
                                    $pay->description . '>' .
                                    '">'. $pay->description .'</option>';
                              }
                            ?>
                        </select>
                      </div> 

                      




                  </div>
                  <div class="row">
                      <div class="form-group col-lg-6">
                          <label for="comment">Remark Payment :</label>
                          <textarea placeholder="Input Remark For Payment (Ex:Credit Card Number)" class="form-control" rows="2" id="remark_payment"></textarea>
                      </div> 

                      <div class="col-lg-5 pull-right form-horizontal">
                        <div class="form-group dempet-banget">
                          <h4><label for="totalEnd" class="col-sm-5 control-label">Total :</label></h4>
                          <h4><label for="totalEnd" class="totalEnd col-sm-7 control-label pull-left">0</label></h4>
                        </div> 
                        <div class="form-group dempet-banget">
                          <h4><p for="paidEnd" class="col-sm-5 control-label">Paid :</p></h4>
                          <h4><p for="paidEnd" class="paidEnd col-sm-7 control-label">0</p></h4>
                        </div>
                        <div class="form-group">
                          <h4><label for="balance" class="col-sm-5 control-label pull left">Change :</span></label></h4>
                          <h4><label for="balance" class="balance col-sm-7 control-label">0</span></label></h4>
                        </div>
                      </div>
                  </div>
                </div>
                <div class="panel-footer" style="display:flow-root;">
                  <button disabled class="pay mleft btn btn-lg btn-primary pull-right" onclick="sendData(1)">PAY</button>
                  <button class="open_bill mleft btn btn-lg btn-success pull-right" onclick="sendData(0)">Open Bill</button>
                  <button class="reset mleft btn btn-lg btn-danger ">Reset</button>
                  
                </div>
              </div>
          </div>
      </div>
  </div>
</div>
</div>


<style type="text/css">
  .dempet{
    margin-bottom: 0px;
  }
  .dempet-banget{
    margin-bottom: -10px;
  }
  .dempet-keatas{
    margin-top:-20px;
  }
  .priceH{
    display: none;
  }
  .mleft{
    margin-left: 10px;
  }
  input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
</style>


<!-- Modal -->
  <div class="modal fade" id="viewBill" role="dialog">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Bill Number : <b class="c-bill_number"></b></h4>
        </div>
        <div class="modal-body">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="title">Payment Info <label class="pull-right">Date : <b class="date_input">-</b>
              </label></div>

            </div>
            <div class="panel-body">
              <ul class="list-group col-sm-12">
                <li class=" no_border">
                  <b class="col-sm-4">Payment From </b>
                  <b class="col-sm-8">: <b class="paid_with">Direct</b></b>
                </li>
                <li class=" no_border">
                  <b class="col-sm-4">Payment Type </b>
                  <b class="col-sm-8">: <b class="payment_type"></b></b>
                </li>
                <li class=" no_border">
                  <b class="col-sm-4">Room Number </b>
                  <b class="col-sm-8">: <b class="room_number">-</b></b>
                </li>     
                <li class=" no_border">
                  <b class="col-sm-4">Remark Payment </b>
                  <b class="col-sm-8">: <b class="remark_payment">-</b></b>
                </li>                                           
              </ul>
              <style type="text/css">
                .no_border{
                  border-top: 0 none;
                  border-bottom: 0 none;
                  list-style-type: none;
                }
              </style>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-body">
            <center style="margin-bottom:20px;"><h3><?php echo $page_title; ?></h3></center>
                <table id="table-item_bill" class="table" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Item</th>
                      <th>Qty</th>
                      <th>Price</th>
                      <th>Disc</th>
                      <th>Net</th>
                    </tr>
                  </thead>
                  <tbody class="tb_item_modal">
                  </tbody>
                </table>
            </div>
          </div> 
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="col-lg-6 pull-right">
              <div class="row">
                <div class="col-xs-12 col-lg-12 pull-right">
                  <div class="p_kanan col-xs-6 col-lg-6">Total</div>
                  <div class="total p_kanan bold col-xs-6 col-lg-6">0</div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-lg-12 pull-right">
                  <div class="p_kanan col-xs-6 col-lg-6">Paid</div>
                  <div class="paid p_kanan bold col-xs-6 col-lg-6">0</div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 col-lg-12 pull-right">
                  <div class="p_kanan col-xs-6 col-lg-6">Change</div>
                  <div class="balance p_kanan bold col-xs-6 col-lg-6">0</div>
                </div>
              </div>
              </div>

              <div class="form-group col-lg-6 pull-left">
                  <label for="comment">Remark :</label>
                  <label class="remark_item" style="font-weight: normal;">-</label>
                </div>
              <style type="text/css">
                .p_kanan{
                  text-align: right;
                }
                .bold{
                  font-weight: bold;
                }
              </style>
            </div>
          </div>     
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
          <a href="#" class="btn btn-success cetak_invoice">
            <span class="glyphicon glyphicon-print"></span> Cetak Invoice 
          </a>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="modal_delete" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <p>Remark</p>
          <textarea id="remark_del" style="width:100%;" rows="3" name="remark_del_item"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger delete_item">Delete It</button>
        </div>
      </div>
    </div>
  </div>
