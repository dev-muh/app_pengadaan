
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<label class="col-sm-3 control-label" style="display:inline-block;width: 150px;">Nama Item</label>
			<div class="col-sm-9">
				: <b><?php echo empty($it_info) ? '':$it_info[0]->item_name; ?></b>
			</div>
		</div>

		<div class="row">
			<label  class="col-sm-3 control-label" style="display:inline-block;width: 150px;">Barcode</label>
			
			<div class="col-sm-9">
				: <b><?php echo empty($it_info) ? '':$it_info[0]->barcode; ?></b>
			</div>
		</div>

		<div class="row">
			<label class="col-sm-3 control-label" style="display:inline-block;width: 150px;">Tahun</label>
			<div class="col-md-3">
				<select class="form-control">
					<?php if(!empty($tahun)){ ?>
						<option disabled="disabled" selected="selected">Pilih Tahun</option>
						<?php foreach ($tahun as $key => $val) { ?>
							<option 
								<?php if($tahun_cur==$val->tahun){
									echo 'selected="selected"';
								} ?>
								onclick="all()" value="<?php echo $val->tahun; ?>"><?php echo $val->tahun; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
</div>

<br><br>
<div class="row">
	<div class="col-md-12">
		<table border="2px" id="tb-history_item" class="table table-bordered table-striped table-hover dt-responsive cell-border" cellspacing="0" width="100%" style="border: solid 1px #169eda;">
			<thead>
				<tr style="background-color:#169eda; color:white;">
					<th>No. SPB</th>
					<th>Bulan</th>
					<th>No. Pengambilan</th>
					<th>Tgl. Pengambilan</th>
					<th>Nama Karyawan</th>
					<th>Group</th>
					<th>Kurir</th>
					<th>Status</th>
					<th>Jumlah Barang Masuk</th>
					<th>Jumlah Barang Keluar</th>
				</tr>
				<tr style="background-color: #d6d6d6;">
					<td>Stock Awal</td>
					<?php $date=date_create($it_info[0]->insert_date); ?>
					<td><?php echo !empty($it_info) ? date_format($date,'F'):''; ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td  style="font-weight: bolder;">
						<?php 
							// $st_aw = $it_info[0]->stock_awal;
							$st_now = $it_info[0]->qty;

							$stock_aw = $st_now+$item_keluar-$item_masuk;

							echo !empty($it_info) ? $stock_aw:'';

						?>
					
					</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
		<!-- 		<tr>
					<td>Stock Awal</td>
					<?php $date=date_create($it_info[0]->insert_date); ?>
					<td><?php echo !empty($it_info) ? date_format($date,'F'):''; ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo !empty($it_info) ? $it_info[0]->stock_awal:''; ?></td>
					<td></td>
				</tr> -->
				<?php if(!empty($log_item)){ ?>
					<?php foreach ($log_item as $key => $val) { ?>
						<tr>
							<td><?php echo $val->no_spb; ?></td>
							<td><?php echo $val->bulan; ?></td>
							<td><?php echo $val->no_pengambilan; ?></td>
							<td><?php echo $val->tgl_pengambilan; ?></td>
							<td><?php echo $val->nama_karyawan; ?></td>
							<td><?php echo $val->group; ?></td>
							<td><?php echo $val->nama_kurir; ?></td>
							<td><?php echo $val->status_text; ?></td>
							<td><?php echo $val->item_masuk; ?></td>
							<td><?php echo $val->item_keluar; ?></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6"></td>
					
					<td colspan="2" style="background-color: white;">TOTAL</td>
					
					<td style="font-weight:bold; background-color: white;"><?php echo $item_masuk+$stock_aw; ?></td>
					<td style="font-weight:bold; background-color: white;"><?php echo $item_keluar; ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2" style="background-color: white;">SISA STOCK</td>
					
					<td colspan="2" align="center" style="font-weight:bold; background-color: white;"><?php echo !empty($it_info) ? $it_info[0]->qty:''; ?></td>
					
				</tr>
			</tfoot>
		</table>
	</div>
</div>