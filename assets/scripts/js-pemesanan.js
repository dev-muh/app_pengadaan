var tmpItem = [];
var qtyItem = [];
var opt_item = {
	width:'100%',
	templateResult: formatData,
	// templateSelection: formatData,
	dropdownCssClass: "bigdrop",
	containerCssClass:"form-control custom_select2"
	// closeOnSelect: false
}

$(function () {
  	$('#tahun_keranjang').datetimepicker({
        viewMode: 'years',
        format:'YYYY'
	});
});

var pemesanan_tb = $('#tb_pemesanan').DataTable({
	"pageLength": 100,
	// "ordering": false 
	"order": [[ 11, 'desc' ]]
});

// var tablez = $('#tb_pemesanan').DataTable();
 
function getDataStatus() {
    var stat = [];
    // console.log(pemesanan_tb.row(1).data());

    $.each(pemesanan_tb.rows().data(),function(key,val){
    	// console.log(val.);
    	if(parseInt(val[10])<5){
	    	stat.push({
		    	id:val[11],
		    	status:val[10]
		    });
    	}
    });

    // console.log(stat);
    $.post(URL+'server/get_data_status_pemesanan',{data:stat}).done(function(data){
    	var res = JSON.parse(data);
    	$.each(res,function(key,val){
    		var status = pemesanan_tb.rows('.id-'+val.id_pemesanan).data()[0][10];
    		var id_pemesanan = pemesanan_tb.rows('.id-'+val.id_pemesanan).data()[0][6];

    		if(status!=val.status){
	    		pemesanan_tb.rows('.id-'+val.id_pemesanan).data()[0][10] = val.status;
	    		pemesanan_tb.rows('.id-'+val.id_pemesanan).data()[0][6] = val.txt_status;
	    		var index = pemesanan_tb.rows('.id-'+val.id_pemesanan);
	    		pemesanan_tb.cell(index,5).data(val.kurir);
	    		pemesanan_tb.cell(index,6).data(val.txt_status);
	    		pemesanan_tb.cell(index,10).data(val.status);

	    		var btn_act = $.ajax({
					type: "GET",
					url: URL+'mobile/btn_pemesanan',
					data: {
					id_pem:val.id_pemesanan,
					user_type:sesi,
					status:val.status,
					id_kurir:val.id_kurir,
					no_pem:val.no_pemesanan
					},
					dataType: 'json',
					async: false,
					success: function (result) {
					  return result;
					},
					error: function (result) {
					  // code here
					  return result;
					}
	          	}).responseText; 

	          	pemesanan_tb.cell(index,9).data(btn_act);
	    		
				pemesanan_tb.rows(index).nodes().to$().addClass( 'highlight' );
				$('.highlight').css('background-color','green');
				$( ".highlight" ).animate({
				backgroundColor:'none'
				}, 1000, function() {
					$('.highlight').parent().find('tr').removeAttr('style');
					$('.highlight').parent().find('tr').removeClass('highlight');
				});

				if(val.status==1){
					$.toast({
						heading: 'Pemesanan Disetujui.',
						text: 'Pemesanan dari <b>'+val.pemesan+'</b> dengan nomor <b>'+ val.no_pemesanan+ '</b> telah disetujui.',
						showHideTransition: 'slide',
						position:'bottom-right',
						icon: 'success',
						hideAfter:8000,
						stack:5						
					});
				}

				if(val.status==2){
					$.toast({
						heading: 'Kurir ditambahkan.',
						text: 'Kurir (<b>'+val.kurir+'</b>) telah ditambahkan untuk Pemesanan dari <b>'+val.pemesan+'</b> dengan nomor <b>'+ val.no_pemesanan+ '</b>.',
						showHideTransition: 'slide',
						position:'bottom-right',
						icon: 'success',
						hideAfter:8000,
						stack:5,
						bgColor: '#3c8dbc'
					});
				}

				if(val.status==3){
					$.toast({
						heading: 'Perubahan Status.',
						text: 'Status Pemesanan dari <b>'+val.pemesan+'</b> dengan nomor <b>'+ val.no_pemesanan+ '</b> telah berubah menjadi <b>'+val.txt_status+'</b>',
						showHideTransition: 'slide',
						position:'bottom-right',
						icon: 'info',
						hideAfter:8000,
						stack:5,
						bgColor: '#444'
					});
				}

				if(val.status==4){
					$.toast({
						heading: 'Perubahan Status.',
						text: 'Status Pemesanan dari <b>'+val.pemesan+'</b> dengan nomor <b>'+ val.no_pemesanan+ '</b> telah berubah menjadi <b>'+val.txt_status+'</b>',
						showHideTransition: 'slide',
						position:'bottom-right',
						icon: 'info',
						hideAfter:8000,
						stack:5,
						bgColor: '#444'
					});
				}

				if(val.status==5){
					$.toast({
						heading: 'Pesanan Selesai.',
						text: 'Status Pemesanan dari <b>'+val.pemesan+'</b> dengan nomor <b>'+ val.no_pemesanan+ '</b> telah <b>SELESAI</b>.<br>Status pemesanan telah berubah menjadi <b>'+val.txt_status+'</b>',
						showHideTransition: 'slide',
						position:'bottom-right',
						icon: 'success',
						hideAfter:8000,
						stack:5
					});
				}

				if(val.status==6){
					$.toast({
						heading: 'Pesanan Dibatalkan.',
						text: 'Pemesanan dari <b>'+val.pemesan+'</b> dengan nomor <b>'+ val.no_pemesanan+ '</b> telah <b>Dibatalkan</b>.<br>Status pemesanan telah berubah menjadi <b>'+val.txt_status+'</b>',
						showHideTransition: 'slide',
						position:'bottom-right',
						icon: 'error',
						hideAfter:8000,
						stack:5
						// bgColor:'red'
					});
				}
				
    		}


    	});
    });
    // $.post(URL+'server/get_data_status_pemesanan',{data:stat}).done(function(data){

    // 	if(data==''||data==null){

    // 	}else{
    // 		var res = JSON.parse(data);
    // 		$.each(pemesanan_tb.rows().data(),function(k,v){
    // 			// if(val[10]<5){
	   //  			$.each(res,function(key1,val1){
	   //  				if((v[11]==val1.id_pemesanan)){
	   //  			// 		var btn_act = $.ajax({
				// 				// type: "GET",
				// 				// url: URL+'mobile/btn_pemesanan',
				// 				// data: {
				// 				// id_pem:val1.id_pemesanan,
				// 				// user_type:sesi,
				// 				// status:val1.status,
				// 				// id_kurir:val1.id_kurir
				// 				// },
				// 				// dataType: 'json',
				// 				// async: false,
				// 				// success: function (result) {
				// 				//   return result;
				// 				// },
				// 				// error: function (result) {
				// 				//   // code here
				// 				//   return result;
				// 				// }
    //     //                   	}).responseText;            
		  //   		// 		pemesanan_tb.cell(key,6).data(val1.txt_status);
		  //   		// 		pemesanan_tb.cell(key,9).data(btn_act);
		  //   				pemesanan_tb.cell(k,10).data(val1.status);
		  //   			}
	   //  			});
    // 			// }
    // 		});
    // 	}
    // });
}
// if(typeof(EventSource) !== "undefined") {
// 	var source = new EventSource(URL+"server/s_pem");
// 	source.onmessage = function(event) {
// 	  	// document.getElementById("cur_time").innerHTML = event.data;
// 	  	console.log(event.data);
// 	}
// } else {
//   	document.getElementById("cur_time").innerHTML = "Sorry, your browser does not support server-sent events...";
// }

function trigger_sse(x=null){
	var md=[];
	var md_sort = '';
	var tmp_index = 0;
	$.each(pemesanan_tb.data(),function(key,val){
		md.push(val[0].trim());
	});

	$.each(md.sort(),function(key,val){
		md_sort += val.trim();
	});

	if(x==null){
		return $.md5(md_sort);	
	}else{
		return md;
	}

}

function trigger_status_pemesanan(){
	var md=[];
	var md_sort = '';
	$.each(pemesanan_tb.data(),function(key,val){
		md.push(val[10].trim());
	});

	$.each(md.sort(),function(key,val){
		md_sort += val.trim();
	});

	// console.log(md_sort);
	return $.md5(md_sort);	
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
		    	`<span><img src="`+URL+`assets/img/`+String(data.id).split('|')[5]+`"/>&nbsp;&nbsp;&nbsp;` + data.id.split('|')[2] + `</span>`
		  	);
		  	return $result;
		}

	}
	// alert(data.value);
  	
}

function sh_item(event){
	
	$.alert({
	    // content: function(){
	    //     var self = this;
	    //     self.setContent('Checking callback flow');
	    //     return $.ajax({
	    //         url: 'bower.json',
	    //         dataType: 'json',
	    //         method: 'get'
	    //     }).done(function (response) {
	    //         self.setContentAppend('<div>Done!</div>');
	    //     }).fail(function(){
	    //         self.setContentAppend('<div>Fail!</div>');
	    //     }).always(function(){
	    //         self.setContentAppend('<div>Always!</div>');
	    //     });
	    // },
	    // contentLoaded: function(data, status, xhr){
	    //     self.setContentAppend('<div>Content loaded!</div>');
	    // },
	    // onContentReady: function(){
	    //     this.setContentAppend('<div>Content ready!</div>');
	    // }
	});
}

$(function() {

	trigger_sse();
	$('#sh_item').on('click',function(event){
		event.preventDefault();
		$.dialog({
			title:'Pilih barang',
			content: function(){
		        var self = this;
		        return $.post(URL+'transaksi/sh_item','').done(function (response) {
		            self.setContent(response);

		        }).fail(function(){
		            
		        });
		    },
		    height:1000
		});
	});

	$('#item_select').select2(opt_item);
	$('#tb_pemesanan').DataTable();
	$('#ch_stat').popover();



	$.each($('#item_select option'), function(index, val) {
			var v = $(this).val().split('|');
			tmpItem.push(v[1]);
			qtyItem.push(v[3]);
	});

	$("#bc").focus(function() {
		$("#bc").val('');
		if($('#auto_enter').is(':checked')==true){
			$("#btn_add").prop('disabled',true);
		}else{
			$("#btn_add").prop('disabled',false);
		}
	});

	$("#bc").on("keydown paste cut", function() {
	   setTimeout(function(){
	   		if($('#bc').val().length>0){
				$.each(tmpItem, function(index, val) {
					 if(tmpItem[index]==$("#bc").val()){
					 	// setTimeout(function(){
					 		
					 	// },20);
					 	// $('#item_select').prop('selectedIndex',index).select2({width:'100%'});
				 		Ex2($('#item_select').prop('selectedIndex',index).select2(opt_item));
					 }else{
					 	// alert('');
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
});

function Ex2(x){
	x;
	// $('#item_select').select2({width:'100%'});
	// alert('');
 	if($('#auto_enter').is(':checked')==true){
	 	$('#qty').val(1);
	 	add_item();

 	}else{
 		$('#qty').focus();
 		setTimeout(function(){
 			$('#btn_add').prop('disabled',false);
 			st_btn_add = true;
 		},10);
 		
 	}
 }


function autoenter(){
	setTimeout(function(){
		if($('#auto_enter').is(':checked')==true){
			$('#qty').prop('disabled',true);
			$('#btn_add').prop('disabled',true);
			$('#bc').focus().val('');
		}else{
			$('#qty').prop('disabled',false);
			$('#btn_add').prop('disabled',false);
			$('#bc').focus().val('');
		}
	},10);
	
}

function enter(event) {
	var x = event.which || event.keyCode;
	if (x == 13) {
		//
		add_item();

	}
}

function add_item() {
	
	var valid = validate('req-add-item');

	if (valid && $('#qty').val() >= 1 ) {
		var match = false;
		if($('#item_select').val()!=null){
			
			var item_val = $('#item_select').val().split('|');
			var indexIt = $('#item_select').prop('selectedIndex');

			if(parseInt($('#qty').val())>item_val[3]){
				$.alert(`Stok tidak mencukupi jumlah permintaan anda.\n
						Jumlah stok saat ini adalah `+ item_val[3] +` `+item_val[4]);
			}else{
					$('#bc_par').hide();
					$('#stock').val('');
					var qty = $('#qty').val();
					var note = $('#note').val();

					try {
						$.each($('#tb_item_pemesanan').find('tbody tr'), function(index, val) {
							var barcode = $('#tb_item_pemesanan').find('tbody tr:eq(' + index + ') td:eq(1)').html();
							if (item_val[1] == barcode) {
								match = true;
								var jml = $('#tb_item_pemesanan').find('tbody tr:eq(' + index + ') td:eq(4)').html();
								$('#tb_item_pemesanan').find('tbody tr:eq(' + index + ') td:eq(4)').html(parseInt(jml) + parseInt(qty));
								$('#tb_item_pemesanan').find('tbody tr:eq(' + index + ') td:eq(5)').html($('#note').val());
							}
						});
					} catch (e) {

					} finally {

						var cItem = $('#tb_item_pemesanan').find('tbody tr').length - 1;
						//no = parseInt($('#tb_item_pemesanan').find('tbody tr:eq('+cItem+') td:eq(0)').html());



						if (match == false) {
							$('#tb_item_pemesanan').find('tbody').append(`
										<tr>
										<input type="hidden" class="id_item" value="` + item_val[0] + `">
										<input type="hidden" class="id_it_pemesanan">
										<td class="no_item"></td>
										<td>` + item_val[1] + `</td>
										<td>` + item_val[2] + `</td>
										<td class="r-td">` + item_val[3] + `</td>
										<td class="r-td">` + qty + `</td>
										<td>` + note + `</td>
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


							$.each($('#tb_item_pemesanan').find('tbody tr'), function(index, val) {
								$('#tb_item_pemesanan').find('tbody tr:eq(' + index + ') td:eq(0)').html(index + 1);
							});
							//$('#qty').val('1');

						}
						$('#bc').focus().val('');
						$('#qty').val('');
						$('#note').val('');
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
	var it_val = parseInt(x.parent().parent().find('td:eq(4)').html());
	var it_stock = parseInt(x.parent().parent().find('td:eq(3)').html());
	var b = x.parent().find('button.it_edit');

	$(btn_submit).text('Item sedang diubah...').attr('disabled', true);
	

	var s = b.find('span');
	
	//alert(s.attr('class'));
	if(s.hasClass('glyphicon-edit')){
		x.parent().parent().find('td:eq(4)').html('<input type="number" style="width:100%" class="edit_item_inline" value="'+ it_val +'">');	

		x.parent().parent().find('td:eq(4) input.edit_item_inline').focus();
		x.parent().parent().find('td:eq(4) input.edit_item_inline').select();
		b.removeClass('btn-warning').addClass('btn-success');
		s.removeClass('glyphicon-edit').addClass('glyphicon-ok');
	}else{
		if(s.hasClass('glyphicon-ok')){
			//x.parent().parent().find('td:eq(4)').html('<input style="width:100%" class="edit_item_inline">');	
			var edit_val = parseInt(x.parent().parent().find('td:eq(4) input.edit_item_inline').val());

			if(edit_val<=it_stock){
				x.parent().parent().find('td:eq(4)').html(edit_val);
				b.removeClass('btn-success').addClass('btn-warning');
				s.removeClass('glyphicon-ok').addClass('glyphicon-edit');
			}else{
				$.alert('Jumlah Item melebihi jumlah Stock');
			}
			$(btn_submit).html(btn_submit_text).attr('disabled', false);
		}
	}
}



function validate(x) {
	var valid = $(":input[" + x + "]").length;
	var focus = [];
	try {
		$.each($(":input[" + x + "]"), function(index, val) {
			if ($(":input[" + x + "]:eq(" + index + ")").val() == '') {
				$(this).attr('data-original-title', 'Form masih Kosong');
				$(this).tooltip('show');
				$(this).removeAttr('data-original-title');
				focus.push($(this));
			} else {
				valid--;
			}
		});
	} catch (e) {

	} finally {
		if (valid == 0) {
			return true;
		} else {
			focus[0].focus();
			return false;

		}
	}
}

var btn_submit = '#btn-submit';
var btn_submit_text = $(btn_submit).html();


function submit(x) {
	console.log('a')
	// if ($(btn_submit).prop('disabled', true)) {
	if ($(btn_submit).attr("disabled") == 'disabled') {
	 return;
	}
	console.log('b')
	var tmpIt = [];
	var itemSuccess = [];
	var itemError = [];
	
  $(btn_submit).text('Submitted ...').attr('disabled', true);

	$.each($('#tb_item_pemesanan tbody tr'), function(index, val) {
			tmpIt.push({
				barcode:val.getElementsByTagName('td')[1].innerHTML,
				name:parseInt(val.getElementsByTagName('td')[2].innerHTML),
				stock:parseInt(val.getElementsByTagName('td')[3].innerHTML),
				qty:parseInt(val.getElementsByTagName('td')[4].innerHTML),
				note:parseInt(val.getElementsByTagName('td')[5].innerHTML)
			});

			var stock = parseInt(val.getElementsByTagName('td')[3].innerHTML);
			var qty = parseInt(val.getElementsByTagName('td')[4].innerHTML);


			if(stock<qty){
				itemError.push({
					barcode:val.getElementsByTagName('td')[1].innerHTML,
					name:val.getElementsByTagName('td')[2].innerHTML,
					stock:parseInt(val.getElementsByTagName('td')[3].innerHTML),
					qty:parseInt(val.getElementsByTagName('td')[4].innerHTML),
					note:parseInt(val.getElementsByTagName('td')[5].innerHTML)
				});
				$('#tb_item_pemesanan tbody tr:eq('+index+')').css({
					background:'#fd9c9c'
				});
			}else{
				itemSuccess.push({
					barcode:val.getElementsByTagName('td')[1].innerHTML,
					name:val.getElementsByTagName('td')[2].innerHTML,
					stock:parseInt(val.getElementsByTagName('td')[3].innerHTML),
					qty:parseInt(val.getElementsByTagName('td')[4].innerHTML),
					note:parseInt(val.getElementsByTagName('td')[5].innerHTML)
				});
			}
	});

	if(itemError.length>0){
		var msgErr ='<ul>';
		$.each(itemError,function(index,val){
			msgErr += '<li>Stock <b style="color:blue;">' + val.name + '('+ val.barcode +')</b> saat ini <b style="color:red;">kurang dari jumlah permintaan pemesanan.</b></li>';
		});
		msgErr += '</ul>';
		$.alert(msgErr);
		$(btn_submit).html(btn_submit_text).attr('disabled', false);
	}else{
		let submit_pemesanan = async function(){
			var valid = validate('req-submit');
			var cTableItem = $('#tb_item_pemesanan').find('tbody tr').length;
			if (cTableItem == 0) {
				$.alert('Isikan minimal 1 item');
			}
			if (valid && cTableItem > 0) {
				
				var id = '';
				var id_cust = $('#list_customer').val();
				var nomor = $('#no_pemesanan').html();
				var items = [];
				try {
					$.each($('.id_item'), function(index, val) {
						var id_i = $('.id_item').eq(index).val();
						var id_ip = $('.id_it_pemesanan').eq(index).val();
						var qty_i = $('.id_item').eq(index).parent().find('td:eq(4)').html();
						var h_stock_i = $('.id_item').eq(index).parent().find('td:eq(3)').html();
						var note = $('.id_item').eq(index).parent().find('td:eq(5)').html();

						items.push({
							'id': id_ip,
							'id_item': id_i,
							'qty': qty_i,
							'h_stock': h_stock_i,
							'note': note
						});
					});
				} catch (e) {

				} finally {

					if(id_cust=='' || id_cust==null || id_cust=='undefined'){
						$.alert('Nama karyawan belum dipilih.');
						return false;
					}else{
						var msg = '';
						if (x == 'edit') {
							id = $('.id_pemesanan').val();
							msg = 'mengubah'
						}
						if (x == 'add') {
							msg = 'menambah'
						}

						// startloading('Mohon tunggu, sedang mengirim data...');
						$.post(URL + 'transaksi/trx_pemesanan', {
							mode: x,
							id_pemesan: id_cust,
							id: id,
							nomor: nomor,
							item: items,list_del:list_del
						}).done(function(data){
							endloading();
							var res = JSON.parse(data);

							if (res.status =='1'||res.status ==1) {
								
								$.alert({
									title: 'Success',
									content: 'Sukses ' + msg + ' data',
									buttons: {
										ok: function() {
											window.location.replace(URL + 'transaksi/order_atk/view');
										}
									}
								});
							}else{
								$.alert({
									title: '',
									content: res.message,
									buttons: {
										ok: function() {
											window.location.replace(URL + 'transaksi/order_atk/view');
										}
									}
								});
							}
							$(btn_submit).html(btn_submit_text).attr('disabled', false);
						}).fail(function(data,d,a){
							endloading();
							if (data.status ==404) {
								$.alert({
									title: 'Error',
									content: 'URL Not Found',
									buttons: {
										ok: function() {
											window.location.replace(URL + 'transaksi/order_atk/view');
										}
									}
								});
							}else{
								$.alert({
									title: 'Error',
									content: a,
									buttons: {
										ok: function() {
											window.location.replace(URL + 'transaksi/order_atk/view');
										}
									}
								});
							}
							console.log(data.responseText);
							$(btn_submit).html(btn_submit_text).attr('disabled', false);
						});
					}

				}
			}
		}

		if(tmpIt<1){
			$.alert('Item Pemesanan Kosong');
			$(btn_submit).html(btn_submit_text).attr('disabled', false);
		}else{
			if(tmpIt.length==itemSuccess.length){
				submit_pemesanan();
			}
		}
	}
}

function del_item(x) {
	x.parent().parent().remove();
	$.each($('#tb_item_pemesanan').find('tbody tr'), function(index, val) {
		$('#tb_item_pemesanan').find('tbody tr:eq(' + index + ') td:eq(0)').html(index + 1);
	});
}

var list_del = [];
function del_ip(x, el) {
	$.confirm({
		title: 'Hapus item',
		content: 'Anda yakin ingin menghapus item ini?',
		buttons: {
			confirm: function() {
				list_del.push(x);
				el.parent().parent().remove();
			},
			cancel: function() {

			}
		}
	});
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
				$('.v_rating_gudang').html(res[0].rating_gudang);
				$('.v_status').html(res[0].status_txt);
				$('.v_kurir').html(res[0].kurir);
				$('.v_komentar_gudang').html(res[0].komentar_gudang);


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


// function sh_pemesanan(x){
// 	$.confirm({
// 		title:'Confirm',
// 		content:'jahsd',
// 		buttons:{
// 			ok:{
// 				text:'Oke',
// 				action:function(){
// 					alert('');
// 				}
// 			}
// 		}
// 	});
// }

$(function() {
	$('.btn-pop').popover();
});

function kurir_act(x) {
	var k_id = x.find('input[name=kurir_id]').val();
	var k_id_pem = x.parent().find('.id_pem').val();
	var k_name = x.find('b.kurir_name').html();

	$.confirm({
		title: 'Pilih kurir',
		content: 'Anda akan memilih kurir <b>' + k_name + '</b>',
		buttons: {
			confirm: function() {
				startloading('Mohon tunggu. Sedang memilih kurir...');
				$.post(URL + 'transaksi/add_kurir', {
					id_pem: k_id_pem,
					id_kurir: k_id
				}).done(function(data, textStatus, xhr) {
					endloading();
					var res = JSON.parse(data);

					$.alert({
						title: res.status.toUpperCase(),
						content: res.message,
						buttons: {
							ok: function() {
								window.location = URL + 'transaksi/order_atk/view';
							}
						}
					});

				});
			},
			cancel: function() {

			}
		}
	});
}

function acc_order(x) {
	
	$.confirm({
		title: 'Terima pemesanan?',
		content: 'Anda akan menerima pemesanan ini',
		buttons: {
			confirm: function() {
				startloading('Mohon tunggu...');
				$.post(URL + 'transaksi/acc_order', {
					id: x
				}).done(function(data) {
					endloading();
					var res = JSON.parse(data);



					if(res.status=='success'){
						$.alert({
							title: "Success",
							content: res.message,
							buttons: {
								ok: function() {
									window.location = URL + 'transaksi/order_atk/view';
								}
							}
						});
					}else{
						var err_msg = '<ul>';
						$.each(res.message,function(i,val){
							err_msg += '<li>' + res.message[i].message + '</li>';
						});
						err_msg += '</ul>';

						$.alert({
							title: "Error List(s)",
							content: err_msg,
							buttons: {
								ok: function() {
									//window.location = URL + 'transaksi/order_atk/view';
								}
							}
						});
					}
				});
			},
			cancel: function() {

			}
		}
	});
}

// $(function() {
// 	if(m=='view'){

// 		var z = 500;
// 		var k = 0;
// 		var ins = setInterval(function() {

// 			$.post(URL + 'transaksi/ck_stat', {
// 				dt: arr_cs
// 			}, function(data, textStatus, xhr) {
// 				var dts = $.trim(data);
// 				if (dts.length > 0) {
// 					var res = $.parseJSON(dts);
// 					var ket = ['Waiting Approval', 'Order Received', 'Courier Assigned', 'Prepare Item', 'Courier On The Way', 'Done'];
// 					var ket_kurir = ['Prepare Item', 'Courier On The Way', 'Done','Cancel'];
// 					var btn = ['bg-red', 'bg-darken-2', 'bg-yellow', 'bg-aqua', 'bg-blue', 'btn-success','btn-danger'];

// 					$.get(URL + 'transaksi/pemesanan_btn_act/' + res.id + '/' + res.status, function(btn1) {
// 						$.post(URL + 'transaksi/ck_stat/get', {
// 							id: res.id
// 						}, function(data, textStatus, xhr) {
// 							var table = $('#tb_pemesanan').DataTable();
// 							var arr = $.parseJSON(data);

// 							var id_row = table.data();
// 							try {
// 								$.each(id_row, function(i, val) {
// 									if (table.row(i).data()[1] == arr[1][0].no_pemesanan) {
// 										var c_l = table.data()[0].length - 1;
// 										table.cell(i, c_l).data(btn1).draw(false);
// 										table.cell(i, 6).data(arr[1][0].kurir_name).draw(false);
// 										table.cell(i, 7).data(arr[2][0].status).draw(false);
// 										$('#tb_pemesanan').DataTable().draw(false);

// 										$('[data-toggle="pop-' + res.id + '"]').popover('toggle');

// 										setTimeout(function() {
// 											$('[data-toggle="pop-' + res.id + '"]').popover('toggle');
// 										}, 3000);
// 									}
// 								});
// 							} catch (e) {
// 								console.log(e);
// 							}

// 							arr_cs = arr[0];
// 							var ket = ['Waiting Approval', 'Order Received', 'Courier Assigned', 'Prepare Item', 'Courier On The Way', 'Done','Cancel'];
// 							toast('Nomor Pesanan : ' + arr[1][0].no_pemesanan, 'Status pesanan : ' + ket[arr[1][0].status], 'fa fa-truck');
// 						});
// 					}, 'html');

// 				}
// 			});

// 		}, 5000);
// 	}
// });

function pil_kurir(x) {

	//console.log(table.columns( 1 ).search( 'PSN-1513135850-2017' ).row(1).data()[0]);
	$('[data-toggle="popover"]').popover({
		html: true,
		content: function() {

			$('#popover-content').find('.id_pem').val($(this).val());
			return $('#popover-content').html();
		}
	});
	$(x).popover('toggle');

}

function ch_stat(x) {
	//console.log(table.columns( 1 ).search( 'PSN-1513135850-2017' ).row(1).data()[0]);
	$('[data-toggle="status_pop_content"]').popover({
		html: true,
		content: function() {

			$('#status_pop_content').find('.id_pem').val($(this).val());
			return $('#status_pop_content').html();
		}
	});
	$(x).popover('toggle');
}


function ch_stat_pem(x) {
	$(x).popover({
		html: true,
		content: function() {

			$('#stat_pem_popover').find('.id_pem').val($(x).val());
			return $('#stat_pem_popover').html();
		}
	});

	$(x).popover('toggle');
}


function adm_ch_stat(id,x){
	// alert(id + '>' + x);
	startloading('Mengubah Status Pemesanan...');
	$.ajax({ 
        url: URL + 'transaksi/ch_stat_from_admin', 
        type : "post",      
        dataType : "json",                                               
        data:{id:id,stat:x},
        error: function(result){                    
        	endloading();
        },
        success: function(result) {  
                location.reload()
        }

    }); 
}

function pil_stat(stat,id,c,stTxt){
	var ket_kurir = ['','','','Prepare Item', 'Courier On The Way', 'Done'];
	var btn1 = `	<div class="dropdown">
	                    <button class="btn-pop btn `+ c +` btn-sm btn-flat center-block csstooltip dropdown-toggle" type="button" data-toggle="dropdown">`+stTxt+`
						    `;
	if(stat!=5){
		btn1+=`<span class="caret"></span>`;
	}				
		btn1+=`</button>`;

	if(stat!=5){
		btn1+=`<ul class="dropdown-menu">`;
		$.each(ket_kurir,function(index,val){
			if(index>2){
				btn1+=`<li><a href="#" onclick="adm_ch_stat(`+id+`,`+index+`)">`+val+`</a></li>`;	
			}
		});
		btn1+= `</ul>`;
	}

	btn1+= `</div> `;
	return btn1;
}

function cancel_order(x){
	startloading('Mohon tunggu...');
	$.post(URL + 'mobile/cancel_pem',{id:x}).done(function(data){
		endloading();
		var res = JSON.parse(data);

		if(res.status==1){
			$.alert({
				title: 'Success',
				content: res.message,
				buttons: {
					ok: function() {
						window.location.replace(URL + 'transaksi/order_atk/view');
					}
				}
			});
		}else{
			$.alert({
				title: 'Error',
				content: res.message,
				buttons: {
					ok: function() {
						window.location.replace(URL + 'transaksi/order_atk/view');
					}
				}
			});
		}
	}).fail(function(data){
		endloading();
	});
}

function ch_select(x){
	var v = x.split("|");
	$('#bc').val(v[1]);
	$('#stock').val(v[3]);  
	$('#bc_par').show(); 
	setTimeout(function(){
		$('#qty').focus();
		$('#btn_add').prop('disabled',false);
		st_btn_add = true;
	},100);
}

function ck_stat_pem(id=null){
	startloading('Checking status...');
	$.post(URL+'transaksi/ck_status/'+id).done(function(data){
		endloading();
		var res = JSON.parse(data);
		if(res.status==0){
			window.location.href = res.redirect;
		}else{
			$.alert({
				title:'',
				content:res.message,
				buttons:{
					reload:{
						text:'RELOAD',
						btnClass:'btn-primary',
						action:function(){
							location.reload();
						}
					},
					close:{
						text:'CLOSE'
					}
				}
			});
		}
	}).fail(function(){
		endloading();
		$.alert('Terjadi kesalahan. Err Code : CK_ST_FE(72638472834)');
	});
}

function ck_stat_kurir(id=null){
	startloading('Checking status...');
	$.post(URL+'transaksi/ck_status_kurir/'+id).done(function(data){
		endloading();
		var res = JSON.parse(data);
		$.alert({
			title:'',
			content:res.message,
			buttons:{
				reload:{
					text:'RELOAD',
					btnClass:'btn-primary',
					action:function(){
						location.reload();
					}
				},
				close:{
					text:'CLOSE'
				}
			}
		});
	}).fail(function(){
		endloading();
		$.alert('Terjadi kesalahan. Err Code : CK_ST_FE(72638472834)');
	});
}

function assign_courier(id_p=null,id_k=null){
	startloading('Memilih pemesanan..');
	$.post(URL+'mobile/insertkurir/',{id_pemesanan:id_p,id_kurir:id_k}).done(function(data){
		endloading();
		var res = JSON.parse(data);
		if(res.status==1){
			$.alert({
				title: 'Success',
				content: res.message,
				buttons: {
					ok: function() {
						window.location.replace(URL + 'transaksi/order_atk/view');
					}
				}
			});
		}else{
			$.alert({
				title: 'Error',
				content: res.message,
				buttons: {
					ok: function() {
						window.location.replace(URL + 'transaksi/order_atk/view');
					}
				}
			});
		}
	}).fail(function(e){
		endloading();
		$.alert({
			title: 'Error',
			content: 'Terjadi Kesalahan. Error-Code: AS-C(769928634)',
			buttons: {
				ok: function() {
					window.location.replace(URL + 'transaksi/order_atk/view');
				}
			}
		});
	});
}

function rating(id_pemesanan=null,id_pemesan=null,id_kurir=null,no_pemesanan=null){
	$.confirm({
		title:'<p style="font-size:16px;">Beri rating & ulasan untuk kurir pada pengambilan ' + no_pemesanan + '</p>',
		content:`
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
		buttons:{
			submit:{
				text:'SUBMIT',
				btnClass:'btn-primary',
				action:function(){
					
					var rating = this.$content.find('#rate_input').val();
					var komentar = this.$content.find('#komentar_input').val();
	                
	                if(rating=='' || komentar.length<1){
	                	$.alert('Anda belum memberi rating dan ulasan');
	                	return false;
	                }else{
	                	$('#spin-rate').addClass('active');
						$('#spin-rate').show();
						$('#komentar_input').prop('disabled',true);

		                var data = {
		                	// id_kurir:id_kurir,
		                	id_pemesan:id_pemesan,
		                	id_pemesanan:id_pemesanan,
		                	rate:rating,
		                	comment:komentar
		                };
		                


		                $.post(URL+'mobile/add_rate',data).done(function(){
		                	$.alert({
		                		title:'',
		                		content:'Sukses memberi rating',
		                		buttons:{
		                			close:{
		                				text:'CLOSE',
		                				action:function(){
		                					window.location.replace(URL + 'transaksi/order_atk/view');
		                				}
		                			}
		                		}
		                	});
		                	
		                }).fail(function(){
		                	$('#spin-rate').removeClass('active');
							$('#spin-rate').hide();
							$('#komentar_input').prop('disabled',false);

							$.alert('Terjadi kesalahan. Error_code:RT(2874982340)');
		                });
						return false;
	                }
				}
			},
			close:{
				text:'CLOSE'
			}
		}
	});
}

function rating_pelayanan_admin_gudang(id_pemesanan=null,id_pemesan=null,id_kurir=null,no_pemesanan=null){
	$.confirm({
		title:'<p style="font-size:16px;">Beri rating & ulasan untuk Admin Gudang pada pengambilan ' + no_pemesanan + '</p>',
		content:`
				<label>Kerapihan</label><br>
				<input type="hidden" id="rate_input_kerapihan" name="rate_i">
				<div class="set_rate set" onmouseout="resetRate_id('#rate_input_kerapihan', '.rate_star_kerapihan')">
					<span class="rate_star rate_star_kerapihan small fa fa-star" onmouseover="set_rate_id(0, '.rate_star_kerapihan')" onclick="rate_num_id(1,'#rate_input_kerapihan')"></span>
					<span class="rate_star rate_star_kerapihan small fa fa-star" onmouseover="set_rate_id(1, '.rate_star_kerapihan')" onclick="rate_num_id(2,'#rate_input_kerapihan')"></span>
					<span class="rate_star rate_star_kerapihan small fa fa-star" onmouseover="set_rate_id(2, '.rate_star_kerapihan')" onclick="rate_num_id(3,'#rate_input_kerapihan')"></span>
					<span class="rate_star rate_star_kerapihan small fa fa-star" onmouseover="set_rate_id(3, '.rate_star_kerapihan')" onclick="rate_num_id(4,'#rate_input_kerapihan')"></span>
					<span class="rate_star rate_star_kerapihan small fa fa-star" onmouseover="set_rate_id(4, '.rate_star_kerapihan')" onclick="rate_num_id(5,'#rate_input_kerapihan')"></span>
				</div>
				<br>
				<label>Kesopanan</label><br>
				<input type="hidden" id="rate_input_kesopanan" name="rate_i">
				<div class="set_rate set" onmouseout="resetRate_id('#rate_input_kesopanan', '.rate_star_kesopanan')">
					<span class="rate_star rate_star_kesopanan small fa fa-star" onmouseover="set_rate_id(0, '.rate_star_kesopanan')" onclick="rate_num_id(1, '#rate_input_kesopanan')"></span>
					<span class="rate_star rate_star_kesopanan small fa fa-star" onmouseover="set_rate_id(1, '.rate_star_kesopanan')" onclick="rate_num_id(2, '#rate_input_kesopanan')"></span>
					<span class="rate_star rate_star_kesopanan small fa fa-star" onmouseover="set_rate_id(2, '.rate_star_kesopanan')" onclick="rate_num_id(3, '#rate_input_kesopanan')"></span>
					<span class="rate_star rate_star_kesopanan small fa fa-star" onmouseover="set_rate_id(3, '.rate_star_kesopanan')" onclick="rate_num_id(4, '#rate_input_kesopanan')"></span>
					<span class="rate_star rate_star_kesopanan small fa fa-star" onmouseover="set_rate_id(4, '.rate_star_kesopanan')" onclick="rate_num_id(5, '#rate_input_kesopanan')"></span>
				</div>
				<br>
				<label>Kebersihan</label><br>
				<input type="hidden" id="rate_input_kebersihan" name="rate_i">
				<div class="set_rate set" onmouseout="resetRate_id('#rate_input_kebersihan', '.rate_star_kebersihan')">
					<span class="rate_star rate_star_kebersihan small fa fa-star" onmouseover="set_rate_id(0, '.rate_star_kebersihan')" onclick="rate_num_id(1, '#rate_input_kebersihan')"></span>
					<span class="rate_star rate_star_kebersihan small fa fa-star" onmouseover="set_rate_id(1, '.rate_star_kebersihan')" onclick="rate_num_id(2, '#rate_input_kebersihan')"></span>
					<span class="rate_star rate_star_kebersihan small fa fa-star" onmouseover="set_rate_id(2, '.rate_star_kebersihan')" onclick="rate_num_id(3, '#rate_input_kebersihan')"></span>
					<span class="rate_star rate_star_kebersihan small fa fa-star" onmouseover="set_rate_id(3, '.rate_star_kebersihan')" onclick="rate_num_id(4, '#rate_input_kebersihan')"></span>
					<span class="rate_star rate_star_kebersihan small fa fa-star" onmouseover="set_rate_id(4, '.rate_star_kebersihan')" onclick="rate_num_id(5, '#rate_input_kebersihan')"></span>
				</div>
				<br>

				<div class="form-group>">
					<label>Komentar</label><br>
					<textarea id="komentar_gudang_input" name="komentar_gudang_input" class="form-control" style="width:100%" title="Isi komentar"></textarea>
				</div>
				<br>
				<i id="spin-rate-gudang" class="fa fa-circle-o-notch fa-spin" style="font-size:25px;display:none;"></i>
				<label id="label-loading-rate" style="display:none; color:green;">&nbsp; Sedang mengirim data...</label>
				<style>
					.checked {
					    color: orange;
					}
					.rate_star{
					    font-size:60px;
					    text-shadow: 0px 0px 5px yellow;
					}
					.rate_star.small{
						font-size:30px;
					}
					.rate_star:hover{
					    color: orange;
					    text-shadow: 0px 0px 5px yellow;
					    cursor:pointer;
					}
				</style>
				`,
		buttons:{
			submit:{
				text:'SUBMIT',
				btnClass:'btn-primary',
				action:function(){
					
					var rating_kerapihan = this.$content.find('#rate_input_kerapihan').val();
					var rating_kesopanan = this.$content.find('#rate_input_kesopanan').val();
					var rating_kebersihan = this.$content.find('#rate_input_kebersihan').val();
					var komentar = this.$content.find('#komentar_gudang_input').val();
	                
	                if(rating_kerapihan=='' || rating_kesopanan=='' || rating_kebersihan=='' || komentar.length<1){
	                	$.alert('Anda belum memberi rating dan ulasan');
	                	return false;
	                }else{
	                	$('#spin-rate-gudang').addClass('active');
						$('#spin-rate-gudang').show();
						$('#komentar_gudang_input').prop('disabled',true);

		                var data = {
		                	// id_kurir:id_kurir,
		                	id_pemesan:id_pemesan,
		                	id_pemesanan:id_pemesanan,
		                	rating_kerapihan:rating_kerapihan,
		                	rating_kesopanan:rating_kesopanan,
		                	rating_kebersihan:rating_kebersihan,
		                	comment:komentar
		                };
		                


		                $.post(URL+'mobile/add_rate_gudang',data).done(function(){
		                	$.alert({
		                		title:'',
		                		content:'Sukses memberi rating',
		                		buttons:{
		                			close:{
		                				text:'CLOSE',
		                				action:function(){
		                					window.location.replace(URL + 'transaksi/order_atk/view');
		                				}
		                			}
		                		}
		                	});
		                	
		                }).fail(function(){
		                	$('#spin-rate-gudang').removeClass('active');
							$('#spin-rate-gudang').hide();
							$('#komentar_gudang_input').prop('disabled',false);

							$.alert('Terjadi kesalahan. Error_code:RT(2874982340)');
		                });
						return false;
	                }
				}
			},
			close:{
				text:'CLOSE'
			}
		}
	});
}



function set_rate(x){
	$('.rate_star').css('color','black');
	for(var i=0; i<x+1; i++){
		$('.rate_star:eq('+i+')').css('color','orange');
	}
}

function set_rate_id(x, class_name){
	$(class_name).css('color','black');
	for(var i=0; i<x+1; i++){
		$(class_name+':eq('+i+')').css('color','orange');
	}
}

function resetRate(){
	if($('#rate_input').val()==''){
		$('.rate_star').css('color','black');
	}else{
		$('.rate_star').css('color','black');
		for(var i=0; i<$('#rate_input').val(); i++){
			$('.rate_star:eq('+i+')').css('color','orange');
		}
	}
	
}

function resetRate_id(id_input, class_name){
	if($(id_input).val()==''){
		$(class_name).css('color','black');
	}else{
		$(class_name).css('color','black');
		for(var i=0; i<$(id_input).val(); i++){
			$(class_name+':eq('+i+')').css('color','orange');
		}
	}
	
}

function rate_num(x){
	// alert('');
	// $( "#label-loading-rate" ).css('opacity','0.9');
	if($('#spin-rate').hasClass('active')){
		$.alert('Sedang mengirim data...');
	}else{
		$('#rate_input').val(x);
	}
}

function rate_num_id(x, id_input){
	// alert('');
	// $( "#label-loading-rate" ).css('opacity','0.9');
	if($('#spin-rate-gudang').hasClass('active')){
		$.alert('Sedang mengirim data...');
	}else{
		$(id_input).val(x);
	}
}

function ch_tahun_tb(x){
	location.href = 'view?tahun='+x.parent().find('input').val();
}