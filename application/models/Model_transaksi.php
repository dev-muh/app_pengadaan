<?php
class Model_transaksi extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $session_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
  }
  public function index()
  {
  }

  public function nomor_keranjang($no = 'nomor_order', $format = 'TFPABL')
  {
    try {
      $nomor       = '';
      $q           = $this->db->query("SELECT * FROM " . $no . " order by id desc limit 1");
      $array_bulan = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

      if ($q->num_rows() > 0) {

        $kd    = $q->row()->nomor;
        $th_h  = $q->row()->tahun;
        $th    = date('Y');
        $bln   = date('n');
        $bln_n = $array_bulan[date('n')];

        $kd++;
        $kd = sprintf("%07s", $kd);

        if (date('Y') > $th_h) {
          // $nomor = '001'.$format.$bln_n.'/'.$th;
          $nomor = $format . substr($th, 2, 2) . '0000001';
          $this->db->insert($no, array('nomor' => '0000001', 'bulan' => $bln, 'tahun' => $th));
        } else {
          // $nomor = $kd.$format.$bln_n.'/'.$th;
          $nomor = $format . substr($th, 2, 2) . $kd;
          $this->db->insert($no, array('nomor' => $kd, 'bulan' => $bln, 'tahun' => $th));
        }

      } else {
        // $nomor = '001'.$format.$array_bulan[date('n')].'/'.date('Y');
        $nomor = $format . substr(date('Y'), 2, 2) . '0000001';
        $this->db->insert($no, array('nomor' => '0000001', 'bulan' => date('n'), 'tahun' => date('Y')));
      }
    } catch (Exception $e) {

    } finally {
      return $nomor;
    }
  }
  public function user_type($id = null)
  {
    $query = $this->db->query('select * from user where id=' . $id);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }
  public function tb_pengajuan($id = null, $sort_by = null)
  {
    $ex = null;
    if (!empty($id)) {
      $ex = 'and p.id=' . $id;
    }

    if (!empty($sort_by)) {
      $order = $sort_by;
    } else {
      $order = 'id';
    }
    $query = $this->db->query('select 	p.id,p.no_pengajuan,
										p.id_project,
										p.judul,
										p.tgl_pengajuan,
										p.`status`,
										u.username,
										u.user_type,
										p.stat_penerimaan,
										p.stat_spb,
										(select upj.name from pengajuan as pj join user as upj where pj.submiter=upj.id and pj.id=p.id) as submiter,
										(select upj.name from pengajuan as pj join user as upj where pj.approval=upj.id and pj.id=p.id) as approval,
										(select upj.name from pengajuan as pj join user as upj where pj.receiver=upj.id and pj.id=p.id) as receiver,
										p.submit_date,
										p.approve_date,
										p.receive_date
								from pengajuan as p
										join user as u
								where p.is_delete="0" ' . $ex . '
										and p.update_by=u.id order by p.' . $order . ' desc'
    );

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function list_item_pengajuan($id = null)
  {

    $query = $this->db->query('	select 	p.id,
											p.no_pengajuan,
											p.judul,
											p.tgl_pengajuan,
											p.`status`,
											p.`status` as status_permintaan,
											p.stat_spb,
											p.stat_penerimaan,
											p.submiter,
											p.submit_date,
											(select name as submiter_name from user where id=p.submiter) as submiter_name,
											(select username as submiter_username from user where id=p.submiter) as submiter_username,
											p.approval,
											p.approve_date,
											(select name as approval_name from user where id=p.approval) as approval_name,
											(select user_type as approval_rule from user where id=p.approval) as approval_rule,
											(select jabatan as approval_jabatan from user where id=p.approval) as approval_jabatan,
											ip.qty,
											ip.qty_masuk,
											ip.qty_spb,
											p.update_by,
											p.update_date,
											ip.id as id_it_pn,
											i.item_name,
											i.min_qty,
											i.max_qty,
											i.barcode,
											i.id as id_item,
											ip.h_stock,
											ip.update_date as update_it_date,
											i.satuan,
											u_p.user_type as submiter_rule,
											u_p.jabatan as submiter_jabatan


									from pengajuan as p
											join item_pengajuan as ip
											join pos_item as i
											join user as u_p on p.submiter=u_p.id

									where p.id=' . $id . ' and ip.id_pengajuan=p.id and ip.id_item=i.id and ip.is_delete=0');

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function list_item_pengajuan_edit($id = null)
  {
    $query = $this->db->query('	select 	p.id,p.no_pengajuan,
												p.judul,
												p.tgl_pengajuan,
												p.`status`,
												ip.qty,
												i.min_qty,
												i.max_qty,
												p.update_by,
												p.update_date,
												ip.id as ID_IT_PENGAJUAN,
												i.id as ID_ITEM,
												i.item_name,
												i.barcode,
												ip.h_stock,
												i.qty as i_qty

									from pengajuan as p
											join item_pengajuan as ip
											join pos_item as i

									where p.id=' . $id . ' and ip.id_pengajuan=p.id and ip.id_item=i.id and ip.is_delete=0');

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function trx_pengajuan()
  {
    $id    = $this->input->post('id');
    $mode  = $this->input->post('mode');
    $judul = $this->input->post('judul');
    $nomor = $this->input->post('nomor');
    //$id_submiter = $_SESSION['id_user'];
    $items      = $this->input->post('item');
    $session_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';

    if ($mode == 'add') {
      try {
        $this->db->insert('pengajuan', array(
          'no_pengajuan' => $nomor,
          'judul'        => $judul,
          'submiter'     => $session_id,
          'insert_by'    => $session_id,
          'update_by'    => $session_id,
        )
        );
      } catch (Exception $e) {

      } finally {
        foreach ($items as $key => $value) {
          $items[$key]['id_pengajuan'] = $this->db->insert_id();
          $items[$key]['insert_by']    = $session_id;
          $items[$key]['update_by']    = $session_id;
        }

        $this->db->insert_batch('item_pengajuan', $items);
      }
    }

    if ($mode == 'edit') {

      try {
        $tgl = '';
        $qq  = $this->db->query('select current_timestamp as tanggal');

        if ($qq->num_rows() > 0) {
          $tgl = $qq->row()->tanggal;

          $data = array(
            'judul'       => $judul,
            'update_by'   => $session_id,
            'update_date' => $tgl,
          );
        } else {
          $data = array(
            'judul'     => $judul,
            'update_by' => $session_id,

          );
        }

        $this->db->where('id', $id);
        $this->db->update('pengajuan', $data);
      } catch (Exception $e) {

      } finally {
        foreach ($items as $key => $value) {
          try {
            $items[$key]['id_pengajuan'] = $id;
            $items[$key]['insert_by']    = $session_id;
            $items[$key]['update_by']    = $session_id;
          } catch (Exception $e) {

          } finally {

            $this->db->query('insert into item_pengajuan (id,id_pengajuan,id_item,qty,h_stock,insert_by,update_by)
							values("' . $items[$key]['id'] . '",'
              . $items[$key]['id_pengajuan'] . ','
              . $items[$key]['id_item'] . ','
              . $items[$key]['qty'] . ','
              . $items[$key]['h_stock'] . ','
              . $items[$key]['insert_by'] . ','
              . $items[$key]['update_by'] . ') on duplicate key update qty=' . $items[$key]['qty'] . ',h_stock=' . $items[$key]['h_stock']);
          }
        }

        // $this->db->replace_batch('item_pengajuan',$items);

      }
    }
  }

  public function tb_pemesanan($id = null, $id_pemesan = null, $id_kurir = null, $ids = [], $tahun = null, $report = null, $ex_param1 = null, $ex_param2 = null)
  {
    // print_r($ids);
    $ex = null;
    if (!empty($id)) {
      $ex = 'and p.id=' . $id;
    }
    $pemesan = null;
    if (!empty($id_pemesan)) {
      $pemesan = 'and p.id_pemesan=' . $id_pemesan;
    }
    $kurir = null;
    if (!empty($id_kurir)) {
      $kurir = 'and p.`status`>0 and (isnull(p.id_kurir) or p.id_kurir=' . $id_kurir . ')';
    }

    $idList = null;
    if (!empty($ids)) {
      $idList = "and p.id IN (" . join(",", $ids) . ")";
    }

    if (empty($tahun)) {
      $tahun = date('Y');
    }

    if (empty($report)) {
      $report = 'limit 100';
    } else {
      $report = '';
    }

    $sql = 'select * from (select
										p.id as id_pemesanan,
										u_p.name as pemesan,
										u_p.id as id_pemesan,
										p.no_pemesanan,
										p.tgl_pemesanan,
										g.group_name,
										p.lantai,
										p.`status`,
										(select u.id from user as u where u.id=p.id_kurir) as id_kurir,
										(select u.name from user as u where u.id=p.id_kurir) as kurir,
										(select r.rate from rating as r where r.id_pemesanan=p.id) as rating,
										(select concat("Kerapihan:", rating_kerapihan, " Kesopanan:", rating_kesopanan, " Kebersihan:", rating_kebersihan) from rating_gudang as r where r.id_pemesanan=p.id) as rating_gudang,
										(select r.comment from rating_gudang as r where r.id_pemesanan=p.id) as komentar,
										u_u.user_type,
										u_u.username,
										u_u.name as update_by
								from pemesanan as p
										join user as u_u
										join user as u_p
										join `group` as g on p.`group`=g.id
								where p.is_delete="0"
										' . $ex . '
										' . $pemesan . '
										' . $kurir . '
										' . $idList . '
										' . $ex_param1 . '
										and p.update_by=u_u.id
										and p.id_pemesan=u_p.id
										and year(p.tgl_pemesanan)=' . $tahun . '
								order by
										p.tgl_pemesanan desc ' . $report . ') as a ' . $ex_param2;

    // echo "$sql"; exit;
    $query = $this->db->query($sql);

    // print_r($sql);exit;

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function notif_pemesanan($id = null, $id_pemesan = null, $id_kurir = null)
  {
    $ex = null;
    if (!empty($id)) {
      $ex = 'and p.id=' . $id;
    }
    $pemesan = null;
    if (!empty($id_pemesan)) {
      $pemesan = 'and p.id_pemesan=' . $id_pemesan;
    }
    $kurir = null;
    if (!empty($id_kurir)) {
      $kurir = 'and p.`status`>0 and (isnull(p.id_kurir) or p.id_kurir=' . $id_kurir . ')';
    }

    $query = $this->db->query('select
										p.id as id_pemesanan,
										u_p.name as pemesan,
										u_p.id as id_pemesan,
										p.no_pemesanan,
										p.tgl_pemesanan,
										p.group,
										p.lantai,
										p.`status`,
										(select u.id from user as u where u.id=p.id_kurir) as id_kurir,
										(select u.name from user as u where u.id=p.id_kurir) as kurir,
										(select r.rate from rating as r where r.id_pemesanan=p.id) as rating,
										(select r.comment from rating as r where r.id_pemesanan=p.id) as komentar,
										u_u.user_type,
										u_u.username,
										u_u.name as update_by
								from pemesanan as p
										join user as u_u
										join user as u_p
								where p.is_delete="0"
										' . $ex . '
										' . $pemesan . '
										' . $kurir . '
										and p.`status`=5
										and p.update_by=u_u.id
										and p.id_pemesan=u_p.id order by p.tgl_pemesanan desc');

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function stock_now($id = null)
  {
    $q_item = $this->db->get_where('pos_item', array('id' => $id));
    return $q_item->row()->qty;
  }

  public function trx_pemesanan($is_mobile = null, $id_p = null)
  {
    //echo $this->input->post('nomor');
    $id         = $this->input->post('id');
    $id_pemesan = $this->input->post('id_pemesan');
    $id_user    = $this->input->post('id_user');
    $source     = !empty($_POST['device']) ? $_POST['device'] : 'WEB';
    $device     = 'WEB';
    if ($is_mobile == 'mobile' && $source == 'IOS') {
      $device = 'IOS';
    }
    if ($is_mobile == 'mobile' && $source == 'WEB') {
      $device = 'ANDROID';
    }
    if ($is_mobile == null && $source == 'WEB') {
      $device = 'WEB';
    }

    // $rating = $this->getCRating($id_pemesan)[0];

    // if($_POST['mode']=='add'){
    //   if($rating->pem_process==0 && $rating->jml_rate==0){

    //   }else{
    //     echo '{"status":"-5","message":"Error : Masih terdapat pesanan yang belum selesai / pesanan sebelumnya belum diberi rating."}';
    //     return false;
    //   }
    // }

    // if($is_mobile=='mobile'){
    //   $id_pemesan = $id_p;
    // }

    $count_item              = count($_POST['item']);
    $msg_filter_stock        = [];
    $msg_filter_stock_string = '';

    foreach ($_POST['item'] as $k_stock => $v_stock) {
      $ck_stock = $this->db->query("select qty as stock,item_name from pos_item where id=" . $v_stock['id_item']);

      if ($ck_stock->num_rows() > 0) {
        $r_stock = $ck_stock->row()->stock;
        if ($v_stock['qty'] <= $r_stock) {
          $count_item -= 1;
        } else {
          array_push($msg_filter_stock, array(
            'id'    => $v_stock['id_item'],
            'qty'   => $v_stock['qty'],
            'stock' => $r_stock,
          ));
          $msg_filter_stock_string .= '- Jumlah permintaan item ' . $ck_stock->row()->item_name . ' (' . $v_stock['qty'] . ') melebihi jumlah stock (' . $r_stock . ')<br>';
        }
      }
    }

    if ($count_item == 0) {
      if (!empty($id_pemesan)) {
        $group;
        $lantai;

        $q_user = $this->db->query("	select
											(SELECT IFNULL(NULL, (select `group` from user where id=u.id)) as `Group`) as `group` ,
											(SELECT IFNULL(NULL, (select `lantai` from user where id=u.id)) as `lantai`) as `lantai`

									from
											user as u where u.id=" . $id_pemesan);

        if ($q_user->num_rows() > 0) {
          $group  = $q_user->row()->group;
          $lantai = $q_user->row()->lantai;
        } else {
          echo '{"status":"-3","message":"User tidak ditemukan"}';
          return false;
        }

        $mode = $this->input->post('mode');

        if ($is_mobile == null) {

          if ($mode == 'add') {
            $nomor          = $this->nomor_keranjang();
            $id_nomor_order = $this->db->insert_id();

          }
          if ($mode == 'edit') {
            if (!empty($this->input->post('nomor'))) {
              $nomor = $this->input->post('nomor');
            } else {
              echo '{"status":"-2","message":"Error : Nomor Pemesanan Kosong."}';
              return false;
            }
          }

        } else {
          if ($is_mobile == 'mobile') {
            if ($mode != 'edit') {
              $nomor          = $this->nomor_keranjang();
              $id_nomor_order = $this->db->insert_id();
            }
          }
          if ($is_mobile != 'mobile' && $is_mobile != null) {
            echo '{"status":"-2","message":"Error : Nomor Pemesanan Kosong."}';
            return false;
          }
        }
        $items    = $this->input->post('item');
        $list_del = $this->input->post('list_del');
        // print_r($items);

        $session_id;
        if (!empty($id_pemesan) && $is_mobile == 'mobile') {
          $session_id = $id_pemesan;
        } else {
          $session_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
        }

        $mod_db   = 0;
        $mod_item = 0;

        try {
          if ($mode == 'add') {

            try {
              $data = array(
                'id_pemesan'   => $id_pemesan,
                'no_pemesanan' => $nomor,
                'group'        => $group,
                'lantai'       => $lantai,
                'source'       => $device,
                'insert_by'    => $session_id,
                'update_by'    => $session_id,
              );
              $mod_db = $this->db->insert('pemesanan', $data);

              $insert_id_pemesanan = $this->db->insert_id();
            } catch (Exception $e) {
              echo $e;
            } finally {
              if (!empty($items)) {
                foreach ($items as $key => $value) {
                  $items[$key]['id_pemesanan'] = $insert_id_pemesanan;
                  $items[$key]['h_stock']      = $this->stock_now($items[$key]['id_item']);
                  // echo $this->stock_now($items[$key]['id_item']);
                  $items[$key]['insert_by'] = $session_id;
                  $items[$key]['update_by'] = $session_id;
                }

                // print_r($items);

                $mod_item = $this->db->insert_batch('item_pemesanan', $items);

              }
            }

          }

          if ($mode == 'edit') {
            try {
              $data = array(
                //'judul'=>$judul,
                'id_pemesan' => $id_pemesan,
                'group'      => $group,
                'lantai'     => $lantai,
                'source'     => $device,
                'update_by'  => $session_id,
              );

              $this->db->where('id', $id);
              $mod_db = $this->db->update('pemesanan', $data);

            } catch (Exception $e) {

            } finally {
              if (!empty($items)) {
                foreach ($items as $key => $value) {
                  try {
                    $items[$key]['id_pemesanan'] = $id;
                    $items[$key]['insert_by']    = $session_id;
                    $items[$key]['update_by']    = $session_id;
                  } catch (Exception $e) {

                  } finally {

                    $id_i = '""';
                    if (!empty($items[$key]['id'])) {
                      $id_i = $items[$key]['id'];
                    }

                    $qty_it = $this->db->where('id', $items[$key]['id_item'])->select('qty')->get('pos_item');
                    if ($qty_it->num_rows() > 0) {
                      $his_stock = $qty_it->result()[0]->qty;
                      // print_r($his_stock);
                      $q = 'insert into item_pemesanan (id,id_pemesanan,id_item,qty,h_stock,insert_by,note,update_by)
												values(' . $id_i . ','
                        . $items[$key]['id_pemesanan'] . ','
                        . $items[$key]['id_item'] . ','
                        . $items[$key]['qty'] . ','
                        . $his_stock . ','
                        . $items[$key]['insert_by'] . ',"'
                        . $items[$key]['note'] . '",'
                        . $items[$key]['update_by'] . ') on duplicate key update qty=' . $items[$key]['qty'] . ',
															note="' . $items[$key]['note'] . '",
															h_stock=' . $his_stock;
                      $this->penyesuaian_edit_stock_item($items[$key]);
                      $mod_item += $this->db->query($q);
                      // echo $q;
                    } else {
                      echo '{"status":"-3","message":"Error Menambah Pemesanan (Item Tidak Ada)"}';
                      return false;
                    }

                  }
                }
              }

              if (!empty($list_del)) {
                foreach ($list_del as $key => $value) {
                  $this->db->where('id', $value);
                  $this->db->update('item_pemesanan', array('is_delete' => 1));
                }
              }

              // $this->db->replace_batch('item_pengajuan',$items);

            }
          }
        } catch (Exception $e) {

        } finally {
          if (!empty($id_pemesan)) {

            if ($mode == 'add') {
              if ($mod_db == 1 && $mod_item > 0) {
                echo '{"status":"1","message":"Sukses menambah pemesanan"}';

                try {
                  if (empty($is_mobile)) {
                    $this->db->insert('log_keranjang', array(
                      'insert_by'    => $_SESSION['id_user'],
                      'id_keranjang' => $insert_id_pemesanan,
                      'status'       => 0,
                      'status_text'  => 'Waiting Approval',
                      'message'      => 'User dengan ID ' . $_SESSION['id_user'] . ' dengan nama ' . $_SESSION['name'] . ' membuat pemesanan keranjang untuk USER_ID ' . $id_pemesan . ' dengan nama ' . $_SESSION['name'] . ' dengan ID Pemesanan ' . $insert_id_pemesanan,
                    ));
                  } else {

                    $res_user  = $this->db->get_where('user', array('id' => $id_pemesan));
                    $name_user = $res_user->row()->name;

                    $this->db->insert('log_keranjang', array(
                      'insert_by'    => $id_pemesan,
                      'id_keranjang' => $insert_id_pemesanan,
                      'status'       => 0,
                      'status_text'  => 'Waiting Approval',
                      'message'      => 'User dengan ID ' . $id_pemesan . ' dengan nama ' . $name_user . ' membuat pemesanan keranjang untuk USER_ID ' . $id_pemesan . ' dengan nama ' . $name_user . ' dengan ID Pemesanan ' . $insert_id_pemesanan,

                    ));
                  }
                } catch (Exception $e) {

                }

                $this->load->model('model_mobile');
                $dt_pem = $this->db->get_where('pemesanan', array('id' => $insert_id_pemesanan));
                $this->model_mobile->send_notif($id_pemesan, 'TOFAP', 'Pesanan anda telah dibuat dengan nomor pengambilan ' . $dt_pem->row()->no_pemesanan);
              } else {
                echo '{"status":"-1","message":"Error Menambah Pemesanan (Item Kosong)"}';
                $this->db->delete('pemesanan', array('id' => $insert_id_pemesanan));
                $this->db->delete('nomor_order', array('id' => $id_nomor_order));
              }
            }
            if ($mode == 'edit') {
              if ($mod_db == 1 && $mod_item > 0) {
                echo '{"status":"1","message":"Sukses Mengubah Pemesanan"}';

                try {
                  if (empty($is_mobile)) {
                    $this->db->insert('log_keranjang', array(
                      'insert_by'    => $_SESSION['id_user'],
                      'id_keranjang' => $id,
                      'status'       => 0,
                      'status_text'  => 'Waiting Approval',
                      'message'      => 'User dengan ID ' . $_SESSION['id_user'] . ' dengan nama ' . $_SESSION['name'] . ' membuat pemesanan keranjang untuk USER_ID ' . $id_pemesan . ' dengan nama ' . $_SESSION['name'] . ' dengan ID Pemesanan ' . $id,
                    ));
                  } else {
                    $res_user  = $this->db->get_where('user', array('id' => $id_pemesan));
                    $name_user = $res_user->row()->name;

                    $this->db->insert('log_keranjang', array(
                      'insert_by'    => $id_pemesan,
                      'id_keranjang' => $id,
                      'status'       => 0,
                      'status_text'  => 'Waiting Approval',
                      'message'      => 'User dengan ID ' . $id_pemesan . ' dengan nama ' . $name_user . ' mengubah pemesanan(Keranjang) untuk USER_ID ' . $id_pemesan . ' dengan nama ' . $name_user . ' dengan ID Pemesanan ' . $id,

                    ));
                  }
                  // $this->update_stock_data($id);
                } catch (Exception $e) {

                }
              } else {
                echo '{"status":"-1","message":"Error Mengubah Pemesanan (Item Kosong)"}';
              }
            }
          }
        }
      } else {
        echo '{"status":"-4","message":"ID User belum diisi"}';
        return false;
      }
    } else {
      echo '{
				"status":"-10",
				"message":"' . $msg_filter_stock_string . '"
			}';
    }

  }

  protected function penyesuaian_edit_stock_item($item_pemesanan)
  {
    // print_r($item_pemesanan);
    // Cek Pemesanan sudah done atau belum status 5
    $cek_pemesanan = $this->db->query("select * FROM pemesanan where id=" . $item_pemesanan['id_pemesanan']);
    if ($cek_pemesanan->num_rows() > 0 && $cek_pemesanan->row()->status == 5) {
      // Ambil data lama, jumlah kan qty lama dikurangi qty baru
      $cek_item_pemesanan = $this->db->query("select qty FROM item_pemesanan where id=" . $item_pemesanan['id']);
      if ($cek_item_pemesanan->num_rows() > 0) {
        // Item sudah ada
        $this->db->set('qty', 'qty-' . $item_pemesanan['qty'] . '+' . $cek_item_pemesanan->row()->qty, false);
        $this->db->where('id', $item_pemesanan['id_item']);
        $act_item = $this->db->update('pos_item');
      } else {
        // Item Baru
        $this->db->set('qty', 'qty-' . $item_pemesanan['qty'], false);
        $this->db->where('id', $item_pemesanan['id_item']);
        $act_item = $this->db->update('pos_item');
      }
    }
  }

  public function list_item_pemesanan($id_pem = null)
  {
    $id;

    if ($id_pem == null) {
      $id = $this->input->post('id');
    } else {
      $id = $id_pem;
    }

    $query = $this->db->query('	select 	p.id,p.no_pemesanan,
												p.tgl_pemesanan,
												p.`status`,
												up.name as nama_pemesan,
												(select group_name from `group` where id=up.`group`) as `group`,
												p.lantai,
												(select u.name from user as u where u.id=p.id_kurir) as kurir,
												(select r.rate from rating as r where r.id_pemesanan=p.id) as rating,
												(select concat("Kerapihan:", rating_kerapihan, " Kesopanan:", rating_kesopanan, " Kebersihan:", rating_kebersihan) from rating_gudang as r where r.id_pemesanan=p.id) as rating_gudang,
												(select r.comment from rating_gudang as r where r.id_pemesanan=p.id) as komentar_gudang,
												i.qty as qty_item,
												ip.qty,
												ip.qty_masuk,
												ip.h_stock,
												ip.note,
												p.update_by,
												p.update_date,
												ip.id as id_it_pn,
												i.item_name,
												i.barcode,
												i.id as id_item,

												up.lantai

									from pemesanan as p
											join item_pemesanan as ip
											join pos_item as i
											join user as up

									where p.id=' . $id . ' and
											ip.id_pemesanan=p.id and
											ip.id_item=i.id and
											ip.is_delete=0 and
											p.id_pemesan=up.id
											');

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function list_item_pemesanan_edit($id = null)
  {
    $query = $this->db->query('	select 	p.id as id,p.no_pemesanan,
												p.tgl_pemesanan,
												p.`status` as status,
												ip.qty,
												p.update_by,
												p.update_date,
												ip.id as ID_IT_PEMESANAN,
												ip.note,
												i.id as ID_ITEM,
												i.item_name,
												i.barcode,
												(select u.name as kurir_name from user as u,pemesanan as p where p.id_kurir=u.id and p.id=' . $id . ') as kurir_name,
												i.qty as i_qty


									from pemesanan as p
											join item_pemesanan as ip
											join pos_item as i


									where 	p.id=' . $id . ' and
											ip.id_pemesanan=p.id and
											ip.id_item=i.id and
											ip.is_delete=0');

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function ck_stat()
  {
    $query = $this->db->query('select id,status from pemesanan order by pemesanan.tgl_pemesanan desc');

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function cancel_pemesanan($id = null)
  {
    $id_user;
    $g_id_pemesan = $this->db->where('id', $id)->select('id_pemesan')->get('pemesanan');
    $is_android   = 0;
    if (empty($_SESSION['id_user'])) {
      $get_user = $this->db->where('id', $id)->select('id_pemesan')->get('pemesanan');

      $id_user    = $get_user->row()->id_pemesan;
      $is_android = 1;
    } else {
      $id_user = $_SESSION['id_user'];
    }

    $ck_stat_seb = $this->db->where('id', $id)->select('status')->get('pemesanan');
    if ($ck_stat_seb->num_rows() > 0) {

      $ck = $ck_stat_seb->result();

      if ($ck[0]->status > 0) {

        $it     = $this->db->get_where('item_pemesanan', array('id_pemesanan' => $id));
        $it_qty = $it->result();

        try {
          //TO LOG LOG_ITEM
          $it_pem_to_log = $this->db->get_where('item_pemesanan', array('id_pemesanan' => $id));

          foreach ($it_pem_to_log->result() as $i => $v) {
            $getStock = $this->db->get_where('pos_item', array('id' => $v->id_item));
            $data     = array(
              'id_pemesan'     => $g_id_pemesan->row()->id_pemesan,
              'id_item'        => $v->id_item,
              'stock_awal'     => $getStock->row()->stock_awal,
              'stock_terakhir' => $getStock->row()->qty,
              'action'         => 'TAMBAH',
              'qty'            => $v->qty,
              'is_android'     => $is_android,
              'parent'         => 'KERANJANG',
              'id_parent'      => $v->id_pemesanan,
              'trigger'        => 'ITEM PEMESANAN',
              'id_trigger'     => $v->id,
              'insert_by'      => $id_user,
              'update_by'      => $id_user,
            );

            $insert = $this->db->insert('log_item', $data);
          }

        } catch (Exception $e) {

        } finally {
          foreach ($it_qty as $key => $value) {
            $getStock = $this->db->get_where('pos_item', array('id' => $value->id_item));

            $this->db->where('id', $value->id);
            $act_it_pemesanan = $this->db->update('item_pemesanan', array('h_stock' => $getStock->row()->qty));

            if ($act_it_pemesanan) {
              $qty_it    = $value->qty;
              $kem_stock = $this->db->query('update pos_item set qty=qty+' . $qty_it . ' where id=' . $value->id_item);
            }

          }
        }

      } else {
        //TO LOG LOG_ITEM
        $it_pem_to_log = $this->db->get_where('item_pemesanan', array('id_pemesanan' => $id));

        foreach ($it_pem_to_log->result() as $i => $v) {
          $getStock = $this->db->get_where('pos_item', array('id' => $v->id_item));
          $data     = array(
            'id_pemesan'     => $g_id_pemesan->row()->id_pemesan,
            'id_item'        => $v->id_item,
            'stock_awal'     => $getStock->row()->stock_awal,
            'stock_terakhir' => $getStock->row()->qty,
            'action'         => 'TAMBAH',
            'qty'            => 0,
            'is_android'     => $is_android,
            'parent'         => 'KERANJANG',
            'id_parent'      => $v->id_pemesanan,
            'trigger'        => 'ITEM PEMESANAN',
            'id_trigger'     => $v->id,
            'insert_by'      => $id_user,
            'update_by'      => $id_user,
          );

          $insert = $this->db->insert('log_item', $data);

          $this->db->where('id', $v->id);
          $act_it_pemesanan = $this->db->update('item_pemesanan', array('h_stock' => $getStock->row()->qty));
        }
      }

    }

    $query = $this->db->query("update pemesanan set status=6,update_by=" . $id_user . " where id=" . $id . " and status<5");

    if ($query) {
      $ck_stat = $this->db->where('id', $id)->select('status')->get('pemesanan');

      if ($ck_stat->num_rows() > 0) {
        if ($ck_stat->row()->status == 6) {
          //TO LOG
          try {
            $res_user  = $this->db->get_where('user', array('id' => $id_user));
            $name_user = $res_user->row()->name;

            $this->db->insert('log_keranjang', array(
              'insert_by'    => $id_user,
              'id_keranjang' => $id,
              'status'       => 6,
              'status_text'  => 'Cancel',
              'message'      => 'User dengan ID ' . $id_user . '(' . $name_user . ') membatalkan pemesanan(Keranjang) dengan ID Pemesanan ' . $id,
            ));

          } catch (Exception $e) {

          }

          return '{"status":1,"message":"Berhasil membatalkan pemesanan"}';

        } else {
          if ($ck_stat->row()->status == 5) {
            return '{"status":-3,"message":"Gagal membatalkan pemesanan, Status pemesanan sudah Selesai"}';
          }
        }

      } else {
        return '{"status":-2,"message":"Gagal membatalkan pemesanan. Data tidak ditemukan"}';
      }

    } else {
      return '{"status":-1,"message":"Gagal membatalkan pemesanan"}';
    }
  }

  public function list_spb($id = null)
  {
    $query = $this->db->query('	select
											s.*,
											s.`status` as status_spb,
											u_i.name as dibuat_oleh,
											u_u.name as diubah_oleh

									from
											spb as s
											join user as u_i
											join user as u_u
									where
											s.insert_by=u_i.id
											and s.update_by=u_u.id
											and s.id_permintaan=' . $id . '
											and s.is_delete=0');

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  //##############################  SPB  #########################
  public function tb_spb($have_bast = false)
  {
  	$sql = '	select
											s.*,
											p.no_pengajuan as no_permintaan,
											p.judul,
											(select u_sb.name from user as u_sb where p.submiter=u_sb.id) as diajukan_oleh,
											(select u_re.name from user as u_re where s.receiver=u_re.id) as diterima_oleh,
											s.receive_date as tgl_penerimaan,
											s.status as status_penerimaan,
											u_i.name as dibuat_oleh,
											u_u.name as diubah_oleh

									from
											spb as s
											join pengajuan as p
											join user as u_i
											join user as u_u
									where
											s.insert_by=u_i.id
											and s.id_permintaan=p.id
											and s.update_by=u_u.id
											and s.is_delete=0';

    if ($have_bast) {
      $sql .= ' AND !ISNULL(s.no_bast)';
    }

    $sql .= ' order by s.insert_date desc';

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function get_select2_spb_without_bast($data){
    $sql = "SELECT id, no_spb as text FROM spb";
    $sql .= " WHERE (no_spb LIKE ?) AND isnull(no_bast) AND status=1 and is_delete=0";
    
    $data['total_record'] = $this->db->query($sql, ['%' . $data['q'] . '%'])->num_rows();

    $start  = ($data['page'] - 1) * $data['page_limit'];
    $length = $data['page_limit'];

    $sql .= " LIMIT {$start}, {$length}";

    $query = $this->db->query($sql, ['%' . $data['q'] . '%']);

    $data['more'] = $data['total_record'] > $data['page'] * $data['page_limit'];

    if ($query->num_rows() > 0) {
        $result = $query->result_array();

        $query->free_result();

        $data['items'] = $result;
    } else {
        $data['items'] = [];
    }

    return $data;
  }

  public function list_item_spb($id = null)
  {
    $sql = '	select 	p.id,
											p.id as id_sp,
											p.no_spb,
											p.no_bast,
                      p.tanggal_bast,
											p.attach_file,
											p.insert_date as tgl_pembuatan_spb,
											p.`status`,
											p.`status` as status_spb,
											p.start_periode as start,
											p.end_periode as end,
											p.note as note_penerimaan,

											pn.no_pengajuan as no_permintaan,
											pn.insert_date as tanggal_permintaan,
											pn.status as status_permintaan_brg,
											(select u_s.name from pengajuan as png join user as u_s on u_s.id=png.submiter where png.id=p.id_permintaan) as diajukan_oleh,
											(select u_a.name from pengajuan as png join user as u_a on u_a.id=png.approval where png.id=p.id_permintaan) as disetujui_oleh,
											(select png.approve_date from pengajuan as png where png.id=p.id_permintaan) as disetujui_tanggal,

											ip.id as id_spb,
											ip.qty,
											ip.qty_masuk,
											ip.`harga`,
											p.insert_by,
											p.update_by,
											p.update_date,
											ip.id as id_it_pn,
											i.item_name,
											i.min_qty,
											i.max_qty,
											i.barcode,
											i.id as id_item,
											ip.h_stock,
											ip.update_date as update_it_date,
											i.satuan,
											sup.id as id_supplier,
											sup.supplier_name,
											(select ip.qty*ip.`harga` as total_harga) as total_harga,
											u_spb.name as spb_dibuat_oleh,
											(select stat_spb from pengajuan where id=p.id_permintaan) as status_permintaan

									from 	spb as p
											join pengajuan as pn
											join supplier as sup
											join item_spb as ip
											join pos_item as i
											join user as u_spb

									where 	p.id=' . $id . '
											and p.id_permintaan=pn.id
											and ip.id_spb=p.id
											and ip.id_item=i.id
											and p.id_supplier=sup.id
											and p.insert_by=u_spb.id
											and ip.is_delete=0
											and p.is_delete=0';

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function ls_it_spb($id = null)
  {
    $query = $this->db->query('	select
											i.id_item,
											i.qty,
											i.id_pemesanan_brg,
											s.update_date,
											s.insert_date,
											s.no_spb as no_pengajuan,
											s.status as status_spb,
											s.start_periode,
											s.end_periode,
											ii.item_name,
											i.qty,
											ii.satuan,
											i.harga,
											(select i.qty*i.harga) as total_harga,
											(select u_s.name from pengajuan as png join user as u_s on u_s.id=png.submiter where png.id=s.id_permintaan) as diajukan_oleh,
											sup.supplier_name,
											sup.supplier_address,
											sup.supplier_pic_name,
											sup.supplier_phone


									from
											spb as s
											join item_spb as i on s.id=i.id_spb
											join item_pengajuan as ip
											join pos_item as ii on ip.id_item=ii.id
											join supplier as sup
									where
											i.id_pemesanan_brg=ip.id_pengajuan
											and i.id_item=ip.id_item
											and s.id=' . $id . '
											and s.id_supplier=sup.id
											and s.is_delete=0
											and i.is_delete=0
											and ip.is_delete=0');

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function total_all_spb_by_id($id = null)
  {
    $query = $this->db->query('	select
											sum(qty*harga) as total_all
									from
											item_spb
									where
											id_spb=' . $id);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }

  public function getCRating($id_pemesan = null)
  {
    $query = $this->db->query("	select count(id) as pem_process ,
									(select
										count(id) as jml_rate
									from
										(select
												p.id,
												(select rate from rating where id_pemesanan=p.id) as rating
										 from
										 		pemesanan as p
										 where
										 		p.`status`=5 and p.id_pemesan=" . $id_pemesan . ") as id
									where
										rating is null) as jml_rate

									from pemesanan where `status`<5 and id_pemesan=" . $id_pemesan . "");

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return null;
    }
  }
}
