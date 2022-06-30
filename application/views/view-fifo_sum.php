<?php 
    $bulan_cur = !empty($_GET['bulan']) ? $_GET['bulan']:''; 
    function nf($x){
        return "Rp. ".number_format($x,0,",",".").',-';
    } 

    $t_jumlah_saldo = 0;
    $t_biaya_saldo = 0;
    $t_jumlah_pengambilan = 0;
    $t_biaya_pengambilan = 0;
?>
<script type="text/javascript">
    var bulan = "<?php echo !empty($_GET['bulan']) ? $_GET['bulan']:''; ?>";
    var tahun = "<?php echo !empty($_GET['tahun']) ? $_GET['tahun']:''; ?>";
</script>



<table id="table_fifo" width="100%" class="table table-bordered table-hover dt-responsive cell-border" border="1">
    <thead>
        <tr style="background-color: #169eda; color:white; ">
            <td width="30%">Nama</td>
            <td width="15%">Jumlah Saldo</td>
            <td width="20%">Biaya Saldo</td>
            <td width="15%">Jumlah Pengambilan</td>
            <td width="20%">Biaya Pengambilan</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($log as $key => $value): ?>
            <tr>
                <td><?php echo $value['nama_item']; ?></td>
                <td class="r"><?php echo $value['jumlah_saldo']; ?></td>
                <td class="r"><?php echo nf($value['biaya_saldo']); ?></td>
                <td class="r"><?php echo $value['jumlah_pengambilan']; ?></td>
                <td class="r"><?php echo nf($value['biaya_pengambilan']); ?></td>
            </tr>

            <?php 
                $t_jumlah_saldo += $value['jumlah_saldo'];
                $t_biaya_saldo += $value['biaya_saldo'];
                $t_jumlah_pengambilan += $value['jumlah_pengambilan'];
                $t_biaya_pengambilan += $value['biaya_pengambilan'];
            ?>

        <?php endforeach ?>
    </tbody>
</table>

<?php echo $t_jumlah_saldo; ?>
<style type="text/css">
    .content-wrapper{
        display: flow-root;
    }
    .r{
        text-align: right;
    }
</style>
<script type="text/javascript">
    table = $('#table_fifo').DataTable();

    var t_jumlah_saldo = <?php echo $t_jumlah_saldo; ?>;
    var t_biaya_saldo = <?php echo $t_biaya_saldo; ?>;
    var t_jumlah_pengambilan = <?php echo $t_jumlah_pengambilan; ?>;
    var t_biaya_pengambilan = <?php echo $t_biaya_pengambilan; ?>;

    // $('#total').html('Rp '+num(total));
    $('#jumlah_saldo').html(num(t_jumlah_saldo).replace(',-',''));
    $('#biaya_saldo').html('Rp '+num(t_biaya_saldo));
    $('#jumlah_pengambilan').html(num(t_jumlah_pengambilan).replace(',-',''));
    $('#biaya_pengambilan').html('Rp '+num(t_biaya_pengambilan));

</script>