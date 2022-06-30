$(function() {
		$('#tahun_keranjang').datetimepicker({
				viewMode: 'years',
				format: 'YYYY'
		});
});

function rating(id_pemesanan = null, id_pemesan = null, id_kurir = null, no_pemesanan = null) {
		$.confirm({
				title: '<p style="font-size:16px;">Beri rating & ulasan untuk kurir pada pengambilan ' + no_pemesanan + '</p>',
				content: `
				<div class="set_rate set" onmouseout="resetRate()">
					<span class="rate_star fa fa-star" onmouseover="set_rate(0)" onclick="rate_num(1)"></span>
					<span class="rate_star fa fa-star" onmouseover="set_rate(1)" onclick="rate_num(2)"></span>
					<span class="rate_star fa fa-star" onmouseover="set_rate(2)" onclick="rate_num(3)"></span>
					<span class="rate_star fa fa-star" onmouseover="set_rate(3)" onclick="rate_num(4)"></span>
					<span class="rate_star fa fa-star" onmouseover="set_rate(4)" onclick="rate_num(5)"></span>
				</div>
				<br>
				<div class="form-group>">
					<label>Komentar</label><br>
					<input type="hidden" id="rate_input" name="rate_i">
					<textarea id="komentar_input" name="komentar" class="form-control" style="width:100%" title="Isi komentar"></textarea>
				</div>
				<br>
				<i id="spin-rate" class="fa fa-circle-o-notch fa-spin" style="font-size:25px;display:none;"></i>
				<label id="label-loading-rate" style="display:none; color:green;">&nbsp; Sedang mengirim data...</label>
				<style>
					.checked {
					    color: orange;
					}
					.rate_star{
					    font-size:60px;
					    text-shadow: 0px 0px 5px yellow;
					}
					.rate_star:hover{
					    color: orange;
					    text-shadow: 0px 0px 5px yellow;
					    cursor:pointer;
					}
				</style>
				`,
				buttons: {
						submit: {
								text: 'SUBMIT',
								btnClass: 'btn-primary',
								action: function() {

										var rating = this.$content.find('#rate_input').val();
										var komentar = this.$content.find('#komentar_input').val();

										if (rating == '' || komentar.length < 1) {
												$.alert('Anda belum memberi rating dan ulasan');
												return false;
										} else {
												$('#spin-rate').addClass('active');
												$('#spin-rate').show();
												$('#komentar_input').prop('disabled', true);

												var data = {
														// id_kurir:id_kurir,
														id_pemesan: id_pemesan,
														id_pemesanan: id_pemesanan,
														rate: rating,
														comment: komentar
												};



												$.post(URL + 'mobile/add_rate', data).done(function() {
														$.alert({
																title: '',
																content: 'Sukses memberi rating',
																buttons: {
																		close: {
																				text: 'CLOSE',
																				action: function() {
																						location.reload();
																				}
																		}
																}
														});

												}).fail(function() {
														$('#spin-rate').removeClass('active');
														$('#spin-rate').hide();
														$('#komentar_input').prop('disabled', false);

														$.alert('Terjadi kesalahan. Error_code:RT(2874982340)');
												});
												return false;
										}
								}
						},
						close: {
								text: 'CLOSE'
						}
				}
		});
}


function set_rate(x) {
		$('.rate_star').css('color', 'black');
		for (var i = 0; i < x + 1; i++) {
				$('.rate_star:eq(' + i + ')').css('color', 'orange');
		}
}

function resetRate() {
		if ($('#rate_input').val() == '') {
				$('.rate_star').css('color', 'black');
		} else {
				$('.rate_star').css('color', 'black');
				for (var i = 0; i < $('#rate_input').val(); i++) {
						$('.rate_star:eq(' + i + ')').css('color', 'orange');
				}
		}

}

function rate_num(x) {
		// alert('');
		// $( "#label-loading-rate" ).css('opacity','0.9');
		if ($('#spin-rate').hasClass('active')) {
				$.alert('Sedang mengirim data...');
		} else {
				$('#rate_input').val(x);
		}
}


function submit(x) {
		var tahun = $('#input_tahun_keranjang').val();
		var group = $('#input_group_keranjang').val();
		var rating = $('#input_rating_keranjang').val();

		startloading('Mohon tunggu...');
		$.post(URL + 'report/getTbKeranjang', { tahun: tahun, group: group, rating: rating }).done(function(data) {
				endloading();
				$('#tb_keranjang').html(data);

		}).fail(function() {
				endloading();
		});
}

function export_pdf_history_keranjang() {
		var tahun = $('#input_tahun_keranjang').val();
		var group = $('#input_group_keranjang').val();
		var group_name = $('#input_group_keranjang option:selected').text();
		var rating = $('#input_rating_keranjang').val();
		var rating_name = $('#input_rating_keranjang option:selected').text();
		
		window.location.href = URL + 'report/export_pdf_history_keranjang?tahun=' + tahun 
		+ '&group=' + group 
		+ '&rating=' + rating 
		+ '&group_name=' + group_name
		+ '&rating_name=' + rating_name
		;
}

function export_excel_history_keranjang() {
		var tahun = $('#input_tahun_keranjang').val();
		var group = $('#input_group_keranjang').val();
		var group_name = $('#input_group_keranjang option:selected').text();
		var rating = $('#input_rating_keranjang').val();
		var rating_name = $('#input_rating_keranjang option:selected').text();

		window.location.href = URL + 'report/export_excel_history_keranjang?tahun=' + tahun 
		+ '&group=' + group 
		+ '&rating=' + rating
		+ '&group_name=' + group_name
		+ '&rating_name=' + rating_name
		;
}

function sh_pemesanan(x, y = null) {
		startloading('Mohon tunggu <br> Sedang mengambil data..');
		item_pj = [];
		try {
				//$('#tb_item_pemesanan').find('thead tr th:eq(4)').remove();
		} catch (e) {} finally {

				$.post(URL + 'transaksi/pemesanan_view', {
						id: x
				}, function(data) {
						endloading();
						if (data.length != 0) {
								var res = $.parseJSON(data);
								// $('.judul_pengajuan').html(res[0].judul);
								$('.no_pemesanan').html(res[0].no_pemesanan);
								$('.tgl_pemesanan').html(format_jam(res[0].tgl_pemesanan));
								$('.nm_pemesan').html(res[0].nama_pemesan);
								$('.v_group').html(res[0].group);
								$('.v_lantai').html(res[0].lantai);
								$('.v_rating').html(res[0].rating);
								$('.v_status').html(res[0].status_txt);
								$('.v_kurir').html(res[0].kurir);
								$('.v_komentar').html(res[0].komentar);


								var td = $('#tb_item_pemesanan').find('tbody');
								td.html('');
								var no = 1;

								if (y == 'verifikasi') {
										$('.verifikasi').load(URL + 'transaksi/verifikasi', function() {
												$(this).show();
										});
								} else {
										$('.verifikasi').html('');
								}

								//$('#tb_item_pemesanan').find('thead tr').append('<th>Item Masuk</th>');
								$('#id_pemesanan_lg').val(res[0].id);

								$.each(res, function(index, el) {
										item_pj.push(res[index].barcode);
										td.append(`
<tr>
<input type="hidden" class="id_it_ps" value="` + res[index].id_it_ps + `">
<input type="hidden" class="id_item" value="` + res[index].id_item + `">
<td>` + no + `</td>
<td>` + res[index].barcode + `</td>
<td>` + res[index].item_name + `</td>
<td class="r-td">` + res[index].h_stock + `</td>
<td class="r-td">` + res[index].qty + `</td>
<td>` + res[index].note + `</td>
</tr>

`);
										if (res[index].qty == res[index].qty_masuk) {
												$('#tb_item_pemesanan').find('tbody tr:eq(' + index + ')').addClass('IComplete bg-green');
										}
										no++;
								});
								$('#modal_view_pemesanan').modal('show');
						} else {
								$.alert('Tidak ada Item / data tidak ditemukan');
						}
				});
		}
}