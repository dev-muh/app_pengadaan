
<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Filter By</h3>
        </div>
        <div class="box-body">
          
          <div class="row">
            <div class="col-md-2"><label class="pull-right">PIC</label></div>
            <div class="col-md-4 form-group">
              <select class="form-control" id="id_user" onchange="exec_act($(this).val())">
                <?php foreach ($user_sla as $k => $v) { ?>
                    <option value="<?php echo $v->id; ?>|<?php echo $v->user_type; ?>"><?php echo $v->name; ?> - (<?php echo $v->user_type; ?>)</option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2"><label class="pull-right">Periode</label></div>
            <div class='col-md-3'>
                <div class="form-group">
                    <div class='input-group date' id='datetimepicker6'>
                        <input type='text' class="form-control" id="startper"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class='col-md-3'>
                <div class="form-group">
                    <div class='input-group date' id='datetimepicker7'>
                        <input type='text' class="form-control" id="endper" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2"><label class="pull-right">Service Level</label></div>
            <div class="col-md-4 form-group">
              <select class="form-control" id="sact" onchange="filter_range($(this).prop('selectedIndex'))">
                <option value="0">Waiting Approval</option>
                <option value="1">Order Received</option>
                <option value="2">Courier Assigned</option>
                <option value="3">Prepare Item</option>
                <option value="4">Courier On The Way</option>
                <option value="5">Done</option>
              </select>
            </div>

            <div class="col-md-4">
              <select class="form-control" id="eact">
                <option value="0">Waiting Approval</option>
                <option value="1">Order Received</option>
                <option value="2">Courier Assigned</option>
                <option value="3">Prepare Item</option>
                <option value="4">Courier On The Way</option>
                <option value="5">Done</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2"><label class="pull-right"></label></div>
            <div class="col-md-2 form-group">
              <button class="form-control btn btn-success" onclick="submit($(this))">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Service Level Report</h3>&nbsp;&nbsp;<i id="loading_ctrl" class="fa fa-refresh" style="display: none;"></i>
          <a href="javascript:void(0)" onclick="export_pdf_report_sla($(this))" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i> PDF</a>
          <a href="javascript:void(0)" onclick="export_excel_report_sla($(this))" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i> EXCEL</a>
        </div>
        <div class="box-body" id="tb_sla">


        </div>
      </div>
    </div>
</div>



