$(function(){
	$('#tb_penerimaan').DataTable({pageLength:100});



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

function show_note(x,mode=null){
	var edit;
	if(mode!='edit'){
		edit='readonly';
		var btn = {
			close:{
				text:'CLOSE'
			}
		}
	}else{
		var btn = {
			submit:{
				text:'SUBMIT',
				btnClass:'btn-primary',
				action:function(){
					var iki = this;
					$('#note').val(iki.$content.find('#note_p').val());
				}
			},
			close:{
				text:'CLOSE'
			}
		}
	}


	$.alert({
		title:'Catatan',
		content:`<textarea id="note_p" `+edit+`>`+x+`</textarea>`,
		buttons:btn,
		onContentReady: function () {
	        var el = this;
	        setTimeout(function(){
			    el.$content.find('#note_p').height(el.$content.find('#note_p').get(0).scrollHeight);
			},0);
	    }
	});
}

function get_pdf_bast(item_id){
  var content = '<label>Tanggal BAST</label><input id="tanggal-bast-generate-bast" class="form-control datepicker" />';
  $.confirm({
    title:'Generate BAST',
    // containerFluid:true,
    content: content,
    onContentReady: function () {
        // when content is fetched & rendered in DOM
        var self = this;
        this.$content.find('.datepicker').datepicker();
    },
    columnClass:'large',
    buttons: {
      submit: {
        text: "Generate BAST",
        btnClass: "btn-info",
        action: function () {
          window.location.href = URL + 'transaksi/export_pdf_bast?id=' + item_id + '&date=' + $('#tanggal-bast-generate-bast').val()
        }
      }
    }
  });
    ;
}

function sh_penerimaan(x,judul,mode=null){

	var btn;
	it_sel_qty = null;
	if(mode=='edit'){
		btn = {
			submit:{
				text:'SUBMIT',
				btnClass:'btn-success',
				action:function(){
					startloading('Sedang memverifikasi...');
					var self = this.$content;
					var tb = self.find('#tb_ls_item_spb').DataTable();
					var items = [];
					var note = $('#note').val();

					$.each(tb.data(),function(key,val){
						items.push({
							id_item:tb.cell(key,0).data(),
							id_supplier:tb.cell(key,1).data(),
							id_spb:x,
							qty:tb.cell(key,9).data()
						});
					});

					$.post(URL+'transaksi/verifikasi_penerimaan_barang',{id:x,items:items,note:note}).done(function(data){
						endloading();
						var res = JSON.parse(data);
						$.alert({
							title:'',
							content:res.message,
							buttons:{
								close:{
									text:'CLOSE',
									action:function(){
										location.reload();
									}
								}
							}	
						});
					}).fail(function(e){
						endloading();
						$.alert('Terjadi kesalahan. Error-Code : VER-PEN(984663434)');
					});
					
				}
			},
			close:{
				text:'CLOSE'
			}
		}
	}else{
		btn = {
			close:{
				text:'CLOSE'
			}
		}
	}


	$.confirm({
		title:judul,
		containerFluid:true,
		content:function(){
			var self = this;
			return $.post(URL + 'transaksi/sh_penerimaan/',{id:x,mode:mode}).done(function(data){
				self.setContent(data);

			}).fail(function(e){
				self.setContent('Terjadi Kesalahan');
			});
		},
		columnClass:'large',
		buttons:btn
	});
}


var jml_awal = 0;
var it_sel_qty = null;
var it_sel_jml = null;

function set_it_masuk(x){
	it_sel_qty = null;
	$('.highlight').parent().find('tr').removeClass('highlight');
	var val_it = x.val().split('|');
	var tb = $('#tb_ls_item_spb').DataTable();


	$('#bc').val(val_it[3]);

	$.each(tb.data(),function(key,val){
		var br = tb.cell(key,3).data();
		var jml = tb.cell(key,5).data();
		var aw = tb.cell(key,9).data();
		jml_awal = aw;
		if(val_it[3]==br){
			// tb.cell(key,9).data();
			tb.rows(key).nodes().to$().addClass( 'highlight' );
			it_sel_qty = key;
			it_sel_jml = jml;

			
			var content = $('.jconfirm-content-pane').offset().top;
			var td = $('.jconfirm-content-pane').find('.highlight:last').offset().top;
			
			$('.jconfirm-content-pane').animate({scrollTop: (td-content)},200);
			
			$('.highlight').css('background-color','green');
			$( ".highlight" ).animate({
				backgroundColor:'none'
			}, 1000, function() {
				$('.highlight').parent().find('tr').removeAttr('style');
				// $('.highlight').parent().find('tr').removeClass('highlight');

			});

			setTimeout(function(){
				$('#qty').focus().select();
				// $('#sl_it').prop('disabled',true);
			},100);
		}
	});
	
}

function reset(x){
	var tb = $('#tb_ls_item_spb').DataTable();
	tb.cell(x.parent().parent(),9).data(0).draw();
	
}
function add_qty(x){
	var tb = $('#tb_ls_item_spb').DataTable();
	var qty = parseInt($('#qty').val());
	var msk = parseInt(tb.cell(it_sel_qty,10).data());
	var aw = parseInt(tb.cell(it_sel_qty,9).data());
	if(qty>0){
		if(it_sel_qty!=null){
			

			if((qty+aw+msk)>it_sel_jml){
				// alert(qty+'-'+aw+'-'+it_sel_jml);
				$.alert('Jumlah yang masuk melebihi jumlah pemesanan.');
				$('#bc').val('');
				$('#qty').val('');
				$('#sl_it').prop('selectedIndex',0).select2({dropdownCssClass: "dropdownCLASS",width:"100%"});
			}else{
				
				
				tb.cell(it_sel_qty,9).data(qty+aw).draw();

				$('#sl_it').prop('selectedIndex',0).select2({dropdownCssClass: "dropdownCLASS",width:"100%"});
				$('#bc').val('');
				$('#qty').val('');
				it_sel_qty = null;
				it_sel_jml = null;
			}
		}
	}
}

function autoenter(){
	setTimeout(function(){
		if($('#auto_enter').is(':checked')==true){
			$('#qty').prop('disabled',true);
			$('#btn_add').prop('disabled',true);
			$('#sl_it').prop('disabled',true);
			$('#bc').focus().val('');
		}else{
			$('#qty').prop('disabled',false);
			$('#btn_add').prop('disabled',false);
			$('#sl_it').prop('disabled',false);
			$('#bc').focus().val('');
		}
	},10);
}

function set_it_msk(x,key,aw,jml){
	var tb = $('#tb_ls_item_spb').DataTable();
	var qty = $('#inp'+key).val();
	if(qty>jml){
		$.alert('Jumlah yang masuk melebihi jumlah pemesanan.');
	}else{
		var msk = tb.cell(key,9).data($('#inp'+key).val());
		$('#sl_it').prop('selectedIndex',0).select2({dropdownCssClass: "dropdownCLASS"});
		$('#sl_it').prop('disabled',false);

	}
	
}

function verifikasi_penerimaan(){

}

function prepareUpload(event,id_spb=null){
    //console.log(event);
  files = event.target.files;
  uploadFiles(event,id_spb);
}

function uploadFiles(event,id_spb=null)
{

    var number_file = 'S_'+new Date().getTime();
    event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening

    // START A LOADING SPINNER HERE
    startloading('Sedang mengupload file...');

    // Create a formdata object and add the files
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });

    $.ajax({
        url: URL+'upload/upload_attach?files&number='+ number_file+'&id='+id_spb,
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
            endloading();
            if(typeof data.error === 'undefined')
            {
                // Success so call function to process the form
                // submitForm(event, data);
                $('#file_uploaded_add').html('<a target="_blank" href="'+URL+'assets/customer_attach/'+data.message+'">'+data.message+'</a><a href="#" onclick="del_att('+id_spb+')" style="color:red;"> X</a>');
                $('#cust_k_attach').val('').hide();
                $('#cust_k_attach_hide').val(data.message).hide();
                $('.btn.btn-upload').hide();
                $('.upload-btn-wrapper').hide();

                if(data.status==1){
                   $.alert({
                        title:'Sukses',
                        content:'File : ' + data.message + ' telah terupload.',
                        buttons:{
                            ok:function(){
                                
                            }
                        }
                    }); 
                }else{
                    $.alert({
                        title:'Error',
                        content:'File : ' + data.message + ' gagal di upload.',
                        buttons:{
                            ok:function(){
                                
                            }
                        }
                    });
                }
                
                // successUpload();

            }
            else
            {
                // Handle errors here
                endloading();
                console.log('ERRORS: ' + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            $.alert({
                title:'Error',
                content:'File gagal di upload.',
                buttons:{
                    ok:function(){
                        $('#cust_k_attach').val('');
                        $('#cust_k_attach_hide').val('');
                    }
                }
            });
            console.log('ERRORS: ' + textStatus);
            // STOP LOADING SPINNER
            endloading();
        }
    });
}

function del_att(x){
	loading('#loading_up_spb','.upload_spb','show','display');
    $('.upload-btn-wrapper').hide();
    // $('.upload-btn-wrapper').show();

    $.post(URL+'transaksi/del_upload_file_spb',{id:x}).done(function(data){
    	loading('#loading_up_spb','.upload_spb','hide','display');
    	var res = JSON.parse(data);
    	if(res.status==1){
    		$('#file_uploaded_add').html('');
		    $('#cust_k_attach').show();
		    $('.btn.btn-upload').show();
		    $.alert(res.message);
    	}else{
    		$('.upload-btn-wrapper').hide();
    		$.alert(res,message);
    		// $('.upload_spb').show();
    	}
    }).fail(function(e){
    	loading('#loading_up_spb','.upload_spb','hide','display');
    	$.alert('Terjadi Kesalahan. ERR-UP(79809234882787)');
    });
}