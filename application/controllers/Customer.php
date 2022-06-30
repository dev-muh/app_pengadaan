<?php
defined('BASEPATH') or exit('No direct script access allowed');

class customer extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    if (count($_SESSION) <= 1) {
      redirect('login');
    }
  }

  public function index()
  {

  }
  public function view()
  {
    $var['title']      = 'USER MANAGEMENT';
    $var['user']       = $_SESSION['user_type'];
    $var['s_active']   = 'user';
    $var['js']         = 'js-customer';
    $var['mode']       = 'view';
    $var['page_title'] = 'USER MANAGEMENT';
    $var['plugin']     = 'plugin_1';
    $var['content']    = 'view-customer';
    $this->load->model('model_user');
    $this->load->model('model_customer');
    $var['tb_customer'] = $this->model_user->tb_customer();
    $var['ls_group']    = $this->model_customer->group();
    $this->load->view('view-index', $var);
  }

  public function add()
  {
    if ($_POST['user_type'] == 'Karyawan') {

    } else {

    }

    if ($_POST['user_type'] == 'Karyawan') {

      $email_split = explode('@', $_POST['email']);
      $fil_email   = end($email_split);

      $this->load->model('model_mail');
      $mail = $this->model_mail->mail_filter($fil_email);

      if ($mail) {
        $this->submit_add();
      } else {
        echo 'email_false';
      }
    } else {
      $this->submit_add();
    }

  }

  public function add_csv()
  {
    $config['upload_path']   = './assets/temp/';
    $config['allowed_types'] = 'csv';
    // $config['max_size']      = 100;
    $config['overwrite']     = true;
    // $config['file_name']     = $filename;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file_csv')) {
      echo $this->upload->display_errors();

    }

    $upload_data = $this->upload->data();

    // exit;

    $this->load->model('model_user');
    $this->load->model('model_mail');

    // $file = fopen("assets/karyawan_tofap_sample.csv", "r");
    $file = fopen($upload_data['full_path'], "r");
    // echo "<pre>";
    // exit;

    $message = '';

    $rs_karyawan = [];

    $insert_user_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';

    $title = true;
    while (!feof($file)) {
      $csv_row_data = fgetcsv($file);
      if (!$title && !empty($csv_row_data)) {
        $pass = $this->model_user->randomPassword();
        $data = [
          'username'   => strtolower($csv_row_data[0]),
          'password'   => sha1(md5($pass)),
          'name'       => $csv_row_data[1],
          'email'      => $csv_row_data[2],
          'no_pegawai' => '',
          'jabatan'    => '',
          'department' => $csv_row_data[5],
          'direktorat' => $csv_row_data[6],
          'group'      => $csv_row_data[4],
          'lantai'     => $csv_row_data[3],
          'user_type'  => 'Karyawan',
          'is_active'  => 1,
          'insert_by'  => $insert_user_id,
          'update_by'  => $insert_user_id,
        ];

        // Cek Email
        $email_split = explode('@', $data['email']);
        $fil_email   = end($email_split);
        $mail        = $this->model_mail->mail_filter($fil_email);

        if (!$mail) {
          $msg = 'Gagal Menambahkan email ' . $data['email'] . '. Hanya boleh menggunakan email dengan domain @tugu.com';
          $message .= "$msg<br>";
          continue;
        }

        // Cek User
        $u_available = $this->model_user->ck_user($data['username'], $data['email'], $data['user_type']);

        if ($u_available) {
          $message .= "Duplicate Username/Email " . $data['email'] . ".<br>";
          continue;
        }

        $email_stat = $this->model_user->sendMail($data['email'], $data['name'], '', $data['group'], $data['lantai'], $data['username'], $pass);

        if ($email_stat == '1') {
          $stat = $this->db->insert('user', $data);
          $l_id = $this->db->insert_id();
          if ($stat) {
            $this->db->where('id', $l_id);
            $this->db->update('user', array(
              'active_by' => $l_id,
              'insert_by' => $l_id,
              'update_by' => $l_id,
            ));
            $message .= "Berhasil Menambahkan " . $data['name'] . ".<br>";
          } else {
            $message .= "Gagal Menambahkan " . $data['name'] . ".<br>";
          }
        } else {
          $message .= 'Gagal Mengirim email untuk ' . $data['email'] . ".<br>";
        }
      }
      $title = false;
    }

    fclose($file);
    unlink($upload_data['full_path']);

    echo $message;
  }

  public function submit_add()
  {
    $this->load->model('model_user');

    $id   = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    $data = $this->input->post();

    $u_available = $this->model_user->ck_user($data['username'], $data['email'], $data['user_type']);

    if ($u_available) {
      echo 'duplicate';
    } else {
      $data['insert_by'] = $id;
      $data['update_by'] = $id;
      $data['username']  = strtolower($data['username']);
      if ($data['user_type'] == 'Karyawan') {
        $pass             = $this->model_user->randomPassword();
        $data['password'] = sha1(md5($pass));

        $email_stat = $this->model_user->sendMail($data['email'], $data['name'], '', $data['group'], $data['lantai'], $data['username'], $pass);

        if ($email_stat == '1') {
          $stat = $this->db->insert('user', $data);
          $l_id = $this->db->insert_id();
          if ($stat) {
            $this->db->where('id', $l_id);
            $this->db->update('user', array(
              'active_by' => $l_id,
              'insert_by' => $l_id,
              'update_by' => $l_id,
            ));
            echo 'success';
          } else {
            echo 'error1';
          }
        } else {
          echo 'error2';
          //print_r($email_stat);
        }
      } else {
        $data['password'] = sha1(md5('12345678'));

        $stat = $this->db->insert('user', $data);
        $l_id = $this->db->insert_id();
        if ($stat) {
          $this->db->where('id', $l_id);
          $this->db->update('user', array(
            'active_by' => $l_id,
            'insert_by' => $l_id,
            'update_by' => $l_id,
          ));
          echo 'success';
        } else {
          echo 'error';
        }
      }

      // if($stat){
      //   echo 'success';
      // }else{
      //   echo 'error';
      // }
    }
  }

  public function ubahStaff()
  {
    $this->db->where('user_type', 'Staff');
    $act = $this->db->update('user', array('user_type' => 'Karyawan'));

    if ($act) {
      echo "Success";
    } else {
      echo "Gagal";
    }
  }
  public function edit()
  {
    $id      = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    $data    = $this->input->post();
    $ck_user = $this->db->query('select update_by from user where id=' . $data['id']);

    if ($ck_user->row()->update_by != 0) {
      $data['update_by'] = $id;
    }

    $data['insert_by'] = $id;

    $data['name']       = $this->input->post('name');
    $data['no_pegawai'] = $this->input->post('no_pegawai');
    $data['jabatan']    = $this->input->post('jabatan');
    $data['department'] = $this->input->post('department');
    $data['direktorat'] = $this->input->post('direktorat');
    $data['user_type']  = $this->input->post('user_type');
    $data['group']      = $this->input->post('group');
    $data['username']   = strtolower($data['username']);

    if ($this->input->post('user_type') == 'Karyawan') {
      $data['email']  = $this->input->post('email');
      $data['lantai'] = $this->input->post('lantai');
    }

    unset($data['password']);

    $this->db->where('id', $data['id']);
    $this->db->update('user', $data);

  }

  public function delete()
  {
    $id   = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    $data = $this->input->post();

    $data['delete_by']   = $id;
    $data['delete_date'] = date('Y-m-d H:i:s');
    $data['is_delete']   = 1;

    $this->db->where('id', $data['id']);
    $s = $this->db->update('user', $data);

    if ($s) {
      echo 'success';
    } else {
      echo 'error';
    }
  }

  public function deactive()
  {
    $id   = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    $data = $this->input->post();

    // $data['delete_by'] = $id;
    // $data['delete_date'] = date('Y-m-d H:i:s');
    $data['is_active'] = 0;

    $this->db->where('id', $data['id']);
    $s = $this->db->update('user', $data);

    if ($s) {
      echo 'success';
    } else {
      echo 'error';
    }
  }

  public function activate()
  {
    $id   = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    $data = $this->input->post();

    $data['active_by']   = $id;
    $data['active_date'] = date('Y-m-d H:i:s');
    $data['is_active']   = 1;

    $this->db->where('id', $data['id']);
    $s = $this->db->update('user', $data);

    if ($s) {
      echo 'success';
    } else {
      echo 'error';
    }

  }

  public function alert()
  {
    echo '';
  }

  public function all_group()
  {
    $this->load->model('model_customer');
    $group = $this->model_customer->group();

    echo json_encode($group);
  }

  public function fifo($id = null)
  {
    $this->load->model('model_report');
    $log_item = $this->model_report->report_log_item($id);
    // print_r($log_item);
    $harga_spb = [];

    foreach ($log_item as $key => $value) {
      if (!empty($value->harga_spb)) {
        array_push($harga_spb, $value->harga_spb);
      }
    }

  }

  public function cetak_user($img = null)
  {
    $this->load->library('m_pdf');

    // if(!empty($val['items'])){
    $this->load->view('view-print_user', $val);
    $html = $this->load->view('view-print_user', $val, true);
    // $html = '<img src="">';

    $css = [];

    $pdfFilePath = "USER_" . time() . ".pdf";

    $pdf = $this->m_pdf->load();
    // $mpdf = new Mpdf(['format' => 'Legal']);

    $pdf->AddPage('P', '', '', '', '', '', '', '', '', 20, 20);
    // foreach ($css as $key => $v) {
    //   $pdf->WriteHTML($v, 1);
    // }

    $pdf->WriteHTML($html);

    $pdf->Output($pdfFilePath, "I");
    exit();

    // }
  }

  public function oke()
  {
    $query = $this->db->query("
      SELECT
        v.*,p.insert_by,p.insert_by,p.update_by,p.update_date
      FROM
        v_bug AS v
        JOIN pemesanan AS p ON v.id_keranjang=p.id
      order by insert_date asc
    ");

    foreach ($query->result() as $key => $value) {

      $qq = $this->db->query("SELECT * FROM log_item WHERE id_item=" . $value->id_item . " ORDER BY insert_date desc LIMIT 1")->row();
      // echo 'SIP'.$qq->stock_terakhir-$qq->qty.' - '.$value->qty.' = '.($qq->stock_terakhir-$qq->qty)-$value->qty.'<br>';

      $stock_terakhir = ($qq->stock_terakhir - $qq->qty);
      $this->db->insert('log_item', array(
        'id_item'        => $value->id_item,
        'stock_terakhir' => $stock_terakhir,
        'qty'            => $value->qty,
        'insert_date'    => $value->insert_date,
        'id_pemesan'     => $value->insert_by,
        'stock_awal'     => $qq->stock_awal,
        'action'         => 'KURANGI',
        'is_android'     => 0,
        'parent'         => 'KERANJANG',
        'id_parent'      => $value->id_keranjang,
        'trigger'        => 'ITEM PEMESANAN',
        'id_trigger'     => $value->id,
        'insert_by'      => $value->insert_by,
        'update_by'      => $value->update_by,
        'update_date'    => $value->update_date,
      ));

    }
  }

  public function lanjut()
  {
    $item = $this->db->query("
      SELECT
        l.id_item
      FROM
        log_item AS l
        left JOIN pemesanan AS p ON l.parent='KERANJANG' AND p.id=l.id_parent
        WHERE l.insert_date BETWEEN '2020-01-12' AND '2020-01-20'
      group by l.id_item
      ORDER BY
        l.id_item ASC, l.insert_date asc
    ");

    foreach ($item->result() as $key => $value) {
      $query = $this->db->query("
        SELECT
          p.status,
          (SELECT if(`action`='TAMBAH',stock_terakhir,if(`action`='KURANGI',stock_terakhir-qty,'')) FROM log_item WHERE id_item=l.id_item and insert_date<'2020-01-13 00:00:01' ORDER BY insert_date desc LIMIT 1) AS s_terakhir,
          l.*
        FROM
          log_item AS l
          left JOIN pemesanan AS p ON l.parent='KERANJANG' AND p.id=l.id_parent
          WHERE l.insert_date BETWEEN '2020-01-12' AND '2020-01-20'
          and l.id_item=" . $value->id_item . "
        ORDER BY
          l.id_item ASC, l.insert_date asc
      ");

      $st_ter = $query->result()[0]->s_terakhir;

      foreach ($query->result() as $k => $v) {

        if ($v->action == 'KURANGI') {
          $this->db->where('id', $v->id)->update('log_item', array(
            'stock_terakhir' => $st_ter,
          ));
          $st_ter -= $v->qty;
        }

        if ($v->action == 'TAMBAH') {
          $this->db->where('id', $v->id)->update('log_item', array(
            'stock_terakhir' => $st_ter,
          ));
          $st_ter += $v->qty;
        }

      }
    }

    // $query = $this->db->query("
    //   SELECT
    //     p.status,
    //     (SELECT if(`action`='TAMBAH',stock_terakhir,if(`action`='KURANGI',stock_terakhir-qty,'')) FROM log_item WHERE id_item=l.id_item and insert_date<'2020-01-13 00:00:01' ORDER BY insert_date desc LIMIT 1) AS s_terakhir,
    //     l.*
    //   FROM
    //     log_item AS l
    //     left JOIN pemesanan AS p ON l.parent='KERANJANG' AND p.id=l.id_parent
    //     WHERE l.insert_date BETWEEN '2020-01-12' AND '2020-01-20'
    //     and l.id_item=
    //   ORDER BY
    //     l.id_item ASC, l.insert_date asc
    // ");

    // foreach ($query->result() as $key => $value) {
    //   echo $key;
    // }
  }

  public function lanjut_lagi()
  {
    $query = $this->db->query("
      SELECT
        p.status,
        l.*
      FROM
        log_item AS l
        right  JOIN pemesanan AS p ON l.parent='KERANJANG' AND p.id=l.id_parent
        WHERE l.insert_date BETWEEN '2020-01-12' AND '2020-01-20'

      ORDER BY
        l.id_item ASC, l.insert_date ASC
    ");

    foreach ($query->result() as $key => $value) {
      $this->db->where('id', $value->id_trigger)->update('item_pemesanan', array('h_stock' => $value->stock_terakhir));
    }
  }

}
