var tmpItem = [];
var item_pj = [];
var qtyItem = [];
var indexFind = 0;
var st_btn_add = true;

var opt_item = {
	width:'100%',
	templateResult: formatData,
	// templateSelection: formatData,
	dropdownCssClass: "bigdrop",
	containerCssClass:"form-control custom_select2"
	// closeOnSelect: false
}

function formatData (data) {
	var is_disabled = data.disabled;
	var img = String(data.id).split('|')[5];
	if (!data.id) { return data.text; }
	if(is_disabled==false){
		if(img=='undefined'||img==null||img==''){
		  	var $result= $(
		    	`<span><img src="`+URL+`assets/img/no_pict.png"/>&nbsp;&nbsp;&nbsp;` + data.id.split('|')[2] + `</span>`
		  	);
		  	return $result;
		}else{
			var $result= $(
		    	`<span><img src="`+URL+`assets/img/`+String(data.id).split('|')[6]+`"/>&nbsp;&nbsp;&nbsp;` + data.id.split('|')[2] + `</span>`
		  	);
		  	return $result;
		}

	}
	// alert(data.value);
  	
}

$(document).ready(function(){
	
	$('#tb_pengajuan').DataTable({pageLength:100});
	//$('#item_select').prop('selectedIndex',0);
	$.each($('#item_select option'), function(index, val) {
			var v = $(this).val().split('|');
			tmpItem.push(v[1]);
			qtyItem.push(v[3]);
	});

	$('#modal_view_pengajuan').on('hidden.bs.modal', function () {
	    $('.btn-sh_peng').prop('disabled',false);
	    $('.tmpCol').remove();
	});

	$('#modal_view_pengajuan').on('shown.bs.modal', function () {
	    $('#bc_item').focus();
	});




	$("#bc").on("keydown paste cut", function() {
	   setTimeout(function(){
	   		if($('#bc').val().length>0){
				$.each(tmpItem, function(index, val) {
					 if(tmpItem[index]==$("#bc").val()){
					 	setTimeout(function(){
					 		$('#item_select').prop('selectedIndex',index).select2(opt_item);
					 		Ex2();
					 	},20);

						var Ex2 = function(){
						 	if($('#auto_enter').is(':checked')==true){
							 	$('#qty').val(1);
							 	add_item();
						 	}else{
						 		$('#qty').val('');
						 		$('#qty').focus();

						 		setTimeout(function(){
						 			$('#btn_add').prop('disabled',false);
						 			st_btn_add = true;
						 			var v = $('#item_select').val().split('|');
						 			$('#min_qty').val(v[4]); 
									$('#max_qty').val(v[5]); 
									$('#stock').val(v[3]);
						 		},10);
						 		
						 	}
					 	}
					 }else{
					 	
					 	$('#btn_add').prop('disabled','disabled');
					 	st_btn_add = false;
					 	$('#item_select').prop('selectedIndex',0).select2(opt_item);

					 }
				});
	   		}else{
	   			$('#btn_add').prop('disabled',false);
	   			st_btn_add = true;
	   		}
		},10);
	});

	// $("#bc").focus(function() {
	// 	$("#bc").val('');
	// 	$("#btn_add").prop('disabled',false);
	// 	st_btn_add = true;
	// });
	$("#bc").focus(function() {
		$("#bc").val('');
		if($('#auto_enter').is(':checked')==true){
			$("#btn_add").prop('disabled',true);
		}else{
			$("#btn_add").prop('disabled',false);
		}
	});

	$('#item_select').select2(opt_item);
});

function autoenter(x){
	setTimeout(function(){
		if(x.is(':checked')){
			$('#bc').attr('onpaste','on_paste($(this).val())');
			$('#qty').prop('disabled',true);
			$('#btn_add').prop('disabled',true);
			$('#bc').focus().val('');
		}else{
			$('#bc').removeAttr('onpaste');
			$('#qty').prop('disabled',false);
			$('#btn_add').prop('disabled',false);
			st_btn_add = true;
			$('#bc').focus().val('');
		}
	},10);
	
	
}

function on_paste(x){
	   	setTimeout(function(){
			$.each(tmpItem, function(index, val) {
				 if(tmpItem[index]==$("#bc").val()){
				 	$('#qty').focus();
				 	$('#item_select').prop('selectedIndex',index).select2(opt_item);
				 	$('#qty').val('1');
					add_item();
				 }
			});
	});
}
function sh_pengajuan(x,y=null,z=null){
	if(z!=null){
		z.prop('disabled',true);	
	}
	
	item_pj=[];
	try{
		$('#tb_item_pengajuan').find('thead tr th:eq(7)').remove();
	}catch(e){
	}finally{
		startloading('Mengambil data...');
		itVerifikasi = [];
		$.post(URL + 'transaksi/pengajuan_view', {id: x}).done(function(data) {
			endloading();
			if(data.length!=0){
				var res = $.parseJSON(data);
				itVerifikasi = $.parseJSON(data);
				$('.judul_pengajuan').html(res[0].judul);
				$('.no_pengajuan').html(res[0].no_pengajuan);
				$('.tgl_pengajuan').html(format_jam(res[0].tgl_pengajuan));
				$('.diajukan_oleh').html(res[0].submiter_name);
				var stat_permintaan = '';
				if(res[0].status==0){
					stat_permintaan = 'Pending';
				}
				if(res[0].status==1){
					stat_permintaan = 'Accept';
				}
				if(res[0].status==2){
					stat_permintaan = 'Reject';
				}
				$('.status_permintaan').html(stat_permintaan);
				$('.disetujui_oleh').html(res[0].approval_name);
				$('.tgl_persetujuan').html(format_jam(res[0].approve_date));

				$('#tb_item_pengajuan').find('tbody');
				var td = $('#tb_item_pengajuan').find('tbody');
				td.html('');
				var no=1;

				if(y=='verifikasi'){
					$('.verifikasi').load(URL + 'transaksi/verifikasi/' + x,function(){
						$(this).show();

					});
				}else{
					$('.verifikasi').html('');
				}


				if(TYPE_PAGE=='penerimaan'){
					$('#tb_item_pengajuan').find('thead tr').append('<th>Item Masuk</th>');
				}

				if(y=='verifikasi'){

					$('#tb_item_pengajuan').find('thead tr').append('<th class="tmpCol">Action</th>');
				}
				
				$('#id_pengajuan_penerimaan').val(res[0].id);
				//item_pj.push('');
				// td.append('<tr style="display:none;"></tr>')
				$.each(res,function(index, el) {
					item_pj.push(res[index].barcode);
					if(y=='verifikasi'){
						td.append(`
									<tr>
										<input type="hidden" class="id_it_pn" value="`+res[index].id_it_pn+`">
										<input type="hidden" class="id_item" value="`+ res[index].id_item +`">
										<td>`+ no +`</td>
										<td>`+ res[index].barcode +`</td>
										<td>`+ res[index].item_name +`</td>
										<td class="r-td">`+ res[index].min_qty +`</td>
										<td class="r-td">`+ res[index].max_qty +`</td>
										<td class="r-td">`+ res[index].h_stock +`</td>
										<td class="r-td">`+ res[index].qty +`</td>
										<td class="jml_it">`+ res[index].qty_masuk +`</td>
										<td class="tmpCol"><button type="button" onclick="clear_it(`+res[index].id_it_pn+`,$(this))" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Clear Item">
	                                                  <span class="glyphicon glyphicon-refresh"></span>
	                                                </button></td>
									</tr>

							`);
						// <button type="button" onclick="verifikasi_penerimaan()" class="btn btn-default" data-dismiss="modal">Oke</button>
						$('#modal_view_pengajuan').find('.modal-footer').html(`
								<button type="button" onclick="verifikasi_penerimaan()" class="bg-green btn btn-default">OK</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>`);
					}else{
						var vis = 'none';

						if(TYPE_PAGE=='penerimaan'){
							vis = 'block';
						}

						td.append(`
									<tr>
										<input type="hidden" class="id_it_pn" value="`+res[index].id_it_pn+`">
										<input type="hidden" class="id_item" value="`+ res[index].id_item +`">
										<td>`+ no +`</td>
										<td>`+ res[index].barcode +`</td>
										<td>`+ res[index].item_name +`</td>
										<td class="r-td">`+ res[index].min_qty +`</td>
										<td class="r-td">`+ res[index].max_qty +`</td>
										<td class="r-td">`+ res[index].h_stock +`</td>
										<td class="r-td">`+ res[index].qty +`</td>
										<td class="jml_it" style="display:`+ vis +`">`+ res[index].qty_masuk +`</td>
									</tr>

							`);
					}
					if(res[index].qty==res[index].qty_masuk){
						$('#tb_item_pengajuan').find('tbody tr:eq('+ index +')').addClass('IComplete bg-green');

					}
					no++;
				});
				$('#modal_view_pengajuan').modal('show');

			}else{
				$.alert('Tidak ada Item / data tidak ditemukan');
			}
		}).fail(function(){
			endloading();
			$.alert('Error saat mengambil data.');
		});	
	}	
}

function add_item(){
	var validate_item = function(v_stock,v_max,v_jml,v_qty){
			var maxQty = parseInt(v_max);
			var jumlah = parseInt(v_jml);
			var quantity = parseInt(v_qty);
			var stok = parseInt(v_stock);

			var set = jumlah+quantity+stok;
			if(set>maxQty){
				$.confirm({
					theme: 'modern',
					icon: 'fa fa-warning',
					type:'orange',
					title:'',
					content:'Jumlah stok dan permintaan tidak boleh lebih besar dari maximum stok',
					buttons:{
						ok:{
							text:'OK',
							action:function(){
								$('#qty').select();
							}
						}
					}
				});
				return 0;
			}else{
				return jumlah+quantity;
			}
	}

	var validate_min = function(v_stock,v_min,v_jml,v_qty){
			var minQty = parseInt(v_min);//300
			var jumlah = parseInt(v_jml);//2
			var quantity = parseInt(v_qty);//100
			var stok = parseInt(v_stock);//100

			var set = jumlah+quantity+stok;
			var sisa = minQty-set;
			if(set<minQty){
				$.confirm({
					theme: 'modern',
					icon: 'fa fa-warning',
					type:'orange',
					title:'',
					content:'Jumlah stok dan permintaan tidak boleh lebih kecil dari minimum stok. <br>Jumlah yang anda masukkan kurang <b style="color:red;">'+sisa+'</b> lagi untuk memenuhi minimum stok.',
					buttons:{
						ok:{
							text:'OK',
							action:function(){
								$('#qty').select();
							}
						}
					}
				});
				return 0;
			}else{
				return jumlah+quantity;
			}
	}


	$('#qty').focus();
	var valid = validate('req-add-item');

	if(valid && $('#qty').val()>=1){
		var match = false;
		var keepval = false;
		if($('#item_select').val()!=null){
			var item_val = $('#item_select').val().split('|');
			var indexIt = $('#item_select').prop('selectedIndex');

			
			var qty = $('#qty').val();
			var max_qty = parseInt(item_val[5]);
			var min_qty = parseInt(item_val[4]);
			var stock = parseInt(item_val[3]);

			try{
				$.each($('#tb_item_pengajuan').find('tbody tr'), function(index, val) {
					var barcode = $('#tb_item_pengajuan').find('tbody tr:eq('+ index +') td:eq(1)').html();
					if(item_val[1]==barcode){
						match=true;
						var jml = $('#tb_item_pengajuan').find('tbody tr:eq('+ index +') td:eq(6)').html();
						
						if(validate_item(stock,max_qty,jml,qty)>0 && validate_min(stock,min_qty,jml,qty)>0){
							$('#tb_item_pengajuan').find('tbody tr:eq('+ index +') td:eq(6)').html(validate_item(stock,max_qty,jml,qty));
						}else{
							keepval=true;
						}

						
					}
				});
			}catch(e){

			}finally{
				
				var cItem = $('#tb_item_pengajuan').find('tbody tr').length -1;
				//no = parseInt($('#tb_item_pengajuan').find('tbody tr:eq('+cItem+') td:eq(0)').html());

				
				
				if(match==false){

					if(validate_item(item_val[3],item_val[5],0,qty)==0 || validate_min(item_val[3],item_val[4],0,qty)==0){
						return false;
					}

				 	$('#tb_item_pengajuan').find('tbody').append(`
						<tr>
							<input type="hidden" class="id_item" value="`+ item_val[0] +`">
							<input type="hidden" class="id_it_pengajuan">
							<td class="no_item"></td>
							<td>`+ item_val[1] +`</td>
							<td>`+ item_val[2] +`</td>
							<td class="r-td">`+ item_val[4] +`</td>
							<td class="r-td">`+ item_val[5] +`</td>
							<td class="r-td">`+ item_val[3] +`</td>
							<td class="r-td">`+ qty +`</td>
							<td>
								<button onclick="del_item($(this))" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
								<button onclick="edit_item($(this))" type="button" class="btn btn-warning btn-sm it_edit" data-toggle="tooltip" title="Ubah">
									<span class="glyphicon glyphicon-edit"></span>
								</button>
							</td>
						</tr>
					`);

					$.each($('#tb_item_pengajuan').find('tbody tr'), function(index, val) {
						 $('#tb_item_pengajuan').find('tbody tr:eq('+index+') td:eq(0)').html(index+1);
					});
				}

				// 
				if(keepval==false){
					$('#qty').val('');
					$('#bc').focus().val('');
					$('#min_qty').val(''); 
					$('#max_qty').val(''); 
					$('#stock').val(''); 
					$('#item_select').prop('selectedIndex',0).select2(opt_item);
				}
				
			}
			
		}else{
			$.alert('Anda belum memilih Item.');
			return false;

		}
		
	}
}

function edit_item(x){

	// alert('');
	var validate_item = function(v_stock,v_max,v_jml,v_qty,v_act=null){
			var maxQty = parseInt(v_max);
			var jumlah = parseInt(v_jml);
			var quantity = parseInt(v_qty);
			var stok = parseInt(v_stock);

			var set = jumlah+quantity+stok;

				// alert(set);

			if(set>maxQty){
				$.confirm({
					theme: 'modern',
					icon: 'fa fa-warning',
					type:'orange',
					title:'',
					content:'Jumlah stok dan permintaan tidak boleh lebih besar dari maximum stok',
					buttons:{
						ok:{
							text:'OK',
							action:function(){
								// $('#qty').select();
								v_act.parents().eq(1).find('td:eq(6) input.edit_item_inline').select();
							}
						}
					}
				});
				return 0;
			}else{
				return jumlah;
			}
	}

	var validate_min = function(v_stock,v_min,v_jml,v_qty){
			var minQty = parseInt(v_min);//300
			var jumlah = parseInt(v_jml);//2
			var quantity = parseInt(v_qty);//100
			var stok = parseInt(v_stock);//100

			var set = jumlah+quantity+stok;
			var sisa = minQty-set;
			if(set<minQty){
				$.confirm({
					theme: 'modern',
					icon: 'fa fa-warning',
					type:'orange',
					title:'',
					content:'Jumlah stok dan permintaan tidak boleh lebih kecil dari minimum stok. <br>Jumlah yang anda masukkan kurang <b style="color:red;">'+sisa+'</b> lagi untuk memenuhi minimum stok.',
					buttons:{
						ok:{
							text:'OK',
							action:function(){
								$('#qty').select();
							}
						}
					}
				});
				return 0;
			}else{
				return jumlah+quantity;
			}
	}


	var it_val = parseInt(x.parent().parent().find('td:eq(6)').html());
	var it_stock = parseInt(x.parent().parent().find('td:eq(5)').html());
	// alert(it_stock);
	var b = x.parent().find('button.it_edit');
	

	var s = b.find('span');
	
	//alert(s.attr('class'));
	if(s.hasClass('glyphicon-edit')){
		x.parent().parent().find('td:eq(6)').html('<input type="number" style="width:100%" class="edit_item_inline" value="'+ it_val +'">');	

		x.parent().parent().find('td:eq(6) input.edit_item_inline').focus();
		x.parent().parent().find('td:eq(6) input.edit_item_inline').select();
		b.removeClass('btn-warning').addClass('btn-success');
		s.removeClass('glyphicon-edit').addClass('glyphicon-ok');
	}else{

		if(s.hasClass('glyphicon-ok')){
			// alert('');
			x.parents().eq(1).find('td:eq(6) input.edit_item_inline').focus();
			var stockQ = x.parents().eq(1).find('td:eq(5)').html();
			var minQ = x.parents().eq(1).find('td:eq(3)').html();
			var maxQ = x.parents().eq(1).find('td:eq(4)').html();
			var jmlQ = x.parents().eq(1).find('td:eq(6) input.edit_item_inline').val();

			// alert(stockQ);

			if(validate_item(stockQ,maxQ,jmlQ,0,x)>0 && validate_min(stockQ,minQ,jmlQ,0,x)>0){
				var edit_val = parseInt(x.parent().parent().find('td:eq(6) input.edit_item_inline').val());
				x.parent().parent().find('td:eq(6)').html(edit_val);
				b.removeClass('btn-success').addClass('btn-warning');
				s.removeClass('glyphicon-ok').addClass('glyphicon-edit');
			}
			//x.parent().parent().find('td:eq(6)').html('<input style="width:100%" class="edit_item_inline">');	
			
		}
	}
}

function ck_it(){
	
	setTimeout(function(){
		$('#qty_item').attr('readonly','').val('');
		var bc = $('#bc_item').val();

		$.each(item_pj, function(index, val) {
			var jml = $('#tb_item_pengajuan').find('tbody tr:eq('+ index +') td:eq(6)').html();
			 if(bc==item_pj[index]){
			 	$('#s_item').prop('selectedIndex',index+1);
			 	$('#qty_item').removeAttr('readonly').focus();
			 	indexFind=index;
			 }else{

			 }
		});
	},10);
	
}

function change_it(x){
	var dt_it = x.val().split('|');
	$('#bc_item').val(dt_it[1]);
	$('#qty_item').prop('readonly',false);
	$('#qty_item').focus();
	indexFind = x.prop('selectedIndex') -1;

}

function add_count(){
	//alert($('#item_select').val());
	var match = false;
	//var item_val = $('#item_select').val().split('|');
	var qty = $('#qty').val();

	try{
		$('#tb_item_pengajuan').find('tbody tr:eq('+ index +') td:eq(6)').html(parseInt(jml)+parseInt(1));
	}catch(e){
		alert(e);
	}finally{

	}
}

function del_item(x){
	x.parent().parent().remove();
	$.each($('#tb_item_pengajuan').find('tbody tr'), function(index, val) {
		$('#tb_item_pengajuan').find('tbody tr:eq('+index+') td:eq(0)').html(index+1);
	});
}

function submit(x){
	if($('.edit_item_inline').length>0){
		$.alert('Jumlah belum disimpan.');
		return false;
	}

	var tmpIt = [];
	var itemSuccess = [];
	var itemError = [];

	$.each($('#tb_item_pengajuan tbody tr'), function(index, val) {
			tmpIt.push({
				barcode:val.getElementsByTagName('td')[1].innerHTML,
				name:parseInt(val.getElementsByTagName('td')[2].innerHTML),
				stock:parseInt(val.getElementsByTagName('td')[5].innerHTML),
				qty:parseInt(val.getElementsByTagName('td')[6].innerHTML)
			});

			var stock = parseInt(val.getElementsByTagName('td')[5].innerHTML);
			var qty = parseInt(val.getElementsByTagName('td')[6].innerHTML);


			// if(stock<qty){
			// 	itemError.push({
			// 		barcode:val.getElementsByTagName('td')[1].innerHTML,
			// 		name:val.getElementsByTagName('td')[2].innerHTML,
			// 		stock:parseInt(val.getElementsByTagName('td')[3].innerHTML),
			// 		qty:parseInt(val.getElementsByTagName('td')[6].innerHTML),
			// 	});
			// 	$('#tb_item_pengajuan tbody tr:eq('+index+')').css({
			// 		background:'#fd9c9c'
			// 	});
			// }else{
			itemSuccess.push({
				barcode:val.getElementsByTagName('td')[1].innerHTML,
				name:val.getElementsByTagName('td')[2].innerHTML,
				stock:parseInt(val.getElementsByTagName('td')[5].innerHTML),
				qty:parseInt(val.getElementsByTagName('td')[6].innerHTML)
			});
			// }
	});

	let submit_pengajuan = function(){
		var valid = validate('req-submit');
		var cTableItem = $('#tb_item_pengajuan').find('tbody tr').length;
		if(cTableItem==0){
			$.alert('Isikan minimal 1 item');
		}
		if(valid && cTableItem>0){
			var id = '';
			var judul = $('#judul_pengajuan').val();
			var nomor = $('#no_pengajuan').html();
			var items = [];
			try{
				$.each($('.id_item'), function(index, val) {
					var id_i = $('.id_item').eq(index).val();
					var id_ip = $('.id_it_pengajuan').eq(index).val();
					var qty_i = $('.id_item').eq(index).parent().find('td:eq(6)').html();
					var h_stock_i = $('.id_item').eq(index).parent().find('td:eq(5)').html();

					items.push({
						'id':id_ip,
						'id_item':id_i,
						'qty':qty_i,
						'h_stock':h_stock_i
					});
				});
			}catch(e){

			}finally{
				if(x=='edit'){
					id=$('.id_pengajuan').val();
				}
				startloading('Mengirim data...');
				
				$.post(URL + 'transaksi/trx_pengajuan', {mode:x,id:id,judul:judul,nomor:nomor,item:items}).done(function(data, textStatus, xhr) {
					endloading();
					if(textStatus=='success'){
						$.alert({
							title:'Success',
							content:'Sukses menambah data',
							buttons:{
								ok:function(){
									window.location.replace(URL + 'transaksi/trx/view');
								}
							}
						});
						
					}
				}).fail(function(){
					endloading();
					$.alert('Error saat pengambilan data.');
				});
			}
		}				
	}

	if(tmpIt<1){
		$.alert('Item Pemesanan Kosong');
	}else{
		if(tmpIt.length==itemSuccess.length){
			submit_pengajuan();
		}
	}

	//########################   BATAS UPDATE

}

function del_ip(x,el){
	$.confirm({
		title:'Hapus item',
		content:'Anda yakin ingin menghapus item ini?',
		buttons:{
			confirm: function(){
				startloading('Menghapus item..');
				
				$.post(URL + 'transaksi/del_it_pengajuan', {id:x}).done(function(data, textStatus, xhr) {
					endloading();
					el.parent().parent().remove();
				}).fail(function(){
					endloading();
					$.alert('Error saat menghapus item.');
				});
			},
			cancel: function(){

			}
		}
	});
}

function del_pengajuan(x,el){
	$.confirm({
		title:'Hapus item',
		content:'Anda yakin ingin menghapus item ini?',
		buttons:{
			confirm: function(){
				startloading('Menghapus data pengajuan..');
				$.post(URL + 'transaksi/del_pengajuan', {id:x}).done(function(data, textStatus, xhr) {
					endloading();
					el.parent().parent().remove();
				}).fail(function(){
					endloading();
					$.alert('Error saat menghapus item.');
				});
			},
			cancel: function(){

			}
		}
	});
}

function reject_pengajuan(x){
	startloading('Mengirim data...');
	$.post(URL + 'transaksi/reject_pengajuan', {id:x}).done(function(data, textStatus, xhr) {
		endloading();
		if(textStatus=='success'){
			$.alert({
				title:'Success',
				content:'Berhasil menolak pengajuan',
				buttons:{
					ok:function(){
						window.location.replace(URL + 'transaksi/trx/view');
					}
				}
			});
			
		}else{
			$.alert({
				title:'Warning',
				content:'Error mengubah data \n' + textStatus,
				buttons:{
					ok:function(){
						window.location.replace(URL + 'transaksi/trx/view');
					}
				}
			});
		}
	}).fail(function(){
		endloading();
		$.alert('Error saat menolak pengajuan');
	});
}

function accept_pengajuan(x){
	startloading('Mengirim data...');
	$.post(URL + 'transaksi/accept_pengajuan', {id:x}).done(function(data, textStatus, xhr) {
		endloading();
		if(textStatus=='success'){
			$.alert({
				title:'Success',
				content:'Berhasil menerima pengajuan.',
				buttons:{
					ok:function(){
						window.location.replace(URL + 'transaksi/trx/view');
					}
				}
			});
			
		}else{
			$.alert({
				title:'Warning',
				content:'Error mengubah data \n' + textStatus,
				buttons:{
					ok:function(){
						window.location.replace(URL + 'transaksi/trx/view');
					}
				}
			});
		}
	}).fail(function(){
		endloading();
		$.alert('Error saat menerima pengajuan.');
	});
}

function validate(x){
	var valid = $(":input["+x+"]").length;
	var focus = [];
	try{
		$.each($(":input["+x+"]"), function(index, val) {
			if($(":input["+x+"]:eq("+index+")").val()==''){
				 $(this).attr('data-original-title','Form masih Kosong');
				 $(this).tooltip('show');
				 $(this).removeAttr('data-original-title');
				 focus.push($(this));
			}else{
				valid--;
			}
		});
	}catch(e){

	}finally{
		if(valid==0){
			return true;
		}else{
			focus[0].focus();
			return false;

		}
	}
}

function cItem(x){
	// setTimeout(function(){
	// 	$.each(tmpItem, function(index, val) {
	// 		 if(tmpItem[index]==x.val()){
	// 		 	$('#qty').focus();
	// 		 	$('#item_select').prop('selectedIndex',index).select2(opt_item);
			 	
	// 		 }
	// 	});
	// },10);
}

function enter(event){
	var cek = 0;
	var x = event.which || event.keyCode;
	if(x==13){
		//$('#qty').val('1');
		//add_item();
		if(st_btn_add){
			add_item();
		}else{
			$.alert('Tidak ditemukan Item dengan Barcode Tersebut.');
		}
	}	
}

function enter_it(event=null){
	if(event!=null){
		var x = event.which || event.keyCode;
	}
	var jml = $('#tb_item_pengajuan').find('tbody tr:eq('+ indexFind +') td:eq(5)').html();
	var jmlAsli = $('#tb_item_pengajuan').find('tbody tr:eq('+ indexFind +') td:eq(6)').html();
	var qty = $('#qty_item').val();
	if((x==13||x==null) && (qty>0)){
		if(parseInt(jml)<parseInt(jmlAsli)){
			try{
				if((parseInt(qty)+parseInt(jml))<=parseInt(jmlAsli)){
					$('#tb_item_pengajuan').find('tbody tr:eq('+ indexFind +') td:eq(5)').html(parseInt(jml)+parseInt(qty));
					$('#qty_item').val('').focus();
					//$('#bc_item').val('').focus();
					//$('#qty_item').val('').attr('readonly','');

					var tmpJml = $('#tb_item_pengajuan').find('tbody tr:eq('+ indexFind +') td:eq(5)').html();
					var getID = $('#tb_item_pengajuan').find('tbody tr:eq('+ indexFind +') input[type=hidden].id_it_pn').val();
					var getIDItem = $('#tb_item_pengajuan').find('tbody tr:eq('+ indexFind +') input[type=hidden].id_item').val();
					postStatusPenerimaan(indexFind,getID,tmpJml,getIDItem,qty);


					// if(parseInt(jml)==parseInt(jmlAsli)){
					// 	// $('#tb_item_pengajuan').find('tbody tr:eq('+ indexFind +')').css('background-color','green');
					// 	alert('');
					// }
				}else{
					$.alert({
						title:'Warning!',
						content:'Jumlah Qty melebihi jumlah pengajuan'
					});
				}
			}catch(e){

			}finally{
				var j1 = jmlAsli;
				var j2 = $('#tb_item_pengajuan').find('tbody tr:eq('+ indexFind +') td:eq(5)').html();
				ck_completeItem=[];

				if(parseInt(j1)==parseInt(j2)){

					$('#tb_item_pengajuan').find('tbody tr:eq('+ indexFind +')').addClass('IComplete bg-green');
					
					// cIt_pn = $('#tb_item_pengajuan').find('tbody tr').length;
					// cIt_pn_ok = $('#tb_item_pengajuan').find('tbody tr.IComplete').length;

					// if(cIt_pn == cIt_pn_ok){
					// 	verifikasiPenerimaan($('#id_pengajuan_penerimaan').val());
					// }

					// $.each(cIt_pn, function(index, val) {
						 
					// });
				}
			}
		}else{
			$.alert({
				title:'Item Cukup',
				content:'Item sudah lengkap'
			});
		}
	}
}

var itVerifikasi = [];
function postStatusPenerimaan(id_rw,id,jml,id_item,qty){
	//alert(id_rw);
	itVerifikasi[id_rw].qty_masuk = parseInt(itVerifikasi[id_rw].qty_masuk) + parseInt(qty);
	//itVerifikasi.push({id:id,jml:jml,id_item:id_item,qty:qty});
	// startloading('Mengirim data...');
	// $.post(URL + 'transaksi/update_it_pn', {id:id,jml:jml,id_item:id_item,qty:qty}).done(function(data, textStatus, xhr) {
	// 	endloading();
	// }).fail(function(){
	// 	endloading();
	// 	$.alert('Error saat memperbarui qty item.');
	// });
}

function clear_it(id,x){
	var id_arr = x.parent().parent().index();
	itVerifikasi[id_arr].qty_masuk = 0;
	x.parent().parent().find('td.jml_it').html(0);
	$('#tb_item_pengajuan').find('tbody tr:eq('+ id_arr +')').removeClass('IComplete bg-green');
}

function verifikasi_penerimaan(){
	startloading('Memverifikasi penerimaan...');
	$.post(URL + 'transaksi/verifikasi_penerimaan', {items:itVerifikasi}).done(function(data, textStatus, xhr) {
		endloading();
		var res = JSON.parse(data);
		$.confirm({
			title:'',
			content:res.message,
			buttons:{
				ok:function(){
					window.location.replace(URL + 'transaksi/trx/view_penerimaan');
				}
			}

		});
				
		
	}).fail(function(){
		endloading();
		$.alert('Error saat mengirim data verifikasi.');
	});
}

function verifikasiPenerimaan(id){
	startloading('Memverifikasi penerimaan...');
	$.post(URL + 'transaksi/update_stat_penerimaan', {id:id}).done(function(data, textStatus, xhr) {
		endloading();
		$.confirm({
			title:'Pesan Sukses',
			content:'Status Telah Terverifikasi',
			buttons:{
				ok:function(){
					window.location.replace(URL + 'transaksi/trx/view_penerimaan');
				}
			}

		});
				
		
	}).fail(function(){
		endloading();
		$.alert('Error saat mengirim data verifikasi.');
	});
}

function ch_select(x){
	$('#qty').val('');
	var v = x.split("|");
	$('#bc').val(v[1]); 
	$('#min_qty').val(v[4]); 
	$('#max_qty').val(v[5]); 
	$('#stock').val(v[3]); 
	setTimeout(function(){
		$('#qty').focus();
		$('#btn_add').prop('disabled',false);
		st_btn_add = true;
	},100);
}


