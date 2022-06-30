<?php 
    function nf($x){
        return "Rp. ".number_format($x,0,",",".").',-';
    } 

    function bl($bulan=null){
        $b = [
                    'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                ];

        if($bulan<=0){
            return $b[(12+($bulan))-1];
        }else{
            return $b[$bulan-1];
        }
    }
?>

<div class="col-md-12">
    <table id="tb_in" class="table table-bordered table-hover dt-responsive cell-border" border=1 width="100%" style="background-color: white;">
        <thead>
            <tr style="background-color: #169eda; color:white; " class="tb-c">
                <th width="250px">Keterangan</th>
                <th width="100px">Bulan</th>
                <th width="50px">Jumlah Saldo</th>
                <th width="120px">Harga Satuan Saldo</th>
                <th width="150px">Biaya Saldo</th>
                <th>Jumlah Pengambilan</th>
                <th width="100px">Harga Satuan Pengambilan</th>
                <th width="150px">Biaya Pengambilan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($log)){ ?>
          
                <?php foreach ($log['in']['saldo_akhir'] as $key => $val) { ?>
                    <tr>
                        <td>Saldo Akhir <?php echo $val['bulan']; ?></td>
                        <td></td>
                        <td class="r"><?php echo $val['qty']; ?></td>
                        <td class="r"><?php echo nf($val['harga']); ?></td>
                        <td class="r"><?php echo nf($val['qty']*$val['harga']); ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?> 

                <?php if(!empty($log['in']['spb'])){ ?>
                    <?php foreach ($log['in']['spb'] as $key => $val) { ?>
                        <tr >
                            <td><?php echo $val['no_spb']; ?></td>
                            <td class="c"><?php echo bl($val['bulan']); ?></td>
                            <td class="r"><?php echo $val['qty']; ?></td>
                            <td class="r"><?php echo nf($val['harga']); ?></td>
                            <td class="r"><?php echo nf($val['qty']*$val['harga']); ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php } ?>   
                <?php } ?>
                    <tr style="background-color: yellow; font-weight: bold;">
                        <td  >Sub Total Saldo</td>
                        <td class="c"><?php echo bl($_GET['bulan']); ?></td>
                        
                        <td class="r"><?php echo !empty($log['in']['sub_total']['qty']) ? $log['in']['sub_total']['qty']:''; ?></td>
                        <td></td>
                        <td class="r"><?php echo !empty($log['in']['sub_total']['harga']) ? nf($log['in']['sub_total']['harga']):''; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
            <?php } ?>


            <?php if(!empty($log)){ ?>

                <?php $sub_total_pengambilan = array('qty'=>0,'total'=>0); ?>
                <?php if(!empty($log['out']['pengambilan'])){ ?>
                    <?php foreach ($log['out']['pengambilan'] as $key => $val) { ?>
                        <?php 
                                $sub_total_pengambilan['qty']+=$val['qty']; 
                                $sub_total_pengambilan['total']+=($val['harga']*$val['qty']); 
                        ?>
                        <tr>
                            <td>Pengambilan</td>
                            <td class="c"><?php echo $val['bulan']; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="r"><?php echo $val['qty']; ?></td>
                            <td class="r"><?php echo nf($val['harga']); ?></td>
                            <td class="r"><?php echo nf($val['harga']*$val['qty']); ?></td>
                        </tr>
                    <?php } ?> 
                <?php } ?>
                    <tr style="background-color: yellow; font-weight: bold;">
                        <td >Sub Total Pengambilan</td>
                        <td class="c"><?php echo bl($_GET['bulan']); ?></td>
                        
                        <td></td>
                        <td></td>
                        <td class="r"></td>
                        <td class="r"><?php echo $sub_total_pengambilan['qty']; ?></td>
                        <td></td>
                        <td class="r"><?php echo nf($sub_total_pengambilan['total']); ?></td>
                    </tr>
                    <?php $sub_total_akhir = array('qty'=>0,'total'=>0); ?>
                <?php if(!empty($log['out']['saldo_akhir'])){ ?>
                    <?php foreach ($log['out']['saldo_akhir'] as $key => $val) { ?>
                        <?php 
                            $sub_total_akhir['qty']+=$val['qty']; 
                            $sub_total_akhir['total']+=($val['harga']*$val['qty']); 
                        ?>
                        <tr>
                            <td>Saldo Akhir <?php echo $val['bulan']; ?></td>
                            <td class="c"><?php echo bl($_GET['bulan']); ?></td>
                            <td class="r"><?php echo $val['qty']; ?></td>
                            <td class="r"><?php echo nf($val['harga']); ?></td>
                            <td class="r"><?php echo nf($val['harga']*$val['qty']); ?></td>
                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>
                    <?php } ?>   
                <?php } ?>

                <tr style="background-color: yellow;  font-weight: bold;">
                    <td >Sub Total Saldo Akhir</td>
                    <td class="c"><?php echo bl($_GET['bulan']); ?></td>
                    
                    
                    <td class="r"><?php echo $sub_total_akhir['qty']; ?></td>
                    <td class="r"></td>
                    <td class="r"><?php echo nf($sub_total_akhir['total']); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>