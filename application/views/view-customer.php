
<div id="user_mng">
    <!-- Content Header (Page header) -->
    <input type="hidden" id="BASE_URL" value="<?php echo base_url(); ?>" >
    <!-- <input type="hidden" id="type_pos" value="<?php //echo $type; ?>"> -->
      <div class="row">

        <div class="col-md-12">
          <div id="table-wrapper">
              <center>
                <h2 ><?php echo $page_title; ?></h2>
                <hr style="border-top: 3px double #8c8b8b;">
                <?php //$this->load->view('tpl_form_message'); ?>
              </center>

              <br>


              <button type="button" id="addRow" class="btn btn-success pull-right" data-toggle="modal" data-target="#add-customer"><span class="glyphicon glyphicon-plus"></span>
                Tambah User
              </button>

              <button type="button" id="addRow" class="btn btn-info pull-right" data-toggle="modal" data-target="#import-customer" style="margin-right: 5px;"><span class="glyphicon glyphicon-plus"></span>
                Import CSV
              </button>
<!--               <button type="button" class="btn btn-success pull-right" data-toggle="popover"  data-title="Select Rule" data-placement="left" data-trigger="focus" onclick="select_rule($(this))"><span class="glyphicon glyphicon-plus"></span>
                Tambah User
              </button> -->
              <br><br>
              <table id="tblCustomer" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th class="" style="background-color: #4F81BD; color: white;">NO.</th>
                  <th class="" style="background-color: #4F81BD; color: white;">USERNAME</th>
                  <th class="" style="background-color: #4F81BD; color: white;">NAMA</th>
                  <th class="" style="background-color: #4F81BD; color: white;">EMAIL</th>
                  <th class="" style="background-color: #4F81BD; color: white;">RULES</th>
                   <th class="" style="background-color: #4F81BD; color: white;">DEPARTMENT</th>
                   <th class="" style="background-color: #4F81BD; color: white;">DIREKTORAT</th>
                  <th class="" style="background-color: #4F81BD; color: white;">JABATAN</th>
                  <th class="" style="background-color: #4F81BD; color: white;">GROUP</th>
                  <th class="" style="background-color: #4F81BD; color: white;">LANTAI</th>
                  <th class="" style="background-color: #4F81BD; color: white;">STATUS</th>
<!--                   <th class="" style="background-color: #4F81BD; color: white;">UPD. BY</th>
                  <th class="" style="background-color: #4F81BD; color: white;">UPD. DATE</th> -->
                  <th class="" style="background-color: #4F81BD; color: white;">ACTION</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 1;
                    if(!empty($tb_customer) && $tb_customer !== NULL){
                      
                      foreach ($tb_customer as $tb) {
                        echo '<tr>
                              <td>'.$no.'</td>
                             
                              <td>'.$tb->username.'</td>
                              <td>'.$tb->name.'</td>
                              <td>'.$tb->email.'</td>
                              <td>'.$tb->user_type.'</td>
                              <td>'.$tb->department.'</td>
                              <td>'.$tb->direktorat.'</td>
                              <td>'.$tb->jabatan.'</td>
                             
                              <td>'.$tb->group_name.'</td>
                              <td>'.$tb->lantai.'</td>';

                              if($tb->is_active==1){
                                  echo '<td><p class="btn bt-sm btn-success btn-sm" data-toggle="tooltip" title="Status User : Active">Active</p></td>';
                              }
                              if($tb->is_active==0){
                                  echo '<td><p class="btn bt-sm btn-warning btn-sm" data-toggle="tooltip" title="Status User : Active">Non Active</p></td>';
                              }

                        echo 
                              '<!--<td>'.$tb->update_by_username.'</td>
                              <td>'.$tb->update_date.'</td>-->
                              
                              <td width="20%">';

                              if($tb->is_active==0){
                                echo '<button data-toggle="tooltip" title="Activate User" class="btn btn-primary btn-sm" onclick="activate('.$tb->id.')"><span class="glyphicon glyphicon-check"></span></button>&nbsp;';
                              }

                              if($tb->is_active==1){
                                echo '<button data-toggle="tooltip" title="Deactivate User" class="btn btn-danger btn-sm" onclick="deactivate('.$tb->id.')"><span class="glyphicon glyphicon-glyphicon glyphicon-remove-sign"></span></button>&nbsp;';
                              }

                                echo '<button data-toggle="tooltip" title="Ubah" class="btn btn-warning btn-sm" onclick="edit($(this),'. $tb->id .')" value="'
                                      .$tb->id.'|'
                                      .$tb->username.'|'
                                      .$tb->name.'|'
                                      .$tb->password.'|'
                                      .$tb->user_type.'|'
                                      .$tb->no_pegawai .'|'
                                      .$tb->department .'|'
                                      .$tb->jabatan . '|'
                                      .$tb->email . '|'
                                      .$tb->group_id . '|'
                                      .$tb->lantai . '|'
                                      .$tb->is_active. '|'
                                      .$tb->direktorat.
                                      '"><span class="glyphicon glyphicon-edit"></span></button>';
                                  if($tb->user_type!='Karyawan'){
                                      echo ' <button data-toggle="tooltip" title="Reset Password" class="btn btn-default btn-sm" onclick="reset('.$tb->id.')"><span class="glyphicon glyphicon-refresh"></span></button>';
                                  }
                                  echo ' <button data-toggle="tooltip" title="Hapus" class="btn btn-danger btn-sm" onclick="del('.$tb->id.')"><span class="glyphicon glyphicon-trash"></span></button>
                              </td>
                            </tr>';
                        $no++;
                      }
                    }

                  ?>
                </tbody>
              </table>
              <?php echo '<input type="hidden" class="cUser" value="'. $no .'">'; ?>
          </div>
        </div>
      </div>

      <div class="modal modal-success fade" id="add-customer" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add User</h4>
              </div>
              <div class="modal-body">
                <form id="frm-customer" autocomplete="off">
                  <input type="hidden" id="id_customer" name="id" value=""/>

                  <div class="pr v_username row" style="display: none">
                    <label for="u_username" class="col-sm-4 control-label">Username <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="text" class="n-kr kr form-control" id="u_username" name="username" placeholder="Isikan Username" value="" required>
                    </div>
                  </div>  
                  

                  <div class="pr v_password row" style="display: none">
                    <label for="u_password" class="col-sm-4 control-label">Password <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="u_password" name="password" placeholder="Isikan Password" value="" required>
                    </div>
                  </div>  
                  

                  <div class="pr v_nama row" style="display: none">
                    <label for="u_nama" class="col-sm-4 control-label">Nama <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="text" class="n-kr kr form-control" id="u_nama" name="name" placeholder="Isikan Nama" value="" required>
                    </div>
                  </div>  

                  <div class="pr v_email row" style="display: none">
                    <label for="u_email" class="col-sm-4 control-label">Email <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="email" class="kr form-control" id="u_email" name="email" placeholder="Isikan Email" value="">
                    </div>
                  </div>  
                  

                  <div class="pr v_no_peg row" style="display: none">
                    <label for="u_no_peg" class="col-sm-4 control-label">Nomor Pegawai <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="u_no_peg" name="no_pegawai" placeholder="Isikan Nomor Pegawai" value="">
                    </div>
                  </div>  
                  

                  <div class="pr v_jabatan row" style="display: none">
                    <label for="u_jabatan" class="col-sm-4 control-label">Jabatan <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="text" class="n-kr form-control" id="u_jabatan" name="jabatan" placeholder="Isikan Jabatan" value="">
                    </div>
                  </div>  
                  

                  <div class="pr v_department row" style="display: none">
                    <label for="u_department" class="col-sm-4 control-label">Department <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="u_department" name="department" placeholder="Isikan Department" value="">
                    </div>
                  </div>  

                  <div class="pr v_direktorat row" style="display: none">
                    <label for="u_direktorat" class="col-sm-4 control-label">Direktorat <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="u_direktorat" name="direktorat" placeholder="Isikan Direktorat" value="">
                    </div>
                  </div>  

<!--                   <div class="pr v_group row" style="display: none">
                    <label for="u_group" class="col-sm-4 control-label">Group <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="u_group" name="group" placeholder="Isikan Group" value="" required>
                    </div>
                  </div>  -->

                  <div class="v_group row pr" style="display: none">
                    <label for="u_group" class="col-sm-4 control-label">Group<span class="asterisk">*</span></label>

                    <div class="col-sm-7 v_group">
                        <select  id="u_group" class="n-kr kr form-control pr select2 act col-sm-12" required name="group">
                          <option disabled selected>-- SELECT GROUP --</option>
                          <?php foreach ($ls_group as $key => $value) { ?>
                              <option value="<?php echo $value->id; ?>"><?php echo $value->group_name; ?></option>
                          <?php } ?>
                        </select>
                      
                    </div>
                  </div>  
                  <br>

                  <div class="v_lantai row" style="display: none">
                    <label for="u_lantai" class="col-sm-4 control-label">Lantai <span class="asterisk">*</span></label>

                    <div class="col-sm-7 v_group">
                      <select  id="u_lantai" class="n-kr kr form-control select2 act col-sm-12" required name="lantai">
                        <option disabled selected>-- SELECT FLOOR --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                    </div>
                  </div> 
                  

                  <div class="v_privilage row" style="display: inline">
                    <label for="u_privilege" class="col-sm-4 control-label">Rules<span class="asterisk">*</span></label>

                    <div class="col-sm-7">
                        <select  id="u_privilege" class="n-kr kr form-control col-sm-12" name="user_type" required onchange="v_form($(this).val())">
                          <option disabled selected>-- SELECT RULE --</option>
                          <!-- <option value="Super Admin" >Super Admin</option> -->
                          <!-- <option value="Admin">Admin</option> -->
                          <option value="Admin TOFAP" >Admin TOFAP</option>
                          <!-- <option value="Admin Pengadaan">Admin Pengadaan</option> -->
                          <!--  <option value="Admin ATK">Admin ATK</option> -->
                          <!-- <option value="Admin Pemesanan">Admin Pemesanan</option> -->
                          <!-- <option value="Admin Penerimaan">Admin Penerimaan</option> -->
                          <option value="Admin Gudang">Admin Gudang</option>
                          <option value="Approval">Approval</option>
                          <option value="Karyawan">Karyawan</option>
                          <option value="Kurir">Kurir</option>
                        </select>
                      
                    </div>
                  </div>  
                  <br>

                  <div class="v_active row pr" style="display: none">
                      <label for="u_active" class="col-sm-4 control-label">Active<span class="asterisk">*</span></label>
                      <div class="col-sm-7">
                         <select  id="u_active" class="form-control col-sm-12" name="is_active" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                         </select>
                      </div>
                  </div>



              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                <button type="submit" id="u_save" class="btn btn-outline">Save</button>
              </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal modal-info fade" id="import-customer" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Import User</h4>
              </div>
              <form id="frm-import-customer" autocomplete="off" onsubmit="return false;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-8 col-sm-offset-4">
                    <a class="btn btn-default" href="<?= base_url('assets/karyawan_tofap_template.csv') ?>">Download Template</a>
                  </div>
                </div>
                <br>
                 <div class="row">
                    <label for="file-csv" class="col-sm-4 control-label">File CSV <span class="asterisk">*</span></label>

                    <div class="col-sm-8">
                      <input type="file" class="form-control" id="file-csv" name="file_csv" required>
                    </div>
                  </div>  
                  <br>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                <button type="submit" id="u_save" class="btn btn-outline">Import</button>
              </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>



        <ul id="pop_select_rule" class="dropdown-menu" style="display: none;">
          <input type="hidden" name="id_pem" class="id_pem">
          <button class="btn bg-aqua btn-block" type="button" onclick="adm_ch_stat(String($(this).parent().find('.id_pem').val()),String(3))">Prepare Item</button>
          <button class="btn btn-primary btn-block" type="button" onclick="adm_ch_stat(String($(this).parent().find('.id_pem').val()),String(4))">Courier On The Way</button>
          <button class="btn btn-success btn-block" type="button" onclick="adm_ch_stat(String($(this).parent().find('.id_pem').val()),String(5))">Done</button>

        </div> 
        </ul>
    <!-- /.content -->

    <style>
      #u_password{
        -webkit-text-security:square;
      }
    </style>

</div>



