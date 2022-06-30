$(function(){
	$('.table').DataTable({pageLength:100});
	// $('#tb_master_supplier').DataTable({"order": [[ 1, "asc" ]]});

	if(item_get!=''){
		sel_prod(item_get);
		$('.inp_it').val(item_get).select2();
	}
});

function add_master_supplier(reload=null,mode=null,x=null){
	
	var id = '';
	var s_id = '';
	var s_name = '';
	var s_address = '';
	var s_pic = '';
	var s_phone = '';

	if(mode=='edit'){
		var dt_sup = x.attr('data_sup').split('|');

		id = dt_sup[0];
		s_id = dt_sup[1];
		s_name = dt_sup[2];
		s_address = dt_sup[3];
		s_pic = dt_sup[4];
		s_phone = dt_sup[5];

	}
	
	$.confirm({
		title:'Add Supplier',
		content:`
				<form id="frm_sup">
					<div class="form-group">
						<label>Supplier ID</label>
						<input id="s_id" name="s_id" class="form-control" type="text" value="`+s_id+`">
					</div>
					<p id="status_id"></p>
					<div class="form-group">
						<label>Supplier Name</label>
						<input name="s_name" class="form-control" type="text" value="`+s_name+`" disabled="disabled">
					</div>
					<div class="form-group">
						<label>Supplier Address</label>
						<input name="s_address" class="form-control" type="text" value="`+s_address+`" disabled="disabled">
					</div>
					<div class="form-group">
						<label>Supplier PIC Name</label>
						<input name="s_pic" class="form-control" type="text" value="`+s_pic+`" disabled="disabled">
					</div>
					<div class="form-group">
						<label>Supplier Phone</label>
						<input name="s_phone" class="form-control" type="text" value="`+s_phone+`" disabled="disabled">
					</div>
				</form>

				<script>
					$(function(){
						var mode = '`+mode+`';

						if(mode=='edit'){
							var f = $('#frm_sup');
							f.find('input[name=s_id]').prop('disabled',true);
							f.find('input[name=s_name]').prop('disabled',false);
							f.find('input[name=s_address]').prop('disabled',false);
							f.find('input[name=s_pic]').prop('disabled',false);
							f.find('input[name=s_phone]').prop('disabled',false);
						}

						$('#s_id').on('keydown paste',function(){
							
							setTimeout(function(){
								ck_id($('#s_id').val());
							},100);
						});


					});
				</script>
				`,
		buttons:{
			ok:{
				text:'Submit',
				btnClass:'bg-blue',
				action:function(){
					startloading('Mohon menunggu. Sedang menambahkan supplier...');
					var self = this.$content;
					var supplier = [];

					supplier.push({
						supplier_id:self.find('input[name=s_id]').val(),
						supplier_name:self.find('input[name=s_name]').val(),
						supplier_address:self.find('input[name=s_address]').val(),
						supplier_pic_name:self.find('input[name=s_pic]').val(),
						supplier_phone:self.find('input[name=s_phone]').val()
					});

					$.post(URL+'master/add_master_supplier',{mode:mode,id:id,supplier:supplier[0]}).done(function(data){
						endloading();
						var res = JSON.parse(data);

						if(res.status==1){
							$.alert({
								title:'',
								content:res.message,
								buttons:{
									close:{
										text:'CLOSE',
										btnClass:'btn-success',
										action:function(){
											if(reload=='no'){
												$('#sel_sup').append('<option value="'+res.result.id+'|'+res.result.supplier_name+'">'+res.result.supplier_name+'</option>');
											}else{
												window.location = URL+'master/master_supplier_all';
											}
										}
									}
								}
							});
						}else{
							if(res.status==2){
								$.alert({
									title:'',
									content:res.message,
									buttons:{
										close:{
											text:'CLOSE',
											btnClass:'btn-success',
											action:function(){												
												window.location = URL+'master/master_supplier_all';
											}
										}
									}
								});
							}else{
								$.alert(res.message);
							}
						}

					}).fail(function(e){
						endloading();
						$.alert('Terjadi Kesalahan');
					});
				}
			},
			close:{
				text:'Close'
			}
		}
	});
}

function ck_id(x){

	var f_opt = function(opt,status,color){
		var f = $('#frm_sup');
		f.find('input[name=s_name]').prop('disabled',opt);
		f.find('input[name=s_address]').prop('disabled',opt);
		f.find('input[name=s_pic]').prop('disabled',opt);
		f.find('input[name=s_phone]').prop('disabled',opt);

		f.find('p#status_id').html(status).css('color',color);
	}

	var v_id = $('#frm_sup').find('input[name=s_id]').val();
	if(v_id==''||v_id==null){
		f_opt(true,'ID Belum Diisi','red');
	}else{
		$.post(URL+'master/ck_id/' + x).done(function(data){
			var res = JSON.parse(data);
			if(res.status==1){
				f_opt(false,res.message,'green');
			}else{

				f_opt(true,res.message,'red');
				$('#s_id').css('background-color','red');
				$( "#s_id" ).animate({
					backgroundColor:'none'
				}, 1000, function() {
					
				});
			}
		}).fail(function(){
			f_opt(false,res.message,'green');
		});
	}
	
}


function sh_master_harga(x=null,i=null){
	$.confirm({
		title:'',
		content:function(){
			var self = this;
			return $.post(URL + 'master/sh_tb_sup_harga/'+ x+'/view').done(function(data){
				self.setContent(data);
			}).fail(function(e){
				self.setContent('Terjadi Kesalahan');
			});
		},
		columnClass:'small',
		buttons:{
			close:{
				text:'CLOSE'
			}
		}
		// containerFluid:true
	});
}

function add_master_harga(){
	$.confirm({
		title:'Tambah Harga : ',
		content:function(){
			var self = this;
			return $.post(URL + 'master/sh_pemesanan_brg/'+ x).done(function(data){
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

function sel_prod(x){
	loading('#loading_item','.inp_it','show');
	$.post(URL+'master/sh_tb_sup_harga/'+x+'/add').done(function(data){
		loading('#loading_item','.inp_it','hide');
		$('#tb_ls_supplier').html(data);
		$('#tb_sp_hrg').DataTable({searching: false, paging: false,order: [[ 1, 'asc' ]]}).column([0]).visible(false);
		$('.select2.act').select2();
	}).fail(function(e){
		loading('#loading_item','.inp_it','hide');
		$.alert('Terjadi Kesalahan. Error Code : SELECT-PRODUK(3312)');
	});
}

function loading(el,inp,x){
	if(x=='show'){
		$(el).css('display','block');
		$(inp).attr('disabled','disabled');
	}
	if(x=='hide'){
		$(el).css('display','none');
		$(inp).attr('disabled',false);
	}
}

function add_it_harga(){
	loading('#loading_sup','.inp_hrg','show');
	// startloading('Sedang mengirim data..');

	var match = false;
	var tb_hrg = $('#tb_sp_hrg').DataTable();

	var insert_harga = function(){
		var sup = [];
		sup.push({
			id_item:id_item,
			id_supplier:$('#sel_sup').val().split('|')[0],
			harga:cnum_n($('#hrg_frm_sup').val())
		});
		
		$.post(URL+'master/add_master_harga',{data:sup}).done(function(data){
			loading('#loading_sup','.inp_hrg','hide');
			// endloading();
			tb_hrg.row.add([
					$('#sel_sup').val().split('|')[0],
					tb_hrg.data().length+1,
					$('#sel_sup').val().split('|')[1],
					num(cnum_n($('#hrg_frm_sup').val())),
					`<button class="btn btn-edit btn-warning btn-sm" onclick="edit(this,'edit')">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button>

                    <button style="display: none" class="btn btn-ok btn-success btn-sm" onclick="edit(this,'save')">
                        <span class="glyphicon glyphicon-ok"></span>
                    </button>

                    <button class="btn btn-danger btn-sm" onclick="del(this)">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>`
			]).draw(false);

			$('#hrg_frm_sup').val('');
			$('#sel_sup').prop('selectedIndex',0);
			$('#sel_sup.select2.act').select2();

		}).fail(function(e){
			$.alert('Terjadi Kesalahan. Error Code : ADD-HRG->3330333');
			loading('#loading_sup','.inp_hrg','hide');
			// endloading();
		});
	}

	if(tb_hrg.data().length>0){
		$.each(tb_hrg.rows().data(),function(key,val){
			if($('#sel_sup').val().split('|')[0]==val[0]){
				match = true;
				// endloading();
				loading('#loading_sup','.inp_hrg','hide');
				$.alert('Data sudah ada.');
			}
		});

		if(match==false){
			insert_harga();
		}
	}else{
		insert_harga();
	}
}

function del(x){
	
	var table = $('#tb_sp_hrg').DataTable();
 	var id_sup = table.cell( $(x).parents('tr'),0 ).data();

 	$.confirm({
		title:'',
		content:'Anda yakin ingin menghapus data Harga Supplier?',
		buttons:{
			ok:{
				text:'HAPUS',
				btnClass:'btn-danger',
				action:function(){
					startloading('Menghapus supplier dari item...');
					 	$.post(URL+'master/delete_hrg/',{id_sup:id_sup,id_it:id_item}).done(function(data){
					 		endloading();
							// table.row( $(x).parents('tr') ).remove().draw();
							window.location = URL + 'master/add_harga_sup/?mode=edit&id='+id_item;
						}).fail(function(e){
							endloading();
							$.alert('Terjadi Kesalahan. Error Code : DEL-PRODUK(7867867)');
						});
				}
			},close:{
				text:'CANCEL'
			}
		}
	});

}

function del_supplier(x){
	

	$.confirm({
		title:'',
		content:'Anda yakin ingin menghapus data Supplier?',
		buttons:{
			ok:{
				text:'HAPUS',
				btnClass:'btn-danger',
				action:function(){
					startloading('Menghapus supplier...');
				 	$.post(URL+'master/delete_supplier_frm_produk/',{id:x}).done(function(data){
				 		endloading();
				 		var res = JSON.parse(data);
				 		if(res.status==1){
							$.alert({
								title:'',
								content:res.message,
								buttons:{
									ok:{
										text:'OKE',
										action:function(){
											window.location = URL + 'master/master_supplier_all';
										}
									}
								}
							});
				 		}else{
				 			$.alert({
								title:'',
								content:res.message,
								buttons:{
									ok:{
										text:'OKE'
									}
								}
							});
				 		}
					}).fail(function(e){
						endloading();
						$.alert('Terjadi Kesalahan. Error Code : DEL-PRODUK(7867867)');
					});
				}
			},close:{
				text:'CANCEL'
			}
		}
	});

}

function edit(x,y=null){

	var table = $('#tb_sp_hrg').DataTable();
 	var id_sup = table.cell( $(x).parents('tr'),0 ).data();
 	if(y=='edit'){
		table.cell( $(x).parents('tr'),3 ).data('<input class="edit" type="text">');
		$(x).parents('tr').find('.btn-edit').hide();
		$(x).parents('tr').find('.btn-ok').show();
 	}else{
 		startloading('Mengubah harga produk...');
 		table.cell( $(x).parents('tr'),3 ).data($(x).parents('tr').find('.edit').val());
 		var hrg = table.cell( $(x).parents('tr'),3 ).data();

 		$(x).parents('tr').find('.btn-edit').show();
		$(x).parents('tr').find('.btn-ok').hide();

		$.post(URL+'master/ch_harga/',{id_sup:id_sup,id_it:id_item,harga:hrg}).done(function(data){
			endloading();
			$.alert({
				title:'',
				content:'Sukses mengubah harga Produk pada supplier',
				buttons:{
					ok:{
						text:'OKE',
						action:function(){
							window.location = URL + 'master/add_harga_sup/?mode=edit&id='+id_item;
						}
					}
				}
			});
		}).fail(function(e){
			endloading();
			$.alert('Terjadi Kesalahan. Error Code : ED-PRODUK(456456454)');
		});
 	}
}


function changeGroup(x=null,nm=null){
	$.alert({
		title:'Ganti nama Group.',
		content:`
			<label for="nama_awal">Nama Baru</label>
			<input class="form-control" id="nm_baru" value="`+nm+`">
		`,
		buttons:{
			simpan:{
				text:'Simpan',
				btnClass:'btn-primary',
				action:function(){
					var self = this;
					let nama_baru = self.$content.find('input#nm_baru').val();

					if(nama_baru==''){
						alert('Nama tidak boleh kosong.');
						return false;
					}else{
						$.post(URL+'master/submit_ch_group',{id:x,nama_baru:nama_baru}).done((data)=>{
							location.reload();
						});
					}
				}
			},close:{
				text:'Close'
			}
		}
	});
}

function v_group(x=null){
	$.dialog({
		title:'History perubahan nama Group',
		content:function(){
			var self = this;
			return $.post(URL+'master/get_his_group',{id:x}).done((data)=>{
				self.setContent(data);
			});
		},columnClass:'col-md-12'
	});
}

function addGroup(){
	$.confirm({
		title:'Tambah Group',
		content:`
			<label for="nama_awal">Nama Baru</label>
			<input class="form-control" id="group_baru">
		`,
		buttons:{
			simpan:{
				text:'Simpan',
				btnClass:'btn-primary',
				action:function(){
					var self = this;
					let group_baru = self.$content.find('input#group_baru').val();

					if(group_baru==''){
						alert('Nama tidak boleh kosong.');
						return false;
					}else{
						$.post(URL+'master/submit_add_group',{group_baru:group_baru}).done((data)=>{
							if(data=='done'){
								location.reload();
							}else{
								if(data=='fail'){
									$.alert('Terjadi kesalahan.');
								}else{
									$.alert('Error terhubung ke server.');
								}
								
							}
							
						});
					}
				}
			},close:{
				text:'Close'
			}
		}
	});
}