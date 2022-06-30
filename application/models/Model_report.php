<?php
class Model_report extends CI_Model
{

  private $SQL_LOG_ITEM = '
	SELECT
	  li.id_item,
	  li.stock_awal,
	  ispb.`harga`,
	  pi.item_name,
	  pi.`barcode`,
	  IF(li.`action` = "TAMBAH", li.`qty`, 0) AS item_masuk,
	  IF(li.`action` = "KURANGI", li.`qty`, 0) AS item_keluar,
	  pi.`qty` AS sisa,
	  spb.`no_spb`,
	  li.`insert_date`,
	  b.`bulan`,
	  p.`no_pemesanan` AS no_pengambilan,
	  p.`tgl_pemesanan` AS tgl_pengambilan,
	  u.`name` AS nama_karyawan,
	  g.`group_name` AS `group`,
	  uk.`name` AS nama_kurir,
	  p.status,
	  IF(p.status = 5, "DONE", NULL) AS status_text,
	  li.`parent`,
	  li.`trigger`,
	  li.`update_date`,
	  ip.`is_delete`
	FROM
	  log_item li
	  LEFT JOIN item_spb ispb
	    ON ispb.`id` = li.`id_trigger`
	    AND li.`parent` = "PENERIMAAN"
	  LEFT JOIN pos_item `pi`
	    ON pi.id = li.`id_item`
	  LEFT JOIN spb
	    ON spb.`id` = li.`id_parent`
	    AND li.`parent` = "PENERIMAAN"
	    AND li.`trigger` = "ITEM SPB"
	  LEFT JOIN pemesanan p
	    ON p.`id` = li.`id_parent`
	    AND li.`parent` = "KERANJANG"
	    AND li.`trigger` = "ITEM PEMESANAN"
	  LEFT JOIN `user` u
	    ON u.`id` = p.`id_pemesan`
	  LEFT JOIN `group` g
	    ON g.`id` = u.`group`
	  LEFT JOIN `user` uk
	    ON uk.`id` = p.`id_kurir`
	  LEFT JOIN item_pemesanan ip
	    ON ip.id = li.`id_trigger`
	    AND li.`parent` = "KERANJANG"
	    AND li.`trigger` = "ITEM PEMESANAN"
	  LEFT JOIN bulan b ON b.`id` = MONTH(li.`insert_date`)
	  WHERE
	  (
	((p.`status` = 5 OR (p.`status` > 0 AND p.`status` < 5)) AND li.`parent` = "KERANJANG")
	OR
	(li.parent = "PENERIMAAN" AND li.trigger = "ITEM SPB" )
	  )
	  AND (
	    ip.is_delete = 0
	    OR ISNULL(ip.is_delete)
	  )
	ORDER BY li.`update_date` ASC
	';

  public function __construct()
  {
    parent::__construct();
  }

  public function log_items($id = null, $start_date, $end_date)
  {
  	$end_date = 'WHERE insert_date >= "' . $start_date . '" AND insert_date <= "' . $end_date . '"';
  	$id = !empty($id) ? (' AND id_item IN (' . (is_array($id) ? implode(',', $id) : $id) . ')') : '';

  	$sql = "SELECT
							*
						FROM (
						$this->SQL_LOG_ITEM
						) AS t $end_date $id
						";

    // print_r($sql); exit;
    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
    	// $fix_result = [];
    	// foreach ($query->result() as $key => $value) {
    	// 	$fix_result[$value->id_item][] = $value;
    	// }
     //  return $fix_result;
      return $query->result();
    } else {
      return [];
    }
  }

  public function log_spb_items($id = null, $start_date)
  {
    // $start_date = 'WHERE insert_date >= "' . $start_date . '"';
    $id = !empty($id) ? (' (' . (is_array($id) ? implode(',', $id) : $id) . ')') : '';

    $sql = "
    SELECT
      li.id_item,
      li.stock_awal,
      ispb.`harga`,
      IF(li.`action` = 'TAMBAH', li.`qty`, 0) AS item_masuk,
      IF(li.`action` = 'KURANGI', li.`qty`, 0) AS item_keluar,
      spb.`no_spb`,
      li.`insert_date`,
      li.`parent`,
      li.`trigger`,
      li.`update_date`
    FROM
      log_item li
      LEFT JOIN item_spb ispb
        ON ispb.`id` = li.`id_trigger`
        AND li.`parent` = 'PENERIMAAN'
      LEFT JOIN pos_item `pi`
        ON pi.id = li.`id_item`
      LEFT JOIN spb
        ON spb.`id` = li.`id_parent`
        AND li.`parent` = 'PENERIMAAN'
        AND li.`trigger` = 'ITEM SPB'
      WHERE li.parent = 'PENERIMAAN' AND li.trigger = 'ITEM SPB' AND li.id_item IN $id AND li.insert_date < '$start_date'
    ORDER BY li.`update_date` ASC
            ";

    // print_r($sql); exit;
    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
      $fix_result = [];
      foreach ($query->result() as $key => $value) {
       $fix_result[$value->id_item][] = $value;
      }
      return $fix_result;
      // return $query->result();
    } else {
      return [];
    }
  }

  public function summary_item_keluar($id = null, $start_date)
  {
    // $start_date = 'WHERE insert_date >= "' . $start_date . '"';
    $id = !empty($id) ? (' (' . (is_array($id) ? implode(',', $id) : $id) . ')') : '';

    $sql = "
    SELECT 
    id_item
    , SUM(item_masuk) AS total_masuk
    , SUM(item_keluar) AS total_keluar
    FROM
    (
    SELECT
      li.id_item,
      li.stock_awal,
      IF(li.`action` = 'TAMBAH', li.`qty`, 0) AS item_masuk,
      IF(li.`action` = 'KURANGI', li.`qty`, 0) AS item_keluar,
      li.`insert_date`,
      b.`bulan`,
      p.`no_pemesanan` AS no_pengambilan,
      p.`tgl_pemesanan` AS tgl_pengambilan,
      p.status,
      IF(p.status = 5, 'DONE', NULL) AS status_text,
      li.`parent`,
      li.`trigger`,
      li.`update_date`,
      ip.`is_delete`
    FROM
      log_item li
      LEFT JOIN pemesanan p
        ON p.`id` = li.`id_parent`
        AND li.`parent` = 'KERANJANG'
        AND li.`trigger` = 'ITEM PEMESANAN'
      LEFT JOIN item_pemesanan ip
        ON ip.id = li.`id_trigger`
        AND li.`parent` = 'KERANJANG'
        AND li.`trigger` = 'ITEM PEMESANAN'
      LEFT JOIN bulan b ON b.`id` = MONTH(li.`insert_date`)
      WHERE
      (
    ((p.`status` = 5 OR (p.`status` > 0 AND p.`status` < 5)) AND li.`parent` = 'KERANJANG')
      )
      AND (
        ip.is_delete = 0
        OR ISNULL(ip.is_delete)
      )
    ORDER BY li.`update_date` ASC 
    ) AS t 
    WHERE t.insert_date < '$start_date' AND t.id_item IN $id
    GROUP BY id_item
            ";

    // print_r($sql); exit;
    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
      $fix_result = [];
      foreach ($query->result() as $key => $value) {
       $fix_result[$value->id_item] = $value;
      }
      return $fix_result;
      // return $query->result();
    } else {
      return [];
    }
  }

  public function report_log_item($id = null, $tahun = null)
  {

    if (empty($id)) {
      return false;
    }
    $th = " and year(insert_date)=" . date('Y');
    if (!empty($tahun)) {
      $th = ' and year(insert_date)=' . $tahun;
    }
    // $sql = "select *,(select qty from pos_item where id=it.id_item) as sisa,(select bulan from bulan where id=month(it.insert_date)) as `bulan`,if(it.`status`=5,'DONE',NULL) as status_text from (
    //           select
    //             id_item,
    //             stock_awal,
    //             (select harga from item_spb where id=id_trigger and l.parent='PENERIMAAN') as harga_spb,
    //             (select item_name from pos_item where id=id_item) as item_name,
    //             (select barcode from pos_item where id=id_item) as barcode,
    //             (select no_spb as no_spb from spb where id=l.id_parent and l.parent='PENERIMAAN' and l.`trigger`='ITEM SPB') as no_spb,
    //             insert_date,
    //             (select no_pemesanan from pemesanan where id=l.id_parent and l.parent='KERANJANG' and l.`trigger`='ITEM PEMESANAN') as no_pengambilan,
    //             (select tgl_pemesanan from pemesanan where id=l.id_parent and l.parent='KERANJANG' and l.`trigger`='ITEM PEMESANAN') as tgl_pengambilan,
    //             (select
    //                   u.name
    //               from
    //                   pemesanan as p
    //                   join `user` as u on u.id=p.id_pemesan
    //               where
    //                   p.id=l.id_parent
    //                   and l.parent='KERANJANG'
    //                   and l.`trigger`='ITEM PEMESANAN') as nama_karyawan,

    //             (select
    //                   g.group_name
    //               from
    //                   pemesanan as p
    //                   join `user` as u on u.id=p.id_pemesan
    //                   join `group` as g on g.id=u.`group`
    //               where
    //                   p.id=l.id_parent
    //                   and l.parent='KERANJANG'
    //                   and l.`trigger`='ITEM PEMESANAN') as `group`,

    //             (select
    //                   u_k.name
    //               from
    //                   pemesanan as p
    //                   join `user` as u_k on u_k.id=p.id_kurir
    //               where
    //                   p.id=l.id_parent
    //                   and l.parent='KERANJANG'
    //                   and l.`trigger`='ITEM PEMESANAN') as nama_kurir,

    //             (select
    //                   p.`status`
    //               from
    //                   pemesanan as p
    //               where
    //                   p.id=l.id_parent
    //                   and l.parent='KERANJANG'
    //                   and l.`trigger`='ITEM PEMESANAN') as `status`,

    //             (select qty from log_item where id=l.id and `action`='TAMBAH') as item_masuk,
    //             (select qty from log_item where id=l.id and `action`='KURANGI') as item_keluar,
    //             parent,
    //             `trigger`,update_date,
    //             (select
    //                   is_delete
    //               from
    //                   item_pemesanan as ip
    //               where
    //                   ip.id=l.id_trigger
    //                   and l.parent='KERANJANG'
    //                   and l.`trigger`='ITEM PEMESANAN') as `is_delete`
    //           from log_item as l
    //           ) as it where (((it.`status`=5 or (it.`status`>0 and it.`status`<5)) and it.parent='KERANJANG') or (it.parent='PENERIMAAN' and it.trigger='ITEM SPB')) and id_item=".$id . $th . " and (it.is_delete=0 or isnull(is_delete)) order by update_date asc";

    // Edit query SQL
    $sql = 'SELECT
						  *
						FROM(' . $this->SQL_LOG_ITEM . ') AS it
						WHERE id_item = ' . $id . $th;
    // echo $sql;
    // exit;
    $rep = $this->db->query($sql);

    if ($rep->num_rows() > 0) {
      return $rep->result();
    } else {
      return null;
    }
  }

  public function summary_log_item($id_item = null)
  {
    // $start_date = 'WHERE insert_date >= "' . $start_date . '"';
    $id = !empty($id_item) ? (' (' . (is_array($id_item) ? implode(',', $id_item) : $id_item) . ')') : '';


    $sql = "
          SELECT 
            pim.id AS id_item
            ,pim.qty
            ,t.sisa
            ,t.total_keluar
            ,t.total_masuk
            ,CASE WHEN MIN(h.insert_date) THEN harga END AS harga
            FROM pos_item pim
            LEFT JOIN 
            (
              SELECT
                id_item,
                sisa,
                SUM(item_keluar) AS total_keluar,
                SUM(item_masuk) AS total_masuk
              FROM (
              $this->SQL_LOG_ITEM
              ) AS t WHERE t.id_item IN $id
              GROUP BY t.id_item
            ) AS t ON t.id_item = pim.id
            LEFT JOIN harga h ON h.`id_item` = pim.id 
            WHERE pim.id IN $id
            GROUP BY pim.id
						";

    // print_r($sql); exit;
    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
    	$fix_result = [];
    	foreach ($query->result() as $key => $value) {
        $value->qty_awal = $value->qty + $value->total_keluar - $value->total_masuk;
        // $value->harga = 47000;
    		$fix_result[$value->id_item] = $value;
    	}
      return $fix_result;
    } else {
      return null;
    }
  }

  public function log_by_kar($by = null, $bulan = null, $tahun = null, $group = null)
  {
    $set_SQL = $this->db->query('set global sql_mode="NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"');


    if ($set_SQL) {
      if ($_GET['periode'] == 'Harian') {
        $date = str_replace('/', '-', $_GET['tanggal']);
        $periode = ' AND DATE(a.date) = "' . date('Y-m-d', strtotime($date)) . '"';
      } else {
        $bulan = !empty($bulan) || $bulan != 0 ? "and month(a.`date`)=" . $bulan : "";
        $tahun = !empty($tahun) || $tahun != 0 ? "and year(a.`date`)=" . $tahun : "";
        $periode = $bulan . ' ' . $tahun;
      }
      $by    = !empty($by) || $by != 0 ? "and id_pemesan=" . $by : "";
      $group = !empty($group) || $group != 0 ? "and a.id_group=" . $group : "";

      $sql = "  select
                      month(a.`date`) as bulan,
                      year(a.`date`) as tahun,
                      a.`date` as update_date,
                      a.id_pemesan_from_pemesanan as id_pemesan,
                      a.id_item,
                      a.item_name,
                      a.satuan as item_satuan,
                      a.qty,
                      a.id_parent,
                      a.log_id,
                      sum(a.qty) as total,
                      (select name from user where id=a.id_pemesan_from_pemesanan) as `name`,
                      (select group_name from `group` as g join user as ug on ug.`group`=g.id where ug.id=a.id_pemesan_from_pemesanan) as group_name,
                      a.id_group

                from (
                    select

                                    li.*,
                          li.id as log_id,
                          i.item_name,
                          i.satuan,
                          (select `status` from pemesanan where li.id_parent=id) as `status_pemesanan`,
                          (select `update_date` from pemesanan where li.id_parent=id) as `date`,
                          (select `id_pemesan` from pemesanan where li.id_parent=id) as `id_pemesan_from_pemesanan`,
                          (select g.id from `group` as g join user as ug on ug.`group`=g.id where ug.id=id_pemesan_from_pemesanan) as id_group
                    from
                          log_item as li
                          join pos_item as i on li.id_item=i.id
                          join item_pemesanan as ip on ip.id=li.id_trigger
                    where

                          li.parent='KERANJANG'
                          and li.`trigger`='ITEM PEMESANAN'
                          and li.`action`='KURANGI'
                          and ip.is_delete=0
                ) as a


                where
                    a.status_pemesanan=5 " . $by . " " . $periode . " " . $group . " group by a.id_item order by a.item_name asc";

      // echo "$sql";exit;

      $query = $this->db->query($sql);

      // echo "  select
      //                 month(a.`date`) as bulan,
      //                 year(a.`date`) as tahun,
      //                 a.`date` as update_date,
      //                 a.id_pemesan_from_pemesanan as id_pemesan,
      //                 a.id_item,
      //                 a.item_name,
      //                 a.qty,
      //                 a.id_parent,
      //                 a.log_id,
      //                 sum(a.qty) as total,
      //                 (select name from user where id=a.id_pemesan_from_pemesanan) as `name`,
      //                 (select group_name from `group` as g join user as ug on ug.`group`=g.id where ug.id=a.id_pemesan_from_pemesanan) as group_name,
      //                 a.id_group

      //           from (
      //               select

      //                               li.*,
      //                     li.id as log_id,
      //                     i.item_name,
      //                     (select `status` from pemesanan where li.id_parent=id) as `status_pemesanan`,
      //                     (select `update_date` from pemesanan where li.id_parent=id) as `date`,
      //                     (select `id_pemesan` from pemesanan where li.id_parent=id) as `id_pemesan_from_pemesanan`,
      //                     (select g.id from `group` as g join user as ug on ug.`group`=g.id where ug.id=id_pemesan_from_pemesanan) as id_group
      //               from
      //                     log_item as li
      //                     join pos_item as i on li.id_item=i.id
      //               where

      //                     li.parent='KERANJANG'
      //                     and li.`trigger`='ITEM PEMESANAN'
      //                     and li.`action`='KURANGI'
      //           ) as a

      //           where
      //               a.status_pemesanan=5 ".$by." " . $bulan . " " . $tahun . " " . $group . " group by a.id_item order by a.item_name desc";

      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return null;
      }
    } else {
      echo "Error SQL Mode.";
    }
  }

  public function info_user_log_karyawan($id = null)
  {
    $query = $this->db->query('select u.name,u.`group`,g.group_name from `user` as u join `group` as g on g.id=u.`group` where u.id=' . $id);

    if (!empty($query)) {

      if ($query->num_rows() > 0) {
        // print_r($query->result());
        return $query->result();
      } else {
        return null;
      }
    }

  }
  public function all_user()
  {
    $query = $this->db->query('select
										u.id,
										u.name,
										g.group_name
									from
										user as u
										join `group` as g on g.id=u.`group`
									where
										u.user_type="Karyawan"
										and u.is_delete=0
										and u.is_active=1
									order by u.name asc');

    if (!empty($query)) {

      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return null;
      }
    }

  }

  public function fifo_func_1($id = null)
  {
    $query = $this->db->query("select
							`is`.id_item,
							i.item_name,
							`is`.harga,s.no_spb,`is`.is_delete,s.`status`

				from
							item_spb as `is`
							join spb as s on `is`.id_spb=s.id
							join pos_item as i on `is`.id_item=i.id
				where
							s.is_delete=0
							and `is`.is_delete=0
							and `is`.id_item=" . $id . "
				limit 1");

    if (!empty($query)) {
      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return null;
      }
    }
  }

  public function fifo_func_2($id = null, $month = null)
  {
    $m = '';
    if (!empty($month)) {
      $m = "and month(s.insert_date)=" . $month;
    }
    $query = $this->db->query("
				select
							`is`.id_item,
							i.item_name,
							`is`.harga,s.no_spb,
							`is`.qty,
							`is`.qty_masuk,
							`is`.is_delete,
							s.`status`,
							month(s.insert_date) as bulan

				from
							item_spb as `is`
							join spb as s on `is`.id_spb=s.id
							join pos_item as i on `is`.id_item=i.id
				where
							s.is_delete=0
							" . $m . "
							and `is`.is_delete=0
							and `is`.id_item=" . $id);

    if (!empty($query)) {
      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return null;
      }
    }
  }

  public function fifo_func_3($id = null, $month = null)
  {
    $m = '';
    if (!empty($month)) {
      $m = "AND a.bulan=" . $month;
    }
    $query = $this->db->query("
				select
						sum(a.qty) as qty_keluar,a.bulan
				from (
						select
									l.id_item,
									l.qty,
									p.`status`,
									month(l.update_date) as bulan
						from
									log_item as l
									join item_pemesanan as ip on l.id_trigger=ip.id and l.`parent`='KERANJANG' and ip.is_delete=0
									join pemesanan as p on ip.id_pemesanan=p.id and l.`parent`='KERANJANG' and p.is_delete=0
						where
									ip.id_item=" . $id . "
						) as a
				where
						a.`status`=5 " .
      $m);
    // echo $query;
    if (!empty($query)) {
      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return null;
      }
    }
  }

  public function fifo_func_hrg_aw($id = null)
  {
    $query = $this->db->query("select harga from harga where id_item=" . $id . " and is_delete=0 limit 1");
    // echo $query;
    if (!empty($query)) {
      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return null;
      }
    }
  }

  public function sla($id_user = null, $startACT = 0, $endACT = 5, $tglStart = null, $tglEnd = 'now()')
  {
    $act = ['waiting_approval', 'order_received', 'courier_assigned', 'prepare_item', 'courier_on_the_way', 'done'];

    $stat = ['10', '10', '10', '10', '10', '10'];
    for ($i = $startACT; $i <= $endACT; $i++) {
      $stat[$i] = $i;
    }


    $user = '';
    if ($id_user != null) {
      $user = "and (pem.id_kurir=" . $id_user . " or (l.insert_by=" . $id_user . " and l.`status`=1))";
    }

    $query_syntax = "select pem.*, time_to_sec(convert(concat(
          timestampdiff(hour,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),':',
          mod(timestampdiff(minute,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),60),':',
          mod(timestampdiff(second,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),60)
        ),time)) as sla_sec, concat(
          timestampdiff(day,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),'hari ',
          mod(timestampdiff(hour,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),24),'j:',
          mod(timestampdiff(minute,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),60),'m:',
          mod(timestampdiff(second,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),60),'d'
        ) as service_level from (
          SELECT
          p.`id`
          ,p.status
          ,p.`is_delete`
          ,p.`no_pemesanan`
          ,p.`id_kurir`
          ,uk.`name` AS kurir
          ,MAX(CASE WHEN lk.status = 1 THEN lk.insert_by END) AS approve_by
          ,MAX(CASE WHEN lk.`status` = " . $stat[0] . " THEN lk.insert_date END) AS waiting_approval
          ,MAX(CASE WHEN lk.`status` = " . $stat[1] . " THEN lk.insert_date END) AS order_received
          ,MAX(CASE WHEN lk.`status` = " . $stat[2] . " THEN lk.insert_date END) AS courier_assigned
          ,MAX(CASE WHEN lk.`status` = " . $stat[3] . " THEN lk.insert_date END) AS prepare_item
          ,MAX(CASE WHEN lk.`status` = " . $stat[4] . " THEN lk.insert_date END) AS courier_on_the_way
          ,MIN(CASE WHEN lk.`status` = " . $stat[5] . " THEN lk.insert_date END) AS done
          ,MAX(CASE WHEN lk.`status` = 5 AND message LIKE  '%Rating%' THEN lk.insert_date END) AS rating
          ,MAX(CASE WHEN lk.`status` = 6 THEN lk.insert_date END) AS cancel
          FROM log_keranjang lk
          LEFT JOIN pemesanan p ON p.`id` = lk.`id_keranjang`
          LEFT JOIN `user` uk ON uk.`id` = p.`id_kurir`
          GROUP BY id_keranjang
          ) as pem join log_keranjang as l on pem.id=l.id_keranjang
        where
            (pem.`status`=5 and pem.is_delete=0)
            and ((l.`status` between " . $startACT . " and " . $endACT . ") and (l.insert_date between convert('" . $tglStart . "',datetime) and convert('" . $tglEnd . "',datetime)))
              " . $user . "
        group by pem.id";
    // echo $query_syntax;
    // exit;

    $query = $this->db
      ->query($query_syntax);

    if (!empty($query)) {
      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return null;
      }
    }
  }

  // public function sla($id_user = null, $startACT = 0, $endACT = 5, $tglStart = null, $tglEnd = 'now()')
  // {
  //   $act = ['waiting_approval', 'order_received', 'courier_assigned', 'prepare_item', 'courier_on_the_way', 'done'];

  //   $stat = ['10', '10', '10', '10', '10', '10'];
  //   for ($i = $startACT; $i <= $endACT; $i++) {
  //     $stat[$i] = $i;
  //   }


  //   $user = '';
  //   if ($id_user != null) {
  //     $user = "and (pem.id_kurir=" . $id_user . " or (l.insert_by=" . $id_user . " and l.`status`=1))";
  //   }

  //   $query_syntax = "select pem.*, time_to_sec(convert(concat(
  //         timestampdiff(hour,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),':',
  //         mod(timestampdiff(minute,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),60),':',
  //         mod(timestampdiff(second,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),60)
  //       ),time)) as sla_sec, concat(
  //         timestampdiff(day,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),'hari ',
  //         mod(timestampdiff(hour,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),24),'j:',
  //         mod(timestampdiff(minute,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),60),'m:',
  //         mod(timestampdiff(second,pem." . $act[$startACT] . ",pem." . $act[$endACT] . "),60),'d'
  //       ) as service_level from (
  //         select
  //           p.id,
  //           p.`status`,
  //           p.`is_delete`,
  //           p.no_pemesanan,
  //           (select name from user as uk where uk.id=p.id_kurir) as kurir,
  //           (select id from user as uid where uid.id=p.id_kurir) as id_kurir,
  //           (select ua.name from log_keranjang as l join user as ua on l.insert_by=ua.id where l.id_keranjang=p.id and `status`=1) as `id_approve_by`,
  //           (select insert_by from log_keranjang where id_keranjang=p.id and `status`=1) as `approve_by`,
  //           (select insert_date from log_keranjang where id_keranjang=p.id and `status`=" . $stat[0] . " limit 1) as `waiting_approval`,
  //           (select insert_date from log_keranjang where id_keranjang=p.id and `status`=" . $stat[1] . ") as `order_received`,
  //           (select insert_date from log_keranjang where id_keranjang=p.id and `status`=" . $stat[2] . ") as `courier_assigned`,
  //           (select insert_date from log_keranjang where id_keranjang=p.id and `status`=" . $stat[3] . " limit 1) as `prepare_item`,
  //           (select insert_date from log_keranjang where id_keranjang=p.id and `status`=" . $stat[4] . " limit 1) as `courier_on_the_way`,
  //           (select insert_date from log_keranjang where id_keranjang=p.id and `status`=" . $stat[5] . " limit 1) as `done`,
  //           (select insert_date from log_keranjang where id_keranjang=p.id and `status`=5 and `message` like '%Rating%' limit 1) as `rating`,
  //           (select insert_date from log_keranjang where id_keranjang=p.id and `status`=6 limit 1) as `cancel` from pemesanan as p) as pem join log_keranjang as l on pem.id=l.id_keranjang
  //       where
  //           (pem.`status`=5 and pem.is_delete=0)
  //           and ((l.`status` between " . $startACT . " and " . $endACT . ") and (l.insert_date between convert('" . $tglStart . "',datetime) and convert('" . $tglEnd . "',datetime)))
  //             " . $user . "
  //       group by pem.id";
  //   echo $query_syntax;
  //   exit;

  //   $query = $this->db
  //     ->query($query_syntax);

  //   if (!empty($query)) {
  //     if ($query->num_rows() > 0) {
  //       return $query->result();
  //     } else {
  //       return null;
  //     }
  //   }
  // }

  public function user_sla()
  {
    $query = $this->db->query("select id,name,user_type from user where is_delete=0 and is_active=1 and (user_type='Admin Gudang' or user_type='Kurir')");

    if (!empty($query)) {
      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return null;
      }
    }
  }

  public function user_sla_penerimaan()
  {
    $query = $this->db->query("select id,name,user_type from user where is_delete=0 and is_active=1 and (user_type='Admin Gudang' or user_type='Kurir')");

    if (!empty($query)) {
      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return null;
      }
    }
  }

  public function getDataSla($start_periode, $end_periode, $sla_type)
  {
    if ($sla_type == 'permintaan') {
      $query = $this->db->query("	select
										no_pengajuan as no_permintaan,
										submit_date,
										approve_date,
										(select timediff(approve_date,submit_date)) as service_level,
										time_to_sec(timediff(approve_date,submit_date)) as sla_sec
								from
										pengajuan
								where
										is_delete=0
										and (submit_date between '" . $start_periode . "' and '" . $end_periode . " 23:59:59')
										and not isnull(approve_date)");
      if (!empty($query)) {
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
    }

    if ($sla_type == 'pemesanan') {
      $query = $this->db->query("	select
										no_pengajuan as no_permintaan,
										approve_date,
										closed_date,
										(select timediff(closed_date,approve_date)) as service_level,
										time_to_sec(timediff(closed_date,approve_date)) as sla_sec
								from
										pengajuan
								where
										is_delete=0
										and (approve_date between '" . $start_periode . "' and '" . $end_periode . " 23:59:59')");
      if (!empty($query)) {
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
    }

    if ($sla_type == 'penerimaan') {
      $query = $this->db->query("	select
												no_spb,
												insert_date as `open`,
												receive_date as closed,
												(select timediff(receive_date,insert_date)) as service_level,
												time_to_sec(timediff(receive_date,insert_date)) as sla_sec
										from
												spb
										where
												is_delete=0
												and (insert_date between '" . $start_periode . "' and '" . $end_periode . " 23:59:59')
												and not isnull(receive_date)");
      if (!empty($query)) {
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
    }
  }

}
