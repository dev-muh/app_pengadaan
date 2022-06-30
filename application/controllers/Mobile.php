<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mobile extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('KirimFcm');
  }

  public function index()
  {

    $this->load->view('view-login');
  }

  public function test_coba($s)
  {
    echo "ANDA MENULISKAN " . $s;
  }

  public function randomPassword()
  {
    // $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $alphabet    = '1234567890';
    $pass        = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
      $n      = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }

  //########## REGISTER
  public function ck_username($x = null)
  {
    $pattern = "/^[a-zA-Z0-9]*$/";
    $text    = $x;
    if (preg_match($pattern, $text)) {
      return true;
    } else {
      return false;
    }
  }
  public function register($ck = null)
  {

    $this->load->model('model_user');

    $user_id  = time();
    $username = $this->input->post('username');
    $name     = $this->input->post('name');
    $email    = $this->input->post('email');
    $group    = $this->input->post('group');
    $lantai   = $this->input->post('lantai');
    $password = $this->randomPassword();

    $email_split = explode('@', $email);
    $fil_email   = end($email_split);

    if (!is_numeric($group)) {
      echo '{"status":"-7","message":"Group yang anda masukkan salah."}';
      return false;
    }

    if (!is_numeric($lantai) || $lantai == 0) {
      echo '{"status":"-8","message":"Lantai yang anda masukkan salah."}';
      return false;
    }

    $this->load->model('model_mail');
    $mail = $this->model_mail->mail_filter($fil_email);

    if ($this->ck_username($username)) {
      if ($mail) {
        $data = array(
          'user_id'     => $user_id,
          'username'    => $username,
          'name'        => $name,
          'group'       => $group,
          'lantai'      => $lantai,
          'email'       => $email,
          'user_type'   => 'Karyawan',
          'is_active'   => 1,
          'active_date' => date('Y-m-d H:i:s'),
          'password'    => sha1(md5($password)),
        );

        $q_username = $this->db->query("select id from user where username='" . $username . "'");
        $q_email    = $this->db->query("select id from user where email='" . $email . "'");

        if (!empty($ck)) {
          if ($q_username->num_rows() > 0) {
            echo '{"status":"1","message":"Registrasi Sukses"}';
          } else {
            if ($q_email->num_rows() > 0) {
              echo '{"status":"1","message":"Registrasi Sukses"}';
            } else {
              echo '{"status":"1","message":"Registrasi Sukses"}';
            }
          }
        } else {
          if ($q_username->num_rows() > 0) {
            echo '{"status":"-1","message":"Username sudah terdaftar."}';
          } else {
            if ($q_email->num_rows() > 0) {
              echo '{"status":"-2","message":"Email sudah terdaftar."}';
            } else {
              //echo $this->randomPassword();
              $email_stat = $this->model_user->sendMail($email, $name, $user_id, $group, $lantai, $username, $password);

              if ($email_stat == '1') {
                $insert = $this->db->insert('user', $data);
                $l_id   = $this->db->insert_id();
                if ($insert) {
                  $this->db->where('id', $l_id);
                  $this->db->update('user', array(
                    'active_by' => $l_id,
                    'insert_by' => $l_id,
                    'update_by' => $l_id,
                  ));
                  echo '{"status":"1","message":"Registrasi Sukses"}';
                } else {
                  echo '{"status":"-3","message":"(DB) Registrasi Error, Silahkan coba kembali"}';
                }
              } else {
                echo '{"status":"-4","message":"' . $email_stat . '"}';
                //print_r($email_stat);
              }
            }
          }
        }
      } else {
        echo '{"status":"-5","message":"Hanya boleh menggunakan email dengan domain @tugu.com"}';
      }
    } else {
      echo '{"status":"-6","message":"Username hanya boleh menggunakan huruf atau angka saja."}';
    }

  }

  //########### FORGET / FORGOT PASSWORD
  public function forget()
  {

    $this->load->model('model_user');

    $username = $this->input->post('username');
    $email    = $this->input->post('email');

    // echo $this->input->post('username') . '>' . $email;

    $q_akun = $this->db->query("select * from user where user_type='Karyawan' and email='" . $email . "' and username='" . $username . "'");

    if ($q_akun->num_rows() > 0) {
      $id       = $q_akun->row()->id;
      $username = $q_akun->row()->username;
      $name     = $q_akun->row()->name;
      $group    = $q_akun->row()->group;
      $lantai   = $q_akun->row()->lantai;
      $password = $this->randomPassword();

      $email_stat = $this->model_user->mailForgetPassword($email, $password, $name, $username);

      if ($email_stat == '1') {
        $this->db->where('id', $id);
        $update = $this->db->update('user', array(
          'password'  => sha1(md5($password)),
          'update_by' => $id,
        ));
        if ($update) {
          echo '{"status":"1","message":"Forgot Password Sukses"}';
        } else {
          echo '{"status":"-1","message":"Database Error"}';
        }
      } else {
        echo '{"status":"-2","message":"Error Forgot Password, Silahkan coba kembali."}';
      }

    } else {
      echo '{"status":"-3","message":"Tidak dapat menemukan Karyawan dengan Username dan Email tersebut"}';
    }
  }

  public function txt_stat($i = null)
  {
    $status = ['Waiting Approval', 'Order Received', 'Courier Assigned', 'Prepare Item', 'Courier On The Way', 'Done', 'Cancel'];
    return $status[$i];
  }
  public function ls_pemesanan()
  {

    $this->load->model('Model_mobile');
    $id = !empty($_POST['id']) ? $_POST['id'] : '';

    if (!empty($id)) {
      $ck_user = $this->db->query('select * from user where id=' . $id);

      if (!empty($ck_user) && $ck_user->num_rows() > 0) {
        if ($ck_user->row()->user_type == 'Admin Gudang') {
          $data = $this->Model_mobile->ls_pemesanan();
        }

        if ($ck_user->row()->user_type == 'Kurir') {
          $data = $this->Model_mobile->ls_pemesanan();
        }

        if (!empty($data)) {
          $dt = json_decode(json_encode($data), true);
          if (!empty($dt)) {
            foreach ($dt as $key => $value) {
              //$dt[$key]['item_list']='';
              $dt[$key]['txt_stat'] = $this->txt_stat($dt[$key]['status']);
              $str                  = '';
              $item                 = $this->Model_mobile->ls_it_pemesanan($dt[$key]['id_pemesanan']);
              if ($item) {
                foreach ($item as $in => $value) {

                  $str .= ',' . $value->item_name;
                  $f_str                 = substr($str, 1);
                  $dt[$key]['item_list'] = $f_str;

                }
              }

            }
            echo json_encode($dt);
          } else {

          }
        }
      }
    }
  }

  public function count()
  {
    $c = $this->db->query('select id from pemesanan where is_delete=0 limit 20')->result();

    foreach ($c as $key => $value) {
      echo $this->db->query('	select
										group_concat(i.item_name) as item_list
									from
										item_pemesanan  as ip
										join pos_item as i on i.id=ip.id_item
									where
										ip.id_pemesanan=' . $value->id . '
										and ip.is_delete=0')->row()->item_list;
    }
  }

  public function ls_by_kurir($id_kurir = null)
  {
    $this->load->model('Model_mobile');

    $status = ['Waiting Approval', 'Order Received', 'Courier Assigned', 'Prepare Item', 'Courier On The Way', 'Done', 'Cancel'];

    // echo "MOSOK IKI NULL?";

    // return false;

    $data = $this->Model_mobile->ls_pemesanan_by_kurir($id_kurir);
    $dt   = json_decode(json_encode($data), true);

    if (!empty($dt)) {
      foreach ($dt as $key => $value) {
        $dt[$key]['txt_stat'] = $status[$dt[$key]['status']];

        $extra = $this->db->query('	select
										group_concat(i.item_name) as item_list,
										count(*) as total_barang
									from
										item_pemesanan  as ip
										join pos_item as i on i.id=ip.id_item
									where
										ip.id_pemesanan=' . $dt[$key]['id_pemesanan'] . '
										and ip.is_delete=0')->row();
        $dt[$key]['item_list']    = $extra->item_list;
        $dt[$key]['total_barang'] = $extra->total_barang;

        // $str = '';
        // $item = $this->Model_mobile->ls_it_pemesanan($dt[$key]['id_pemesanan']);
        // if($item){
        //   foreach ($item as $in => $value) {

        //     $str .= ','.$value->item_name;
        //     $f_str = substr($str,1);
        //     $dt[$key]['item_list']=$f_str;

        //   }
        // }else{

        // }

      }
      echo json_encode($dt);
    } else {

    }
  }
  public function ls_by_pemesan()
  {
    $id_staff = $this->input->post('id');
    $this->load->model('Model_mobile');

    $status = ['Waiting Approval', 'Order Received', 'Courier Assigned', 'Prepare Item', 'Courier On The Way', 'Done', 'Cancel'];

    $data = $this->Model_mobile->ls_pemesanan_by_pemesan($id_staff);

    $dt = json_decode(json_encode($data), true);

    if (!empty($dt)) {
      foreach ($dt as $key => $value) {
        //$dt[$key]['item_list']='';
        $dt[$key]['txt_stat'] = $status[$dt[$key]['status']];
        $str                  = '';
        $item                 = $this->Model_mobile->ls_it_pemesanan($dt[$key]['id_pemesanan']);
        $rate                 = $this->Model_mobile->ck_rate($dt[$key]['id_pemesanan']);
        //print_r($item);
        if ($item) {
          foreach ($item as $in => $value) {

            $str .= ',' . $value->item_name;
            $f_str                 = substr($str, 1);
            $dt[$key]['item_list'] = $f_str;

          }
        } else {

        }

        $dt[$key]['rate'] = $rate[0];

      }
      echo json_encode($dt);
    } else {
      //echo "A";
    }
  }

  public function ls_item_pemesanan($id = null)
  {
    $this->load->model('Model_mobile');
    $item = $this->Model_mobile->ls_it_pemesanan($id);

    $dt = json_decode(json_encode($item), true);
    //echo $dt[0]['id_item'];
    foreach ($dt as $in => $value) {
      //echo $value[$in]['id_item'];
      $img                 = json_decode($this->Model_mobile->getPhoto($dt[$in]['id_item']), true);
      $dt[$in]['img_name'] = $img[0]['img_name'];
    }

    echo json_encode($dt);
  }

  public function insertkurir()
  {

    $id_pem   = $this->input->post('id_pemesanan');
    $id_kurir = $this->input->post('id_kurir');

    $this->db->where('id', $id_pem);
    $this->db->where('is_delete', 0);
    $dt = $this->db->select('*')->from('pemesanan')->get();

    $kurir = $this->db->query("select
								id,
								username,
								name
						from
								user
						where
								id=" . $id_kurir . "
								and is_delete=0
								and is_active=1
								and (user_type='Admin Gudang' or user_type='Kurir')");
    //print_r($kurir->result());

    if ($kurir->num_rows() > 0) {
      if ($dt->num_rows() > 0) {
        $stat = $dt->row()->status;

        switch ($stat) {
          case 0:
            echo '{"status":0,"message":"Waiting Approval"}';
            break;
          case 1:
            try {
              if (!empty($id_pem) && !empty($id_kurir)) {

                $u_dt = array(
                  'id_kurir'       => $id_kurir,
                  'add_kurir_date' => date('Y-m-d H:i:s'),
                  'add_kurir_by'   => $id_kurir,
                  'status'         => 2,
                );

                $this->db->where('id', $dt->row()->id);
                $update = $this->db->update('pemesanan', $u_dt);

                if ($update) {
                  echo '{"status":1,"message":"Success Update Kurir"}';

                  //SEND NOTIF
                  $this->load->model('model_mobile');
                  $dt_pem     = $this->db->get_where('pemesanan', array('id' => $id_pem));
                  $id_kurir   = $dt_pem->row()->id_kurir;
                  $dt_kurir   = $this->db->get_where('user', array('id' => $id_kurir));
                  $kurir_name = $dt_kurir->row()->name;
                  $id_pemesan = $dt_pem->row()->id_pemesan;
                  $this->model_mobile->send_notif($id_pemesan, 'TOFAP', 'Kurir [' . $kurir_name . '] telah ditambahkan ke Pesanan anda dengan nomor pengambilan ' . $dt_pem->row()->no_pemesanan);

                  //TO LOG
                  try {
                    $res_user  = $this->db->get_where('user', array('id' => $id_pemesan));
                    $name_user = $res_user->row()->name;
                    $this->db->insert('log_keranjang', array(
                      'insert_by'    => $id_kurir,
                      'id_keranjang' => $id_pem,
                      'status'       => 2,
                      'status_text'  => $this->txt_stat(2),
                      'message'      => 'User dengan ID ' . $id_kurir . '(' . $kurir_name . ') menambahkan kurir ID:' . $id_kurir . '(' . $kurir_name . ') ke keranjang untuk USER_ID ' . $id_pemesan . '(' . $name_user . ') dengan ID Pemesanan ' . $id_pem,
                    ));
                  } catch (Exception $e) {

                  }
                } else {
                  echo '{"status":-2,"message":"Kesalahan saat mengupdate Data Pemesanan, coba lagi"}';
                }
              } else {
                echo '{"status":-1,"message":"Kurir / Data Pemesanan tidak ada"}';
              }

            } catch (Exception $e) {
              echo '{"status":-3,"message":"Terjadi kesalahan saat update data"}';
            }
            break;
          case 2:
            echo '{"status":2,"message":"Courier Assigned"}';
            break;
          case 3:
            echo '{"status":3,"message":"Prepare Item"}';
            break;
          case 4:
            echo '{"status":4,"message":"Courier On The Way"}';
            break;
          case 5:
            echo '{"status":5,"message":"Done"}';
            break;
          default:
            # code...
            break;
        }
      } else {
        echo '{"status":-5,"message":"Data Pemesanan tidak ada / Pemesanan sudah di cancel/delete"}';
      }
    } else {
      echo '{"status":-4,"message":"Status kurir tidak aktif / Data kurir tidak ada"}';
    }
    //print_r($kurir->result());
  }

  public function updatestatpemesanan()
  {
    $id_pem   = $this->input->post('id_pemesanan');
    $id_kurir = $this->input->post('id_kurir');
    $stat     = $this->input->post('status');

    if (!empty($id_pem) && !empty($id_kurir) && !empty($stat)) {
      $dt = $this->db->query("
								select
									 p.id,u_k.id as id_kurir,p.status,u_k.name
								FROM
									pemesanan as p
								    join user as u_k on p.id_kurir=u_k.id
								WHERE
									(u_k.user_type='Kurir' or u_k.user_type='Admin Gudang') and
									p.status!=5 and
									p.is_delete=0 and
								    p.id=" . $id_pem . " AND u_k.id=" . $id_kurir);

      if ($dt->num_rows() > 0) {
        if ($stat > 2 && $stat <= 5) {
          $data = array(
            'status'    => $stat,
            'update_by' => $id_kurir,
          );
          $this->db->where('id', $id_pem);
          $s_updt = $this->db->update('pemesanan', $data);
          if ($s_updt) {
            echo '[{"status":1,"message":"Sukses update status pemesanan"}]';

            //SEND NOTIF
            $this->load->model('model_mobile');
            $dt_pem     = $this->db->get_where('pemesanan', array('id' => $id_pem));
            $id_kur     = $dt_pem->row()->id_kurir;
            $dt_kurir   = $this->db->get_where('user', array('id' => $id_kur));
            $kurir_name = $dt_kurir->row()->name;
            $id_pemesan = $dt_pem->row()->id_pemesan;
            $stat_txt   = $this->txt_stat($dt_pem->row()->status);
            $this->model_mobile->send_notif($id_pemesan, 'TOFAP', 'Kurir [' . $kurir_name . '] mengubah status Pesanan anda dengan nomor pengambilan ' . $dt_pem->row()->no_pemesanan . ' menjadi ' . $stat_txt);

            //TO LOG
            try {
              $res_user  = $this->db->get_where('user', array('id' => $id_pemesan));
              $name_user = $res_user->row()->name;
              $this->db->insert('log_keranjang', array(
                'insert_by'    => $id_kurir,
                'id_keranjang' => $id_pem,
                'status'       => $stat,
                'status_text'  => $this->txt_stat($stat),
                'message'      => 'User dengan ID ' . $id_kurir . '(' . $kurir_name . ') mengubah status Order dengan kurir ID:' . $id_kurir . '(' . $kurir_name . ') ke keranjang untuk USER_ID ' . $id_pemesan . '(' . $name_user . ') dengan ID Pemesanan ' . $id_pem,
              ));
            } catch (Exception $e) {

            }
          } else {
            echo '[{"status":0,"message":"Error update status pemesanan"}]';
          }
        } else {
          echo '[{"status":-3,"message":"Status pemesanan salah, silahkan coba lagi"}]';
        }

      } else {
        echo '[{"status":-1,"message":"Data tidak ditemukan / pemesanan sudah selesai"}]';
      }
    } else {
      echo '[{"status":-2,"message":"Data inputan tidak lengkap, silahkan coba lagi"}]';
    }

  }

  //########### LOGIN
  public function login()
  {
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $query     = $this->db->query("select * from user where username='" . $username . "' or email='" . $username . "'");
    $jml_order = $this->db->query("select count(id) as jml from pemesanan where status=1 and is_delete=0");

    if (empty($username) || empty($password)) {
      echo '[{"status":"5","message":"Username / Password belum diisi"}]';
    } else {
      if ($query->num_rows() > 0) {

        $s_username = $query->row()->username;
        $s_email    = $query->row()->email;
        $s_password = $query->row()->password;

        if (($username == $s_username || $username == $s_email) && sha1(md5($password)) == $s_password) {
          try {
            $data = json_decode(json_encode($query->result()), true);
          } catch (Exception $e) {

          } finally {
            if ($data[0]['is_delete'] == 0) {
              if ($data[0]['is_active'] == 0) {
                echo '[{"status":"2","message":"Status User masih belum aktif"}]';
              } else {
                $data[0]['status']  = '1';
                $data[0]['message'] = 'Login Success';
                if ($jml_order->num_rows() > 0) {
                  $data[0]['new_order'] = $jml_order->row()->jml;
                }
                echo json_encode($data);
              }
            } else {
              echo '[{"status":"4","message":"User sudah di hapus"}]';
            }
          }

        } else {
          echo '[{"status":"0","message":"Username / Password Salah"}]';
        }
      } else {
        echo '[{"status":"3","message":"User tidak ditemukan"}]';
      }
    }

  }

  public function login_kurir()
  {
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $query     = $this->db->query("select *,(select group_name from `group` where id=u.`group`) as `group` from user as u where (u.username='" . $username . "' or u.email='" . $username . "') and (u.user_type='Kurir' or u.user_type='Super Admin' or u.user_type='Admin TOFAP' or u.user_type='Admin Gudang')");
    $jml_order = $this->db->query("select count(id) as jml from pemesanan where status=1 and is_delete=0");

    if (empty($username) || empty($password)) {
      echo '[{"status":"5","message":"Username / Password belum diisi"}]';
    } else {
      if ($query->num_rows() > 0) {

        $s_username = $query->row()->username;
        $s_email    = $query->row()->email;
        $s_password = $query->row()->password;

        if (($username == $s_username || $username == $s_email) && sha1(md5($password)) == $s_password) {
          try {
            $data = json_decode(json_encode($query->result()), true);
          } catch (Exception $e) {

          } finally {
            if ($data[0]['is_delete'] == 0) {
              if ($data[0]['is_active'] == 0) {
                echo '[{"status":"2","message":"Status User masih belum aktif"}]';
              } else {
                $data[0]['status']  = '1';
                $data[0]['message'] = 'Login Success';
                if ($jml_order->num_rows() > 0) {
                  $data[0]['new_order'] = $jml_order->row()->jml;
                }
                echo json_encode($data);
              }
            } else {
              echo '[{"status":"4","message":"User sudah di hapus"}]';
            }
          }

        } else {
          echo '[{"status":"0","message":"Username / Password Salah"}]';
        }
      } else {
        echo '[{"status":"3","message":"User tidak ditemukan / Type User bukan kurir."}]';
      }
    }

  }

  public function login_karyawan()
  {
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $query     = $this->db->query("select *,(select group_name from `group` where id=u.`group`) as `group` from user as u where (u.username='" . $username . "' or u.email='" . $username . "') and (user_type='Karyawan' or user_type='Super Admin' or user_type='Admin TOFAP')");
    $jml_order = $this->db->query("select count(id) as jml from pemesanan where status=1 and is_delete=0");

    if (empty($username) || empty($password)) {
      echo '[{"status":"5","message":"Username / Password belum diisi"}]';
    } else {
      if ($query->num_rows() > 0) {

        $s_username = $query->row()->username;
        $s_email    = $query->row()->email;
        $s_password = $query->row()->password;

        if (($username == $s_username || $username == $s_email) && sha1(md5($password)) == $s_password) {
          try {
            $data = json_decode(json_encode($query->result()), true);
          } catch (Exception $e) {

          } finally {
            if ($data[0]['is_delete'] == 0) {
              if ($data[0]['is_active'] == 0) {
                echo '[{"status":"2","message":"Status User masih belum aktif"}]';
              } else {
                $data[0]['status']  = '1';
                $data[0]['message'] = 'Login Success';
                if ($jml_order->num_rows() > 0) {
                  $data[0]['new_order'] = $jml_order->row()->jml;
                }
                echo json_encode($data);
              }
            } else {
              echo '[{"status":"4","message":"User sudah di hapus"}]';
            }
          }

        } else {
          echo '[{"status":"0","message":"Username / Password Salah"}]';
        }
      } else {
        echo '[{"status":"3","message":"User tidak ditemukan / Type User bukan karyawan."}]';
      }
    }

  }

  public function ch_pass()
  {
    //$var['user'] = $_SESSION['user_type'];
    $id = $this->input->post('id_user');

    $o = $this->input->post('old');
    $n = $this->input->post('new');

    if (!empty($o) && !empty($n)) {
      $this->db->where('id', $id);
      $this->db->select('password');
      $v_old = $this->db->get('user');

      try {
        $old = $v_old->row()->password;

        if (sha1(md5($o)) == $old) {

          $this->db->where('id', $id);
          $u = $this->db->update('user', array('password' => sha1(md5($n))));
          if ($u) {
            echo '{ "status":"1","message":"Sukses mengubah password" }';
          } else {
            echo '{ "status":"-2","message":"Error mengubah password" }';
          }

        } else {
          echo '{ "status":"-1","message":"Password lama salah" }';
        }
      } catch (Exception $e) {

      }
    } else {
      echo '{ "status":"-3","message":"Password lama & password baru harus diisi" }';
    }

  }

  public function jml_order()
  {
    $query = $this->db->query("select count(id) as jml from pemesanan where status=1 and is_delete=0");
    if ($query->num_rows() > 0) {
      echo '{"jml":' . $query->row()->jml . '}';
    }
  }

  public function add_kurir()
  {
    $id     = $this->input->post('id_kurir');
    $id_pem = $this->input->post('id_pem');
    $data   = array(
      'id'             => $id_pem,
      'status'         => 2,
      'id_kurir'       => $id,
      'add_kurir_date' => date('Y-m-d H:i:s'),
      'add_kurir_by'   => $id,
    );
    $this->db->where('id', $id_pem);
    $act = $this->db->update('pemesanan', $data);

    if ($act) {
      echo json_encode(array('status' => 'success', 'message' => 'Sukses Mengubah Data'));

      //SEND NOTIF
      $this->load->model('model_mobile');
      $dt_pem     = $this->db->get_where('pemesanan', array('id' => $id_pem));
      $id_kurir   = $dt_pem->row()->id_kurir;
      $dt_kurir   = $this->db->get_where('user', array('id' => $id_kurir));
      $kurir_name = $dt_kurir->row()->name;
      $id_pemesan = $dt_pem->row()->id_pemesan;
      $this->model_mobile->send_notif($id_pemesan, 'TOFAP', 'Kurir [' . $kurir_name . '] telah ditambahkan ke Pesanan anda dengan nomor pengambilan ' . $dt_pem->row()->no_pemesanan);
    } else {
      echo json_encode(array('status' => 'error', 'message' => 'Error Mengubah Data, Silahkan coba kembali'));
    }
  }

  public function orderkurir()
  {
    $id_kurir = $this->input->post('id_kurir');

    if (!empty($id_kurir)) {
      $query = $this->db->query("
										select
												p.*,u_k.id as id_kurir,u_k.username,u_k.name,p.status
										FROM
												pemesanan as p
										        join user as u_k on p.id_kurir=u_k.id
										where	p.id_kurir=" . $id_kurir . " AND
												p.is_delete=0 AND
												u_k.is_delete=0 AND
												u_k.is_active=1

										");

      if ($query->num_rows() > 0) {
        echo json_encode($query->result());
      }
    }

  }

  public function pemesanan()
  {
    $id       = $this->input->post('id');
    $id_staff = $this->input->post('id_staff'); //ID Staff
    $mode     = $this->input->post('mode'); //Mode add or edit
    $nomor    = 'PSN-' . time() . '-' . date('Y'); //Nomor Pemesanan
    $items    = $this->input->post('item'); //Bentuk Array

    $this->load->model('model_mobile');
    $ck_staff = $this->model_mobile->ck_staff($id_staff);
    if ($mode == 'add') {
      if (!empty($ck_staff)) {
        try {
          $dt_insert = array(
            'id_pemesan'   => $id_staff,
            'no_pemesanan' => $nomor,
            'insert_by'    => $id_staff,
            'update_by'    => $id_staff,
          );
          $tb_pemesanan = $this->db->insert('pemesanan', $dt_insert);
          if ($tb_pemesanan) {
            $id_pemesanan = $this->db->insert_id();
            foreach ($items as $key => $value) {
              $items[$key]['id_pemesanan'] = $id_pemesanan;
              $items[$key]['insert_by']    = $id_staff;
              $items[$key]['update_by']    = $id_staff;
            }

            $tb_item_pem = $this->db->insert_batch('item_pemesanan', $items);

            if ($tb_item_pem) {
              $this->load->model('model_transaksi');
              $dt_pem = $this->model_transaksi->tb_pemesanan($id_pemesanan);
              if ($dt_pem) {
                $data                  = json_decode(json_encode($dt_pem), true);
                $data[0]['status_php'] = 1;
                $data[0]['message']    = 'Berhasil Menginput Pemesanan';
                echo json_encode($data);
              } else {
                $data[0]['status_php'] = -3;
                $data[0]['message']    = "Kesalahan saat mengambil data Pemesanan";
                echo json_encode($data);
              }
            } else {
              $data[0]['status_php'] = -2;
              $data[0]['message']    = "Kesalahan saat memasukkan Item Pemesanan";
              echo json_encode($data);
            }
          } else {
            $data[0]['status_php'] = -1;
            $data[0]['message']    = "Kesalahan saat memasukkan Pemesanan";
            echo json_encode($data);
          }

        } catch (Exception $e) {
          //echo $e;
        } finally {

        }
      } else {
        $data[0]['status_php'] = -4;
        $data[0]['message']    = "Karyawan tidak ada/bukan karyawan";
        echo json_encode($data);
      }
    } else {
      if ($mode == 'edit') {

      } else {
        $data[0]['status_php'] = -5;
        $data[0]['message']    = "Mode tidak sesuai";
        echo json_encode($data);
      }
    }
  }

  //##################################  PEMESANAN ###############################
  //#### LIST ITEM

  public function ck_pemesanan()
  {
    $pm = $this->db->select('*')->from('item_pemesanan')->get();
    if ($pm->num_rows() > 0) {
      echo json_encode($pm->result());
    }
  }

  public function item()
  {
    $this->load->model('model_produk');
    $this->load->model('model_mobile');
    $dt = [];

    $dt = json_decode(json_encode($this->model_produk->tb_item_is_av()), true);
    // print_r($dt);
    if (!empty($dt)) {
      foreach ($dt as $in => $value) {
        $img                 = json_decode($this->model_mobile->getPhoto($dt[$in]['ID_ITEM']), true);
        $dt[$in]['img_name'] = $img[0]['img_name'] . '?' . time();
      }

      echo json_encode($dt);
    }
  }

  public function trx_pemesanan($mobile = null, $id_p = null)
  {
    $this->load->model('model_transaksi');
    $this->model_transaksi->trx_pemesanan($mobile, $id_p);
    //inputan
    //Example
    // mode:add
    // id_pemesan:84
    // id:
    // nomor:PSN-1515011659-2018
    // item[0][id]:
    // item[0][id_item]:96
    // item[0][qty]:1

    // item[1][id]:
    // item[1][id_item]:97
    // item[1][qty]:1
    // id_user:84
    // id=25 (id pemesanan jika mode = edit)
  }

  public function staff_ls_pemesanan()
  {
    $id = $this->input->post('id');
    $this->load->model('model_transaksi');
    echo json_encode($this->model_transaksi->tb_pemesanan(null, $id));
  }

  public function del_it_pemesanan()
  {
    $id = $this->input->post('id');

    $this->db->where('id', $id);
    $del_stat = $this->db->update('item_pemesanan', array('is_delete' => 1));

    if ($del_stat) {
      echo '{"status":"1","message":"Sukses menghapus item pemesanan"}';
    } else {
      echo '{"status":"-1","message":"Error menghapus item pemesanan"}';
    }
  }

  //################ RATE #############
  public function v_rate()
  {
    $id = $this->input->post('id_kurir');

    $query = $this->db->query('	select
												r.id_kurir,
												u_k.name,
												count(r.id_kurir) as jml_pemesanan,
												avg(r.rate) as rate
									from
												rating as r
												join user as u_k

									where
												r.id_kurir=' . $id . '
												and r.id_kurir=u_k.id');

    $err = $this->db->error();

    if ($query) {
      if ($query->num_rows() > 0) {
        echo json_encode($query->result());
      } else {
        echo json_encode(json_decode('[{"status":"-1","message":"Data Kosong"}]', true));
      }
    } else {
      //echo $err['code'];
      echo json_encode(json_decode('[{"status":"' . $err['code'] . '","message":"' . $err['message'] . '"}]', true));
    }

  }

  public function add_rate()
  {
    $id_kurir     = $this->input->post('id_kurir');
    $id_pemesan   = $this->input->post('id_pemesan');
    $id_pemesanan = $this->input->post('id_pemesanan');
    $rate         = $this->input->post('rate');
    $comment      = $this->input->post('comment');

    if (
      // empty($id_kurir) ||
      empty($id_pemesan) ||
      empty($id_pemesanan) ||
      empty($rate)
    ) {
      echo json_encode(json_decode('[{"status":"-1","message":"Data tidak lengkap, harap isi semua form"}]', true));
      return false;

    } else {

      if (
        // !is_numeric($id_kurir) ||
        !is_numeric($id_pemesan) || !is_numeric($id_pemesanan) || !is_numeric($rate)) {
        echo json_encode(json_decode('[{"status":"-2","message":"Inputan hanya boleh berisi Angka"}]', true));
        return false;
      } else {

        $insert = $this->db->query('insert into rating (id_pemesan,id_pemesanan,rate,comment)
										values('
          . $id_pemesan . ','
          . $id_pemesanan . ','
          . $rate . ',"'
          . $comment . '") on duplicate key update rate=' . $rate . ',comment="' . $comment . '"');
        // $data = array(
        //         'id_kurir' => $id_kurir,
        //         'id_pemesan' => $id_pemesan,
        //         'id_pemesanan' => $id_pemesanan,
        //         'rate' => $rate
        //         );

        // $insert = $this->db->insert('rating',$data);

        $err = $this->db->error();

        if ($insert) {
          echo json_encode(json_decode('[{"status":"1","message":"Berhasil Menginputkan Data"}]', true));

          //TO LOG
          try {
            $res_user  = $this->db->get_where('user', array('id' => $id_pemesan));
            $name_user = $res_user->row()->name;
            $this->db->insert('log_keranjang', array(
              'insert_by'    => $id_pemesan,
              'id_keranjang' => $id_pemesanan,
              'status'       => 5,
              'status_text'  => $this->txt_stat(5),
              'message'      => 'User dengan ID ' . $id_pemesan . '(' . $name_user . ') menambahkan Rating dengan ID Pemesanan ' . $id_pemesanan,
            ));
          } catch (Exception $e) {

          }
        } else {
          echo json_encode(json_decode('[{"status":"' . $err['code'] . '","message":"' . $err['message'] . '"}]', true));
        }

      }
    }

  }

  public function add_rate_gudang()
  {
    $id_kurir          = $this->input->post('id_kurir');
    $id_pemesan        = $this->input->post('id_pemesan');
    $id_pemesanan      = $this->input->post('id_pemesanan');
    $rating_kerapihan  = $this->input->post('rating_kerapihan');
    $rating_kesopanan  = $this->input->post('rating_kesopanan');
    $rating_kebersihan = $this->input->post('rating_kebersihan');
    $comment           = $this->input->post('comment');


    if (
      // empty($id_kurir) ||
      empty($id_pemesan) ||
      empty($id_pemesanan) ||
      empty($rating_kebersihan) ||
      empty($rating_kesopanan) ||
      empty($rating_kerapihan)
    ) {
      echo json_encode(json_decode('[{"status":"-1","message":"Data tidak lengkap, harap isi semua form"}]', true));
      return false;

    } else {

      if (
        // !is_numeric($id_kurir) ||
        !is_numeric($id_pemesan) || !is_numeric($id_pemesanan) || !is_numeric($rating_kebersihan) || !is_numeric($rating_kesopanan) || !is_numeric($rating_kerapihan)) {
        echo json_encode(json_decode('[{"status":"-2","message":"Inputan hanya boleh berisi Angka"}]', true));
        return false;
      } else {

      	$sql_query = 'insert into rating_gudang (id_pemesan,id_pemesanan,rating_kebersihan, rating_kesopanan,rating_kerapihan,comment)
										values('
          . $id_pemesan . ','
          . $id_pemesanan . ','
          . $rating_kebersihan . ','
          . $rating_kesopanan . ','
          . $rating_kerapihan . ',"'
          . $comment . '") on duplicate key update rating_kebersihan=' . $rating_kebersihan . ', rating_kesopanan=' . $rating_kesopanan . ', rating_kerapihan=' . $rating_kerapihan . ',comment="' . $comment . '"';

        $insert = $this->db->query($sql_query);

        $err = $this->db->error();

        if ($insert) {
          echo json_encode(json_decode('[{"status":"1","message":"Berhasil Menginputkan Data"}]', true));

          //TO LOG
          try {
            $res_user  = $this->db->get_where('user', array('id' => $id_pemesan));
            $name_user = $res_user->row()->name;
            $this->db->insert('log_keranjang', array(
              'insert_by'    => $id_pemesan,
              'id_keranjang' => $id_pemesanan,
              'status'       => 5,
              'status_text'  => $this->txt_stat(5),
              'message'      => 'User dengan ID ' . $id_pemesan . '(' . $name_user . ') menambahkan Rating dengan ID Pemesanan ' . $id_pemesanan,
            ));
          } catch (Exception $e) {

          }
        } else {
          echo json_encode(json_decode('[{"status":"' . $err['code'] . '","message":"' . $err['message'] . '"}]', true));
        }

      }
    }

  }

  public function cancel_pem()
  {
    $id = $this->input->post('id');

    $this->load->model('model_transaksi');
    $c = $this->model_transaksi->cancel_pemesanan($id);

    echo $c;

  }

  public function ch_password()
  {
    //$var['user'] = $_SESSION['user_type'];
    $id = $this->input->post('id_user');

    $o = $this->input->post('old');
    $n = $this->input->post('new');

    if (!empty($o) && !empty($n)) {
      $this->db->where('id', $id);
      $this->db->select('password');
      $v_old = $this->db->get('user');

      try {
        $old = $v_old->row()->password;

        if (sha1(md5($o)) == $old) {

          $this->db->where('id', $id);
          $u = $this->db->update('user', array('password' => sha1(md5($n))));
          if ($u) {
            echo '{ "status":"1","message":"Sukses mengubah password" }';
          } else {
            echo '{ "status":"-2","message":"Error mengubah password" }';
          }

        } else {
          echo '{ "status":"-1","message":"Password lama salah" }';
        }
      } catch (Exception $e) {

      }
    } else {
      echo '{ "status":"-3","message":"Password lama & password baru harus diisi" }';
    }
  }

  public function edit_profile()
  {

    //$data = $this->input->post();
    $data['id']        = $this->input->post('id');
    $data['insert_by'] = $this->input->post('id');
    $data['update_by'] = $this->input->post('id');
    $data['name']      = $this->input->post('name');
    //$data['no_pegawai'] = $this->input->post('no_pegawai');
    $u_info    = $this->db->get_where('user', array('id' => $data['id']));
    $user_info = $u_info->row()->user_type;

    if ($user_info == 'Karyawan') {
      // $data['jabatan'] = $this->input->post('jabatan');
      $data['email'] = $this->input->post('email');
    }

    // $data['department'] = $this->input->post('department');
    //$data['user_type'] = $this->input->post('user_type');
    $data['group']  = $this->input->post('group');
    $data['lantai'] = $this->input->post('lantai');
    //$data['username'] = strtolower($data['username']);

    unset($data['password']);

    //print_r($data);

    $this->db->where('id', $data['id']);
    $update = $this->db->update('user', $data);

    $empty = true;
    if ($update) {
      //echo '{"status":"1","message":"Sukses mengubah data"}';
      //print_r($data);

      foreach ($data as $key => $value) {
        if (!empty($value)) {
          $empty = false;
          //echo '{"status":"1","message":"Sukses mengubah data"}';
        } else {
          echo '{"status":-2,"message":"' . strtoupper($key) . ' belum diisi"}';
          $empty = true;
          return false;
        }
      }
      if (!$empty) {
        echo '{"status":1,"message":"Sukses mengubah data"}';
      }
    } else {
      echo '{"status":-1,"message":"Error mengubah data (DB)"}';
    }

  }

  public function search()
  {
    $id   = $this->input->post('id');
    $text = $this->input->post('text');

    if (!empty($id)) {
      if (!empty($text)) {
        $status = ['Waiting Approval', 'Order Received', 'Courier Assigned', 'Prepare Item', 'Courier On The Way', 'Done', 'Cancel'];

        $this->load->model('Model_mobile');
        $data = $this->Model_mobile->search_pemesanan($id, $text);

        $dt = json_decode(json_encode($data), true);

        if (!empty($dt)) {
          foreach ($dt as $key => $value) {
            //$dt[$key]['item_list']='';
            $dt[$key]['txt_stat'] = $status[$dt[$key]['status']];
            $str                  = '';
            $item                 = $this->Model_mobile->ls_it_pemesanan($dt[$key]['id_pemesanan']);
            $rate                 = $this->Model_mobile->ck_rate($dt[$key]['id_pemesanan']);
            //print_r($item);
            if ($item) {
              foreach ($item as $in => $value) {

                $str .= ',' . $value->item_name;
                $f_str                 = substr($str, 1);
                $dt[$key]['item_list'] = $f_str;

              }
            } else {

            }

            $dt[$key]['rate'] = $rate[0];

          }
          echo json_encode($dt);
        }
      }
    } else {
      echo '{ "status":"-1","message":"ID Kosong" }';
    }

  }

  public function search_item()
  {
    $text = $this->input->post('text');
    // $query = $this->db->query("select * from pos_item where item_name like '%" . $text . "%' and is_delete=0");
    $this->load->model('model_produk');
    $this->load->model('model_mobile');

    $dt = [];
    $dt = json_decode(json_encode($this->model_produk->tb_item_is_av($text)), true);

    foreach ($dt as $in => $value) {
      $img                 = json_decode($this->model_mobile->getPhoto($dt[$in]['ID_ITEM']), true);
      $dt[$in]['img_name'] = $img[0]['img_name'];
    }

    if (!empty($dt)) {
      echo json_encode($dt);
    }
  }

  public function all_group()
  {
    $this->load->model('model_customer');
    $group = $this->model_customer->group();

    echo json_encode($group);
  }

  // for mobile, push notification purpose
  public function save_token()
  {
    $id    = $this->input->post('id');
    $token = $this->input->post('token');

    if (!empty($id) && !empty($token)) {
      $user = $this->db->get_where('user', array('id' => $id));
      if ($user->num_rows() > 0) {
        $this->db->where('id', $user->row()->id);
        $update = $this->db->update('user', array('token' => $token));

        if ($update) {
          echo json_encode(array('status' => '1', 'message' => 'Sukses simpan token fcm'));
        } else {
          echo json_encode(array('status' => '-1', 'message' => 'Gagal simpan token'));
        }
      } else {
        echo json_encode(array('status' => '-2', 'message' => 'Email tidak terdaftar'));
      }

    } else {
      echo json_encode(array('status' => '-3', 'message' => 'Email atau token kosong'));
    }
  }

  // ini hanya contoh saja
  // public function send_notif($id=null,$title=null,$message=null){
  //   $user = $this->db->get_where('user',array('id'=>$id));
  //   $token = $user->row()->token;

  //   echo json_encode($this->kirimfcm->kirim(array('reg_id'=>$token,'title'=>$title,'message'=>$message)));
  // }

  public function clear_token()
  {
    $id = $this->input->post('id');

    if (!empty($id)) {
      $user = $this->db->get_where('user', array('id' => $id));
      if ($user->num_rows() > 0) {
        $this->db->where('id', $user->row()->id);
        $update = $this->db->update('user', array('token' => null));

        if ($update) {
          echo json_encode(array('status' => '1', 'message' => 'Sukses menghapus token'));
        } else {
          echo json_encode(array('status' => '-1', 'message' => 'Gagal hepus token'));
        }
      } else {
        echo json_encode(array('status' => '-2', 'message' => 'ID tidak terdaftar'));
      }

    } else {
      echo json_encode(array('status' => '-3', 'message' => 'ID kosong'));
    }
  }

  public function btn_pemesanan()
  {
    $var['id_pem']       = $_GET['id_pem'];
    $var['user']         = $_GET['user_type'];
    $var['s']            = $_GET['status'];
    $var['id_kurir']     = $_GET['id_kurir'];
    $var['no_pemesanan'] = $_GET['no_pem'];
    // $var['value']->id_kurir=$_POST['id_kurir'];
    $this->load->view('view-pemesanan_btn_act', $var);
  }

  public function acc_order_from_apk()
  {
    $SESI = array('id_user' => '', 'name' => '');

    // $id_approval = $_POST['id_app'];
    $id_approval = !empty($_POST['id_app']) ? $_POST['id_app'] : '';

    if (!empty($id_approval)) {
      $ck_user = $this->db->query('select * from user where id=' . $id_approval);
      if (!empty($ck_user) && $ck_user->num_rows() > 0) {
        $SESI['id_user'] = $id_approval;
        $SESI['name']    = $ck_user->row()->name;
      }
    }

    // echo $SESI['id_user'];
    $id            = $this->input->post('id');
    $id_pem_to_log = $id;
    $result        = $this->db->get_where('pemesanan', array('id' => $id));
    $g_id_pemesan  = $result->row()->id_pemesan;

    if ($result && $result->num_rows() > 0) {
      if ($result->row()->status >= 1) {
        if ($result->row()->status == 6) {
          echo json_encode(array('status' => 'success', 'message' => 'Pemesanan Telah dibatalkan sebelumnya. Status saat ini adalah "' . $this->txt_stat($result->row()->status) . '".'));
          return false;
        } else {
          echo json_encode(array('status' => 'success', 'message' => 'Pemesanan Sudah disetujui sebelumnya. Status saat ini adalah "' . $this->txt_stat($result->row()->status) . '".'));
          return false;
        }
      }
    }

    $this->load->model('model_transaksi');
    $ls_item = json_decode(json_encode($this->model_transaksi->list_item_pemesanan($id)), true);

    $stat = 0;

    $error_message = array('status' => 'error', 'message' => []);

    foreach ($ls_item as $key => $val) {
      $stock_item = $this->db->select('id,qty,barcode,item_name')->where('id', $val['id_item'])->get('pos_item');
      if ((int) $stock_item->row()->qty <= 0) {
        array_push($error_message['message'], array('status' => -1, 'message' => 'Stock item ' . $val['item_name'] . ' dengan barcode ' . $val['barcode'] . ' tidak tersedia.'));
      } else {
        if ((int) $stock_item->row()->qty < $val['qty']) {
          array_push($error_message['message'], array('status' => -2, 'message' => 'Stock item ' . $val['item_name'] . ' saat ini dengan barcode ' . $val['barcode'] . ' kurang dari jumlah pemesanan.'));
        } else {
          $stat++;

        }
      }
    }

    if ($stat == count($ls_item)) {

      foreach ($ls_item as $k => $v) {
        $this->db->where('id', $v['id_it_pn']);
        $act_it_penerimaan = $this->db->update('item_pemesanan', array('h_stock' => $v['qty_item']));

        if ($act_it_penerimaan) {
          $this->db->set('qty', 'qty-' . $v['qty'], false);
          $this->db->where('id', $v['id_item']);
          $act_item = $this->db->update('pos_item');
        }
      }

      $data = array(
        'status' => 1,
      );
      $this->db->where('id', $id);
      $act = $this->db->update('pemesanan', $data);

      if ($act) {
        echo json_encode(array('status' => 'success', 'message' => 'Sukses Mengubah Data'));

        //SEND NOTIF
        try {
          $this->load->model('model_mobile');
          $dt_pem     = $this->db->get_where('pemesanan', array('id' => $id));
          $id_pemesan = $dt_pem->row()->id_pemesan;
          $this->model_mobile->send_notif($id_pemesan, 'TOFAP', 'Pesanan anda dengan nomor pengambilan ' . $dt_pem->row()->no_pemesanan . ' telah disetujui.');
        } catch (Exception $e) {

        }

        //TO LOG
        try {
          $res_user  = $this->db->get_where('user', array('id' => $id_pemesan));
          $name_user = $res_user->row()->name;
          $this->db->insert('log_keranjang', array(
            'insert_by'    => $SESI['id_user'],
            'id_keranjang' => $id,
            'status'       => 1,
            'status_text'  => $this->txt_stat(1),
            'message'      => 'User dengan ID ' . $SESI['id_user'] . '(' . $SESI['name'] . ') menyetujui pemesanan keranjang untuk USER_ID ' . $id_pemesan . '(' . $name_user . ') dengan ID Pemesanan ' . $id,
          ));

          $it_pem_to_log = $this->db->get_where('item_pemesanan', array('id_pemesanan' => $id_pem_to_log));

          foreach ($it_pem_to_log->result() as $i => $v) {
            $getStock = $this->db->get_where('pos_item', array('id' => $v->id_item));
            // print_r($getStock->row()->stock_awal);
            $data = array(
              'id_pemesan'     => $g_id_pemesan,
              'id_item'        => $v->id_item,
              'stock_awal'     => $getStock->row()->stock_awal,
              'stock_terakhir' => $v->h_stock,
              'action'         => 'KURANGI',
              'qty'            => $v->qty,
              'is_android'     => 0,
              'parent'         => 'KERANJANG',
              'id_parent'      => $v->id_pemesanan,
              'trigger'        => 'ITEM PEMESANAN',
              'id_trigger'     => $v->id,
              'insert_by'      => $SESI['id_user'],
              'update_by'      => $SESI['id_user'],
            );

            $insert = $this->db->insert('log_item', $data);
          }

        } catch (Exception $e) {

        }
      } else {
        echo json_encode(array('status' => 'error', 'message' => array(0 => array('status' => 'error', 'message' => 'Error DB. Err Code (7676)'))));
      }
    } else {
      echo json_encode($error_message);
    }

  }

}
