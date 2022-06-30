$(function(){
	$('[data-toggle="collapse"]').tooltip();
	$('[data-toggle="collapse"]').collapse('show');

	reload_table();



});

function loading(el,inp,x,opt=null){
	if(x=='show'){
		$(el).css('display','block');
		if(opt=='display'||opt==null){
			$(inp).css('display','none');
		}
		if(opt=='click'){
			$(inp).attr('disabled','disabled');
		}
		
	}
	if(x=='hide'){
		$(el).css('display','none');
		if(opt=='display'||opt==null){
			$(inp).css('display','block');
		}
		if(opt=='click'){
			$(inp).attr('disabled',false);
		}
	}
}

function sh_pemesanan_brg(x,judul){
	$.confirm({
		title:'Judul Permintaan : '+judul,
		content:function(){
			var self = this;
			return $.post(URL + 'transaksi/sh_pemesanan_brg/'+ x).done(function(data){
				self.setContent(data);
			}).fail(function(e){
				self.setContent('Terjadi Kesalahan');
			});
		},
		columnClass:'large',
		buttons:{
			close:{
				text:'CLOSE'
			}
		}
		// containerFluid:true
	});
}

function sh_ls_spb(x=null){
	$.confirm({
		title:'List SPB',
		content:function(){
			var self = this;
			return $.post(URL + 'pemesanan/sh_SPB/',{id_permintaan:x}).done(function(data){
				self.setContent(data);
			}).fail(function(e){
				self.setContent('Terjadi Kesalahan');
			});
		},
		columnClass:'large',
		buttons:{
			close:{
				text:'CLOSE'
			}
		}
		// containerFluid:true
	});
}

function get_sup_harga(x){
	var sh_sel = function(x){
		$('#select2-sel_sup-container').parent(3).css('display',x);	
	}
	var sh_load = function(x){
		$('#loading_sup').css('display',x);	
	}

	sh_sel('none');
	sh_load('block');

	var item = x.val().split('|');

	$('#spb_it_harga').val('');

	$('#spb_it_barcode').val(item[3]);
	$('#spb_it_barcode').attr('title',item[3]);
	$('#spb_it_barcode').attr('data-original-title',item[3]);
	$('#spb_it_barcode').tooltip();

	
	$('#sel_sup').html('').append($(`<option selected="selected" disabled="disabled" value=""></option>`).text('-- Select Supplier --'));

	$.post(URL+'master/get_harga_supplier/'+item[1]).done(function(data){
		sh_load('none');
		sh_sel('block');

		var res = JSON.parse(data);
		console.log(res);
		

		$.each(res, function(key, value) {   
		    $('#sel_sup').append($("<option></option>").attr('value',value.id+'|'+value.id_supplier+'|'+value.id_item+'|'+num(value.harga)+'|'+value.supplier_name).text(value.supplier_name)); 
		});
	}).fail(function(e){
		sh_load('none');
		sh_sel('block');
		$('#loading_sup').hide();
	});
}

function get_harga(x){
	var sup = x.val().split('|');
	$('#spb_it_harga').val(sup[3]);
}

function add_sup(){

	$('.inp').tooltip("hide");
	var inp = $('.inp').toArray();
	var validate = false;
	var match = false;

	$.each(inp,function(key,val){
		if(!val.value){

			validate = false;
			$(val).attr('data-original-title','Form Kosong');
			$(val).attr('title','Form Kosong');

			$(val).tooltip("show");

			$(val).attr('data-original-title','');
			$(val).attr('title','');
			return false;
		}else{
			validate = true;
		}
	});

	if(validate==true){
		if(parseInt(inp[2].value)>parseInt(inp[0].value.split('|')[4])){
			$.alert('Jumlah input melebihi Jumlah pemesanan');
		}else{
			$.each(tmpItem,function(key,val){
				var p1 = parseInt(val.qty_spb);
				var p2 = parseInt(inp[2].value);
				var qty = parseInt(inp[0].value.split('|')[4]);
				if(inp[0].value.split('|')[0]==val.id_it_pn){
					if((p1+p2)>qty){
						$.alert('Jumlah input melebihi jumlah keseluruhan SPB pada item ini.');
					}else{
						tmpItem[key].qty_spb = parseInt(val.qty_spb)+parseInt(inp[2].value);
					
						var table = $('#tb_spb_sup').DataTable().column([0,1]).visible(false);
						if(table.data().length>0){
							var rows = table.rows().data();
							$.each(rows,function(key1,val1){
								if((val1[0]==inp[0].value.split('|')[1]) && (val1[1]==inp[3].value.split('|')[1])){
									match = true;
									table.cell(key1,5).data(parseInt(val1[5])+parseInt(inp[2].value));
									table.cell(key1,6).data(parseInt(qty)-parseInt(tmpItem[key].qty_spb));
									table.cell(key1,9).data(num(parseInt(val1[5])*parseInt(cnum(val1[8]))));
									table.draw(false);
								}
							});

							if(match==false){
								// alert(parseInt(tmpItem[key].qty_spb));
								var addRow = table.row.add([
										inp[0].value.split('|')[1], //ID ITEM
										inp[3].value.split('|')[1], //ID SUPPLIER
										table.data().length+1,
										inp[0].value.split('|')[3],
										inp[0].value.split('|')[2],
										inp[2].value,
										parseInt(qty)-parseInt(tmpItem[key].qty_spb),
										inp[3].value.split('|')[4],
										inp[3].value.split('|')[3],
										num(inp[2].value*cnum(inp[3].value.split('|')[3])),
										`<button onclick="del_it_create_spb(this)" type="button" class="btn bg-red btn-sm" data-toggle="tooltip" title="Hapus">
									        <span class="glyphicon glyphicon-trash"></span>
									     </button>`
								]).draw();

								var nodes = table.column(0).nodes();
								$(nodes[table.data().length-1]).addClass('tb-hide');
							}
						}else{
							table.row.add([
								inp[0].value.split('|')[1],
								inp[3].value.split('|')[1],
								table.data().length+1,
								inp[0].value.split('|')[3],
								inp[0].value.split('|')[2],
								inp[2].value,
								parseInt(qty)-parseInt(tmpItem[key].qty_spb),
								inp[3].value.split('|')[4],
								inp[3].value.split('|')[3],
								num(inp[2].value*cnum(inp[3].value.split('|')[3])),
								`<button onclick="del_it_create_spb(this)" type="button" class="btn bg-red btn-sm" data-toggle="tooltip" title="Hapus">
							        <span class="glyphicon glyphicon-trash"></span>
							     </button>`
							]).draw();

							var nodes = table.column(0).nodes();
							$(nodes[table.data().length-1]).addClass('tb-hide');
						}
						return false;
					}
				}
			});
		}	
	}
}

function reset_spb(){

}
// alert($.datepicker);
function before_spb(){
	var table = $('#tb_spb_sup').DataTable().column([0,1]).visible(false);
	if(table.data().length<1){
		$.alert('Item Kosong.');
		// loading('#loading_create','#create_spb_submit','hide','click');
	}else{

		var cur_date = new Date();
		var date = $.datepicker.formatDate('yy-mm-dd', new Date());
		var dt = new Date();

		dt.setDate(cur_date.getDate()+3);
		
		$.confirm({
			title:'Periode Tanggal SPB',
			content:`	<div  class="input-group input-daterange date" data-provide="datepicker">
							<label>Start Date</label>
						    <input id="start_date" type="text" class="datepicker dt form-control" value="`+date+`" data-provide="datepicker">
						    <div id="pemisah" style="padding-top:10%; padding-bottom:10%; padding-left:5%; padding-right:5%; border:none;" class="input-group-addon">to</div>
						    <label>End Date</label>
						    <input id="end_date" type="text" class="datepicker form-control" data-provide="datepicker">
						</div>
						<script>
							$(function(){
								$('.datepicker').datepicker({
								    format: 'yyyy-mm-dd'
								});

								// var cur_date = new Date();
								// var dt = new Date();
								// dt.setDate(cur_date.getDate()+3);

								// $('#end_date').val($.datepicker.formatDate('yy-mm-dd', dt));
								$('#pemisah').css('width','10%');
							});
						</script>
						`,
			buttons:{
				submit:{
					text:'SUBMIT',
					btnClass:'btn-primary',
					action:function(){
						var x = this;
						var s_date = x.$content.find('#start_date').val();
						var e_date = x.$content.find('#end_date').val();

						create_spb(s_date,e_date);
					}
				},
				close:{
					text:'CLOSE'
				}
			}
		});
	}
}

function create_spb(s_date=null,e_date=null){
	loading('#loading_create','#create_spb_submit','show','click');

	var table = $('#tb_spb_sup').DataTable().column([0,1]).visible(false);
	var tb_ls_spb = $('#tb_ls_spb').DataTable().column([0]).visible(false);
	var item = [];
	var supplier = [];

	if(table.data().length>0){
		var rows = table.rows().data();
		$.each(rows,function(key,val){
			item.push({
				id_item:table.cell(key,0).data(),
				id_supplier:table.cell(key,1).data(),
				id_pemesanan_brg:id_pemesanan,
				qty:table.cell(key,5).data(),
				harga:cnum(table.cell(key,8).data()),
			});

			var match = false;
			if(supplier.length>0){
				$.each(supplier,function(key_s,val_s){
					if(val_s.id_supplier==table.cell(key,1).data()){
						match=true;
					}
				});
				if(match==false){
					supplier.push({
						id_supplier:table.cell(key,1).data()
					});
				}
			}else{
				supplier.push({
					id_supplier:table.cell(key,1).data()
				});
			}
		});


		$.post(URL+'transaksi/create_spb',{id_pem:id_pemesanan,items:item,supplier:supplier,id_permintaan:id_permintaan,start:s_date,end:e_date}).done(function(data){
			loading('#loading_create','#create_spb_submit','hide','click');

			$('#tb_spb_sup').DataTable().clear().draw();
			var res = JSON.parse(data);

			if(res.status==1){
				$.confirm({
					title:'',
					content:'Item Sudah Lengkap.\n Ingin memverifikasi?',
					buttons:{
							ok:{
								text:'Ya, verifikasi!',
								btnClass:'btn-primary',
								action:function(){
									verifikasi_spb();
								}
							},
							close:{
								text:'Nanti',
								btnClass:'btn-default'
							}
					}

				});
			}

			$.each(res.spb,function(key,val){
				tb_ls_spb.row.add([
					val.id_spb,
					tb_ls_spb.data().length+1,
					val.no_spb,
					val.dibuat_oleh,
					format_jam(val.insert_date),
					val.diubah_oleh,
					`
					 <button onclick="sh_spb(`+val.id_spb+`)" type="button" class="l-`+val.id_spb+` btn btn-default btn-sm" data-toggle="tooltip" title="Lihat Rincian">
				        <span class="glyphicon glyphicon-fullscreen"></span>
				     </button>
					 <button onclick="del_spb(`+val.id_spb+`)" type="button" class="l-`+val.id_spb+` btn bg-red btn-sm" data-toggle="tooltip" title="Hapus">
				        <span class="glyphicon glyphicon-trash"></span>
				     </button>
				     <a href="`+URL+`transaksi/print_spb/`+val.id_spb+`" target="_blank">
					     <button type="button" class="l-`+val.id_spb+` btn bg-green btn-sm" data-toggle="tooltip" title="Cetak">
					        <span class="glyphicon glyphicon-print"></span>
					     </button>
				     </a>
				    `
				]).draw(false);

				var nodes = tb_ls_spb.column(0).nodes();
				$(nodes[tb_ls_spb.data().length-1]).addClass('tb-hide');

				tb_ls_spb.rows(tb_ls_spb.data().length-1).nodes().to$().addClass( 'highlight' );

			});

			var elem = $('tr.highlight');
			if(elem) {
			    $('html').scrollTop(elem.offset().top);
			    $('html').scrollLeft(elem.offset().left);
			}

			$('.highlight').css('background-color','green');
			$( ".highlight" ).animate({
				backgroundColor:'none'
			}, 1000, function() {
				$('.highlight').parent().find('tr').removeAttr('style');
				$('.highlight').parent().find('tr').removeClass('highlight');

			});
		}).fail(function(e){
			loading('#loading_create','#create_spb_submit','hide','click');
			$.alert('Terjadi kesalahan. Code : CRT-130');
		});
	}

	if(table.data().length<1){
		$.alert('Item Kosong.');
		loading('#loading_create','#create_spb_submit','hide','click');
	}

}

function del_it_create_spb(x){
	var tbl = $('#tb_spb_sup').DataTable();
	var row = tbl.row( $(x).parents('tr') );
	var data = tbl.row( row ).data();

	$.each(tmpItem,function(key,val){
		if(val.id_item==data[0]){
			tmpItem[key].qty_spb -= parseInt(data[5]);
		}
	});
	
	row.remove().draw(false);


}

function sh_spb(x){
	$.confirm({
		title:'List Item SPB',
		containerFluid:true,
		content:function(){
			var self = this;
			return $.post(URL + 'transaksi/sh_spb/',{id:x}).done(function(data){
				self.setContent(data);

			}).fail(function(e){
				self.setContent('Terjadi Kesalahan');
			});
		},
		columnClass:'large',
		buttons:{
			close:{
				text:'CLOSE',
				btnClass:'btn_close_periode'
			}
		}
	});
}

function edit_item(x,id=null,id_item=null){
	// alert('');
	var it_val = parseInt(x.parent().parent().find('td.edit_qty').html());
	var it_stock = parseInt(x.parent().parent().find('td:eq(3)').html());
	var b = x.parent().find('button.it_edit');
	

	var s = b.find('span');
	
	//alert(s.attr('class'));
	if(s.hasClass('glyphicon-pencil')){
		var jml_awal = parseInt(x.parent().parent().find('td.edit_qty').html());
		x.parent().parent().find('td.edit_qty').attr('awal',jml_awal);
		x.parent().parent().find('td.edit_qty').html('<input type="number" style="width:100%" onkeydown="calc($(this))" class="edit_item_inline" value="'+ it_val +'">');	

		x.parent().parent().find('td.edit_qty input.edit_item_inline').focus();
		x.parent().parent().find('td.edit_qty input.edit_item_inline').select();
		b.removeClass('btn-warning').addClass('btn-success');
		s.removeClass('glyphicon-pencil').addClass('glyphicon-ok');
	}else{
		// alert('');
		if(s.hasClass('glyphicon-ok')){
			// alert('');

			loading('#loading_spb','.bt-hide','show','display');
			var jml_awal = x.parent().parent().find('td.edit_qty').attr('awal');
			//x.parent().parent().find('td.edit_qty').html('<input style="width:100%" class="edit_item_inline">');	
			var edit_val = parseInt(x.parent().parent().find('td.edit_qty input.edit_item_inline').val());
			$.each(tmpItem,function(key,val){
				if(val.id_item==id_item){
					if(((tmpItem[key].qty_spb-jml_awal)+edit_val)>tmpItem[key].qty){
						$.alert('Jumlah input melebihi jumlah keseluruhan SPB pada item ini.');
						loading('#loading_spb','.bt-hide','hide','display');
					}else{
						tmpItem[key].qty_spb = (tmpItem[key].qty_spb-jml_awal)+edit_val;

						x.parent().parent().find('td.edit_qty').html(edit_val);
						b.removeClass('btn-success').addClass('btn-warning');
						s.removeClass('glyphicon-ok').addClass('glyphicon-pencil');

						var table_it_spb = $('#tb_ls_item_spb').DataTable();
						var tr = x.parent().parent();
						var td = x.parent();
						var indexRow = table_it_spb.cell(td).index().row;
						var jml = edit_val;
						// alert(id_item);


						$.post(URL+'transaksi/edit_item_spb',{id:id,jumlah:jml,jumlah_awal:jml_awal})
						.done(function(data){
							var res = JSON.parse(data);
							loading('#loading_spb','.bt-hide','hide','display');
							if(res.status==1){
								$.confirm({
									title:'',
									content:'Item Sudah Lengkap.\n Ingin memverifikasi?',
									buttons:{
											ok:{
												text:'Ya, verifikasi!',
												btnClass:'btn-primary',
												action:function(){
													verifikasi_spb();
												}
											},
											close:{
												text:'Nanti',
												btnClass:'btn-default'
											}
									}

								});
							}
						}).fail(function(e){
							loading('#loading_spb','.bt-hide','hide','display');
							$.alert('Terjadi Kesalahan. Error Code : (ED_IT_SPB-101)');
						});
					}
					
				}
			});

		}
	}
}

function calc(x){
	setTimeout(function(){
		var table_it_spb = $('#tb_ls_item_spb').DataTable();
		var tr = x.parent().parent();
		var td = x.parent();
		var indexRow = table_it_spb.cell(td).index().row;
		var dt_row = table_it_spb.row(tr).data();
		table_it_spb.cell(indexRow,8).data(num(parseInt(x.val())*parseInt(cnum(dt_row[7]))));
		tr.find('td.edit_total').html(num(parseInt(x.val())*parseInt(cnum(tr.find('td.edit_harga').html()))));
	},20);
}

function verifikasi_spb(){
	startloading('Sedang memverifikasi...');
	$.post(URL+'transaksi/verifikasi_spb/',{id:id_permintaan}).done(function(data_ver){
		endloading();
		var res_ver = JSON.parse(data_ver);
		if(res_ver.status==1){
			$.alert({
				title:'',
				content:res_ver.message,
				containerFluid:true,
				buttons:{
					ok:{
						text:'SELESAI',
						action:function(){
							location.reload();
						}
					}
				}
			});
		}else{
			$.alert({
				title:'',
				content:res_ver.message,
				containerFluid:true,
				columnClass:'medium',
				buttons:{
					close:{
						text:'CLOSE'
					}
				}
			});
		}


	}).fail(function(e){
		$.alert('Terjadi Kesalahan. Err-Code : ER-VER(12368765)');
		endloading();
	});
}

function del_spb(x){

	$.confirm({
		title:'Menghapus SPB',
		containerFluid:true,
		content:'Anda yakin ingin menghapus SPB ini?',
		buttons:{
			ok:{
				text:'HAPUS',
				btnClass:'btn-danger',
				action:function(){
					startloading('Menghapus data SPB..');
					$.post(URL + 'transaksi/del_spb',{id:x}).done(function(data){
						endloading();
						var res = JSON.parse(data);
						if(res.status==1){
							$.alert({
								title:'',
								content:res.message,
								buttons:{
									ok:{
										text:'CLOSE',
										action:function(){
											location.reload();
										}
									}
								}
							});
						}else{
							$.alert(res.message);
						}
					}).fail(function(e){
						endloading();
						$.alert('Terjadi Kesalahan. Error Code : DEL-IT-SPB(872386490)');
					});
				}
			},
			close:{
				text:'CANCEL'
			}
		}
	});
	
}

function reload_table(x){

		$('#tb_ls_spb').DataTable({searching: false, paging: false}).columns.adjust().column([0]).visible(false).draw();
		$('#tb_pemesanan_brg').DataTable({searching: true, paging: true, pageLength: 100}).columns.adjust().draw();
		$('#tb_spb_sup').DataTable({searching: false, paging: false}).columns.adjust().column([0,1]).visible(false).draw();
		$('#tb_ls_item_spb').DataTable({searching: false, paging: false}).columns.adjust().column([0,1]).visible(false).draw();


}

function ed_date_period(x){
	var start_date = new Date(x.parent().parent().find('#start_date_per').attr('date'));
	var end_date = new Date(x.parents().eq(3).find('#end_date_per').attr('date'));

	var s_date = $.datepicker.formatDate('yy-mm-dd', start_date);
	var e_date = $.datepicker.formatDate('yy-mm-dd', end_date);
	
	$.confirm({
		title:'Periode Tanggal SPB',
		content:`	<div  class="input-group input-daterange date" data-provide="datepicker">
						<label>Start Date</label>
					    <input id="start_date" type="text" class="datepicker form-control" value="`+s_date+`" data-provide="datepicker">
					    <div id="pemisah" style="padding-top:10%; padding-bottom:10%; padding-left:5%; padding-right:5%; border:none;" class="input-group-addon">to</div>
					    <label>End Date</label>
					    <input id="end_date" type="text" class="datepicker form-control" value="`+e_date+`" data-provide="datepicker">
					</div>
					<script>
						$(function(){
							$('.datepicker').datepicker({
							    format: 'yyyy-mm-dd'
							});

							$('#pemisah').css('width','10%');
						});
					</script>
					`,
		buttons:{
			submit:{
				text:'SUBMIT',
				btnClass:'btn-primary',
				action:function(){
					$('#table_spb').css('cursor','wait');
					$('#btn_change_periode').prop('disabled',true);

					$('.btn_close_periode').css('cursor','wait');
					$('.btn_close_periode').prop('disabled',true);

					var d = this;

					var sd = d.$content.find('#start_date').val();
					var ed = d.$content.find('#end_date').val();

					var s_fDate = $.datepicker.formatDate('dd M yy', new Date(sd));
					var e_fDate = $.datepicker.formatDate('dd M yy', new Date(ed));

					

					$.post(URL+'transaksi/ch_period',{id:id_spb,start:sd,end:ed}).done(function(data){
						$('#table_spb').css('cursor','');
						$('#btn_change_periode').prop('disabled',false);

						$('.btn_close_periode').css('cursor','');
						$('.btn_close_periode').prop('disabled',false);

						var res = JSON.parse(data);
						if(res.status==1){
							$.alert(res.message);
							var k = x.parents().eq(3);
							k.find('#start_date_per').html(s_fDate);
							k.find('#end_date_per').html(e_fDate);


						}else{
							$.alert(res.message);
						}

						
					}).fail(function(){
						$('#table_spb').css('cursor','');
						$('#btn_change_periode').prop('disabled',false);

						$('.btn_close_periode').css('cursor','');
						$('.btn_close_periode').prop('disabled',false);

						$.alert('Terjadi kesalahan. Err-Code:CH-DT-PER(918974734)');
					});


				}
			},
			close:{
				text:'CLOSE'
			}
		}
	});
}