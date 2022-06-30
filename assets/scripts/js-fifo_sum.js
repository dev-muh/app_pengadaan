var log = [];

var table = $('#table_fifo').DataTable({
	pageLength:100
});


function ch(x,y){
	// alert('');
	$('#jumlah_saldo').empty();
    $('#biaya_saldo').empty();
    $('#jumlah_pengambilan').empty();
    $('#biaya_pengambilan').empty();

	$('#row_res').hide();
	$('#res_sum').empty();
	startloading('Mohon tunggu...');
	$('#res_sum').load(URL+'fifoReport/fifo_sum/'+x+'/'+y,function(res,stat,xhr){
		if(stat=='success'){
			endloading();
			$('#row_res').show();
			$('#res_sum').html(res);
		}

		if(stat=='error'){
			endloading();
			$('#res_sum').html('<center><b>Data tidak ada</b></center>');
		}
	});
}

function export_pdf_fifo_summary(bulan, tahun) {
	// console.log(table.rows().data)
    window.location.href = URL + 'fifoReport/export_pdf_fifo_summary?bulan=' + bulan + '&tahun=' + tahun;
    // var formData = new FormData();
    // formData.append('tabledata', JSON.stringify(table.rows().data().toArray()))
    // $.ajax({
    // 	url: URL + 'fifoReport/export_pdf_fifo_summary',
    // 	type: 'GET',
    // 	dataType: 'json',
    // 	data: formData,
    // })
    // .done(function() {
    // 	console.log("success");
    // })
    // .fail(function() {
    // 	console.log("error");
    // })
    // .always(function() {
    // 	console.log("complete");
    // });
    
}

function export_excel_fifo_summary(bulan, tahun) {
	// console.log(table.rows().data)
    window.location.href = URL + 'fifoReport/export_excel_fifo_summary?bulan=' + bulan + '&tahun=' + tahun;
}

// $.dialog({
// 			title:'Loading Item',
// 			content: `<div class="col-md-2"><img width="100%" src="`+URL+`assets/dist/img/loading.gif"></div><div class="col-md-9"><iframe id="frm" width="100%" height="50" frameBorder="0" src="`+URL+`fifoReport/tt?bulan=`+bulan+`&tahun=`+tahun+`"></iframe></div>`,
// 			containerFluid:true,
// 			onContentReady: function () {
// 				startloading('Mohon tunggu...');


// 				var self = this;
// 		        var ifr=document.getElementById('frm');
// 		        var fr = $('#frm');

// 			    ifr.onload=function(){
			        
// 			        setTimeout(function(){
// 			        	var items = fr.contents().find('.item');
// 			        	t_jumlah_saldo = 0;
// 			        	t_biaya_saldo = 0;
// 						t_jumlah_pengambilan = 0;
// 						t_biaya_pengambilan = 0;
			        	
// 			        	$.each(items,function(key,index){
// 			        		var name = fr.contents().find('.item:eq('+key+')').find('li.name').text();
// 			        		var jumlah_saldo = fr.contents().find('.item:eq('+key+')').find('li.jumlah_saldo').text();
// 			        		var biaya_saldo = fr.contents().find('.item:eq('+key+')').find('li.biaya_saldo').text();
// 			        		var jumlah_pengambilan = fr.contents().find('.item:eq('+key+')').find('li.jumlah_pengambilan').text();
// 			        		var biaya_pengambilan = fr.contents().find('.item:eq('+key+')').find('li.biaya_pengambilan').text();

// 			        		table.row.add([
// 			        			name,
// 			        			jumlah_saldo,
// 			        			'Rp '+num(biaya_saldo),
// 			        			jumlah_pengambilan,
// 			        			'Rp'+num(biaya_pengambilan)

// 			        		]).draw(false);

// 			        		t_jumlah_saldo += parseInt(jumlah_saldo);
// 				        	t_biaya_saldo += parseInt(biaya_saldo);
// 							t_jumlah_pengambilan += parseInt(jumlah_pengambilan);
// 							t_biaya_pengambilan += parseInt(biaya_pengambilan);

// 			        	});
// 			        	var total = fr.contents().find('#total_akhir').text();


// 			        	$('#total').html('Rp '+num(total));
// 			        	$('#jumlah_saldo').html(num(t_jumlah_saldo).replace(',-',''));
// 			        	$('#biaya_saldo').html('Rp '+num(t_biaya_saldo));
// 			        	$('#jumlah_pengambilan').html(num(t_jumlah_pengambilan).replace(',-',''));
// 			        	$('#biaya_pengambilan').html('Rp '+num(t_biaya_pengambilan));
// 			        	table.draw(false);

// 			        	$('.jconfirm').fadeOut('fast',function(){
// 			        		$('.jconfirm').remove();
// 			        		endloading();
// 			        	});

// 			        },300);
// 			    };
// 			}
// });

