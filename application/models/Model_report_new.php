<?php
class Model_report_new extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  private $SQL_LOG_ITEM_KELUAR = "
  SELECT
    li.*,
    li.id AS log_id,
    i.item_name,
    i.satuan,
    p.status AS status_pemesanan,
    p.update_date AS `date`,
    p.id_pemesan AS id_pemesan,
    u.name AS nama_pemesan,
    u.department AS department_pemesan,
    u.`group` AS id_group
  FROM
    log_item AS li
    JOIN pos_item AS i ON li.id_item=i.id
    JOIN item_pemesanan AS ip ON ip.id=li.id_trigger
    LEFT JOIN pemesanan p ON p.id = li.`id_parent`
    LEFT JOIN `user` u ON u.`id` = p.`id_pemesan`
  WHERE
    li.parent='KERANJANG'
    AND li.`trigger`='ITEM PEMESANAN'
    AND li.`action`='KURANGI'
    AND ip.is_delete=0
    AND p.`status` = 5
  ";

  private $SQL_LOG_ITEM_MASUK = "
  SELECT
    li.`id_item`,
    SUM(li.qty) AS qty
  FROM
    log_item AS li
    JOIN pos_item AS i ON li.id_item=i.id
    JOIN item_spb AS ip ON ip.id=li.id_trigger
    LEFT JOIN spb p ON p.id = li.`id_parent`
  WHERE
    li.parent='PENERIMAAN'
    AND li.`trigger`='ITEM SPB'
    AND li.`action`='TAMBAH'
    AND ip.is_delete=0
  ";

  public function get_log_barang_keluar($by = null, $bulan = null, $tahun = null, $group = null)
  {
    $sql = $this->SQL_LOG_ITEM_KELUAR;

    if ($_GET['periode'] == 'Harian') {
      $date = str_replace('/', '-', $_GET['tanggal']);
      $periode = ' AND DATE(p.update_date) = "' . date('Y-m-d', strtotime($date)) . '"';
    } else {
      $bulan   = !empty($bulan) || $bulan != 0 ? "and month(p.`update_date`)=" . $bulan : "";
      $tahun   = !empty($tahun) || $tahun != 0 ? "and year(p.`update_date`)=" . $tahun : "";
      $periode = $bulan . ' ' . $tahun;
    }

    $by    = !empty($by) || $by != 0 ? "and p.id_pemesan=" . $by : "";
    // $bulan = !empty($bulan) || $bulan != 0 ? "and month(p.`update_date`)=" . $bulan : "";
    // $tahun = !empty($tahun) || $tahun != 0 ? "and year(p.`update_date`)=" . $tahun : "";
    $group = !empty($group) || $group != 0 ? "and u.group=" . $group : "";

    $sql .= $by . " " . $periode . " " . $group;
    $sql .= " ORDER BY i.item_name, p.update_date";

    // print_r($sql); exit;

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return [];
    }
  }

  public function get_log_barang_masuk($by = null, $bulan = null, $tahun = null, $group = null)
  {
    $sql = $this->SQL_LOG_ITEM_MASUK;

    $by    = !empty($by) || $by != 0 ? "and p.id_pemesan=" . $by : "";
    $bulan = !empty($bulan) || $bulan != 0 ? "and month(p.`update_date`)=" . $bulan : "";
    $tahun = !empty($tahun) || $tahun != 0 ? "and year(p.`update_date`)=" . $tahun : "";
    $group = !empty($group) || $group != 0 ? "and id_group=" . $group : "";

    $sql .= $by . " " . $bulan . " " . $tahun . " " . $group;
    $sql .= " ORDER BY i.item_name, p.update_date";

    // print_r($sql); exit;

    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return [];
    }
  }

  /**
   * Saldo awal didapat dari
   * STOK AKHIR + Keluar - Return - Pembelian
   * @return [type] [description]
   */
  public function get_saldo_awal($by = null, $bulan = null, $tahun = null, $group = null)
  {
    $by         = !empty($by) || $by != 0 ? "and p.id_pemesan=" . $by : "";
    $start_date = date('Y-m-d H:i:s', strtotime($tahun . '-' . $bulan . '-01'));
    $start_date = ' AND p.update_date >= "' . $start_date . '"';
    $group      = !empty($group) || $group != 0 ? "and u.group=" . $group : "";

    // $sql_filter = $by . " " . $start_date . " " . $group;

    $sql = "
    SELECT
      pi.*,
      t1.qty AS qty_keluar,
      t2.qty AS qty_masuk,
      pi.`qty` + IF(ISNULL(t1.qty), 0, t1.qty) - IF(ISNULL(t2.qty), 0 , t2.qty) AS qty_awal,
      h.harga
    FROM
      pos_item `pi`
      LEFT JOIN harga h ON h.id_item = pi.id
      LEFT JOIN (
        SELECT
            li.`id_item`,
            SUM(li.qty) AS qty
          FROM
            log_item AS li
            JOIN pos_item AS i ON li.id_item=i.id
            JOIN item_pemesanan AS ip ON ip.id=li.id_trigger
            LEFT JOIN pemesanan p ON p.id = li.`id_parent`
            LEFT JOIN `user` u ON u.`id` = p.`id_pemesan`
          WHERE
            li.parent='KERANJANG'
            AND li.`trigger`='ITEM PEMESANAN'
            AND li.`action`='KURANGI'
            AND ip.is_delete=0
            -- STATUS PEMESANAN Done
            AND p.`status` = 5
           $by $start_date $group
           GROUP BY li.`id_item`
      ) t1 ON t1.id_item = pi.id
      LEFT JOIN (
        SELECT
            li.`id_item`,
            SUM(li.qty) AS qty
          FROM
            log_item AS li
            JOIN pos_item AS i ON li.id_item=i.id
            JOIN item_spb AS ip ON ip.id=li.id_trigger
            LEFT JOIN spb p ON p.id = li.`id_parent`
          WHERE
            li.parent='PENERIMAAN'
            AND li.`trigger`='ITEM SPB'
            AND li.`action`='TAMBAH'
            AND ip.is_delete=0
            -- STATUS PEMESANAN Done
           $start_date
           GROUP BY li.`id_item`
      ) t2 ON t2.id_item = pi.id
      WHERE ISNULL(pi.delete_by)
      GROUP BY pi.id
      ORDER BY item_name
    ";

    $query = $this->db->query($sql);
    // echo "$sql"; exit;

    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return [];
    }
  }

}
