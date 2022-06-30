$('#group_name').html(group);
var gr;
$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
});

var resetCanvas = function(){
	  $('#myChart').remove(); // this is my <canvas> element
	  $('#content_canvas').append('<canvas id="myChart"><canvas>');
	  canvas = document.querySelector('#myChart');
	  ctx = canvas.getContext('2d');
	  // ctx.canvas.width = $('#graph').width(); // resize to parent width
	  // ctx.canvas.height = $('#graph').height(); // resize to parent height
	  // var x = canvas.width/2;
	  // var y = canvas.height/2;
	  // ctx.font = '10pt Verdana';
	  // ctx.textAlign = 'center';
	  // ctx.fillText('This text is centered on the canvas', x, y);
};
function ch_by(by='',bln='',thn='',group='',type='',el=null, periode='', tanggal=''){
	if (type == 'employee') {
		gr=0;
		$('#emp_name').show();
		$('#group_div').show();
		$('#group_div_g').hide();
		$('#group_sel').prop('selectedIndex',0);
		$('#emp_sel').prop('selectedIndex',0).select2();
		$('#bln_sel').prop('selectedIndex',0);
		$('#th_sel').prop('selectedIndex',0);
		$('#group_div_g').hide();
	} else if(type == 'group') {
			gr=0;
			$('#group_div').hide();
			$('#emp_sel').prop('selectedIndex',0).select2();
			$('#bln_sel').prop('selectedIndex',0);
			$('#th_sel').prop('selectedIndex',0);
			$('#group_sel').prop('selectedIndex',0).select2({width:'100%'});
			$('#group_div_g').show();
			$('#emp_name').hide();
	}
	ch_bulan(by,bln,thn,group,type,el, periode, tanggal)
}
// function ch_by(x){
// 	// alert(userid);
// 	setTimeout(function(){

// 		if(x=='employee'){
			
// 			// if(sesi=='Karyawan'){
// 			// 	$('#emp_name').show();
// 			// 	$('#group_div').show();
// 			// 	$('#group_div_g').hide();
// 			// 	$('#emp_sel').val(userid).select2();
// 			// 	$('#bln_sel').prop('selectedIndex',0);
// 			// 	$('#th_sel').prop('selectedIndex',0);
// 			// 	$('#group_div_g').hide();
// 			// 	ch_bulan(userid,0,0,0,'employee');
// 			// }else{
// 				gr=0;
// 				$('#emp_name').show();
// 				$('#group_div').show();
// 				$('#group_div_g').hide();
// 				$('#group_sel').prop('selectedIndex',0);
// 				$('#emp_sel').prop('selectedIndex',0).select2();
// 				$('#bln_sel').prop('selectedIndex',0);
// 				$('#th_sel').prop('selectedIndex',0);
// 				$('#group_div_g').hide();
				
// 				// ch_bulan(0,0,0,0,'employee');
// 				ch_bulan($('#emp_sel').val().split('|')[0],
//                                           $('#bln_sel').val(),
//                                           $('#th_sel').val(),
//                                           $('#group_sel').val(),
//                                           'employee',
//                                           $(this),
//                                           $('#input_periode').val(),
//                                           $('#input_tanggal').val())
// 			// }
// 		}

// 		if(x=='group'){
// 			gr=0;
// 			$('#group_div').hide();
// 			$('#emp_sel').prop('selectedIndex',0).select2();
// 			$('#bln_sel').prop('selectedIndex',0);
// 			$('#th_sel').prop('selectedIndex',0);
// 			$('#group_sel').prop('selectedIndex',0).select2({width:'100%'});
// 			$('#group_div_g').show();
// 			$('#emp_name').hide();

// 			// ch_bulan(0,0,0,0,'group');
// 			ch_bulan($('#emp_sel').val().split('|')[0],
//                                           $('#bln_sel').val(),
//                                           $('#th_sel').val(),
//                                           $('#group_sel').val(),
//                                           'group',
//                                           $(this),
//                                           $('#input_periode').val(),
//                                           $('#input_tanggal').val())
// 		}
// 	},10);
// }
jQuery(document).ready(function($) {
	$('#input_periode').change(function(event) {
		/* Act on the event */
		if ($(this).val() == 'Harian') {
			$('#wrapper_select_bulan').hide();
			$('#wrapper_select_tahun').hide();
			$('#wrapper_select_tanggal').show();
		} else {
			$('#wrapper_select_bulan').show();
			$('#wrapper_select_tahun').show();
			$('#wrapper_select_tanggal').hide();
		}
	});

	$('#input_periode').trigger('change');
	$('#submit-filter').trigger('click')
});

var myBarChart;
function setCanvas(data,total,nama_item){

		var ctx = document.getElementById("myChart");
		
		try{
			myBarChart = new Chart(ctx, {

				    type: 'horizontalBar',

					data: data,				    
				    options: {
				    	title:{
				    		display:true,
				    		text:'Laporan Pengambilan Barang',
				    		fontSize:20,
				    		padding:20
				    	},
				    	layout: {
					            padding: {
					                left: 0,
					                right: 0,
					                top: 0,
					                bottom: 0
					            }
    					},
				        animation: {
				        	// easing:'linear',
				          	onComplete: function(animation) {
				          	
				              var ctx = this.chart.ctx;
				              // console.log(ctx);
				              // console.log(this);
				              // console.log(animation);
				              var id = this.id;
				              var data = this.config.data.datasets[0]._meta[id].data;
				              var data_value = this.config.data.datasets[0].data;
				              
				              // console.log(data); 
				              ctx.fillStyle = 'black';
				              ctx.font = '14px "Helvetica Neue", Helvetica, Arial, sans-serif';
				              ctx.textAlign = "left";
				              ctx.textBaseline = "bottom";
				             
				              $.each(data,function (index,key) {
				                  var x = key._model.x;
				                  var y = key._model.y;
				                  ctx.fillText(data_value[index] + " - " + nama_item[index], 30, y+32.5);
				              })
				              
				          }
				        },

				        responsiveAnimationDuration:2000,
				        maintainAspectRatio:false,
				        responsive:true,
				        legend: {
				            display: false
				        },
				        scales: {
				            xAxes: [{
				            	// gridLines: {
				             //        color: "rgba(0, 0, 0, 0)",
				             //    },
				                stacked: true,
				                display:true,
				                ticks: {
				                          beginAtZero: true,
				                          steps: 1,
				                          stepValue: 1,
				                          max: total+5
				                      }
				            }],
				            yAxes: [{
				            	// gridLines: {
				             //        color: "grey",
				             //    },
				                stacked: false,
				                barPercentage: 0.5
				            }]
				        }
				    }
			});
		}catch(e){
		}

	// myBarChart.config.data = data;
	// myBarChart.config.options.scales.xAxes[0].ticks.max = total;

	// myBarChart.options.title.text = '';

 //    myBarChart.update();

}

// $(function(){
// 	setInterval(function(){
// 		try{
// 			$('#Chartku-license-text').remove();
// 		}catch(e){

// 		}
		
// 	},1000);

// 	if(type_sel=='employee'){
// 		ch_by(type_sel);
// 	}
// 	if(type_sel=='group'){
// 		ch_by(type_sel);
// 	}
// 	ch_bulan($('#emp_sel').val().split('|')[0],$('#bln_sel').val(),$('#th_sel').val(),$('#group_sel').val(),type_sel);
// });
function randomColor(){
	return '#'+ ('000000' + Math.floor(Math.random()*16777215).toString(16)).slice(-6);
}
var toURL = {
	user : 0,
	bulan : 0,
	tahun : 0,
	group : 0,
	type : 'employee',
	type_sel : 'employee',
	element:null
};

var total_pengambilan = 0;
var jenis_barang;
var terbanyak;

function export_pdf_pengambilan_barang(by='',bln='',thn='',group='',type='',el=null, periode='', tanggal=''){
	if (periode == "Harian") {
		$('#form-export-pengambilan-barang-harian [name="export_type"]').val('pdf');
		$('#modal-report-harian').modal('show')
	} else {
		$('#form-export-pengambilan-barang-bulanan [name="export_type"]').val('pdf');
		$('#modal-report-bulanan').modal('show')
	}
	// window.location.href = URL + 'report/export_pdf_pengambilan_barang?by='+by+'&bulan='+ bln +'&tahun='+ thn + '&group='+group+'&type='+type+'&username=ykdigital&password=ykdigital&mobile=1'+'&periode='+periode+'&tanggal='+tanggal;
}

function export_excel_pengambilan_barang(by='',bln='',thn='',group='',type='',el=null, periode='', tanggal=''){
	if (periode == "Harian") {
		$('#form-export-pengambilan-barang-harian [name="export_type"]').val('excel');
		$('#modal-report-harian').modal('show')
	} else {
		$('#form-export-pengambilan-barang-bulanan [name="export_type"]').val('excel');
		$('#modal-report-bulanan').modal('show')
	}
	// window.location.href = URL + 'report/export_excel_pengambilan_barang?by='+by+'&bulan='+ bln +'&tahun='+ thn + '&group='+group+'&type='+type+'&username=ykdigital&password=ykdigital&mobile=1'+'&periode='+periode+'&tanggal='+tanggal;
}

$('#form-export-pengambilan-barang-harian').submit(function(event) {
	/* Act on the event */
	var by = $('#emp_sel').val().split('|')[0],
      bln = $('#bln_sel').val(),
      thn = $('#th_sel').val(),
      group = $('#group_sel').val(),
      type = type_sel,
      periode = $('#input_periode').val(),
      tanggal = $('#input_tanggal').val();

  var params = 'by='+by+'&bulan='+ bln +'&tahun='+ thn + '&group='+group+'&type='+type+'&username=ykdigital&password=ykdigital&mobile=1'+'&periode='+periode+'&tanggal='+tanggal + '&' + $(this).serialize();

  if ($('#form-export-pengambilan-barang-harian [name="export_type"]').val() == "pdf") {
		window.location.href = URL + 'report/export_pdf_pengambilan_barang?' + params;
  } else {
		window.location.href = URL + 'report/export_excel_pengambilan_barang?' + params;
  }
});

$('#form-export-pengambilan-barang-bulanan').submit(function(event) {
	/* Act on the event */
	var by = $('#emp_sel').val().split('|')[0],
      bln = $('#bln_sel').val(),
      thn = $('#th_sel').val(),
      group = $('#group_sel').val(),
      type = type_sel,
      periode = $('#input_periode').val(),
      tanggal = $('#input_tanggal').val();

  var params = 'by='+by+'&bulan='+ bln +'&tahun='+ thn + '&group='+group+'&type='+type+'&username=ykdigital&password=ykdigital&mobile=1'+'&periode='+periode+'&tanggal='+tanggal + '&' + $(this).serialize();

  if ($('#form-export-pengambilan-barang-bulanan [name="export_type"]').val() == "pdf") {
		window.location.href = URL + 'report/export_pdf_pengambilan_barang?' + params;
  } else {
		window.location.href = URL + 'report/export_excel_pengambilan_barang?' + params;
  }
});


function ch_bulan(by='',bln='',thn='',group='',type='',el=null, periode='', tanggal=''){
	startloading('Sedang memuat...');
	$('#total_pengambilan').html('0');
	$('#jumlah_terbanyak').html('0');
	$('#nama_barang_terbanyak').html('');
	// console.log(toURL);
	// alert(by+" "+bln+" "+thn+" "+group+" "+type);
	gr = $('#group_sel').val();
// alert(gr);
	user = by;
	bulan = bln;
	tahun = thn;
	type = type;
	type_sel = type;

	$('#myChart').fadeOut('fast');
	$('#nodata').fadeOut('fast');

	window.history.pushState("object or string", "Title", URL+'report/log_by_karyawan?by='+by+'&bulan='+ bln +'&tahun='+ thn+'&group='+ group+'&type='+type+'&periode='+periode+'&tanggal='+tanggal);
	
	if(el!=null){
		el.attr('disabled','disabled');
	}
	
	$.post(URL+'report/log_by_karyawan/yes?by='+by+'&bulan='+ bln +'&tahun='+ thn + '&group='+group+'&type='+type+'&username=ykdigital&password=ykdigital&mobile=1'+'&periode='+periode+'&tanggal='+tanggal).done(function(data){
		endloading();
		total_pengambilan = 0;
		if(myBarChart==null){
			resetCanvas();
		}else{
			resetCanvas();
		}
		
		var res = JSON.parse(data);
		$('#jenis_barang').html(res.item_value.length);


		var bg_color = [];
		$.each(res.item_value,function(key,val){
			total_pengambilan += val;
			bg_color.push(randomColor());
		});

		let indexMax = res.item_value.indexOf(Math.max.apply(null, res.item_value));

		var nan = parseInt(Math.max.apply(null, res.item_value));

		if(isNaN(nan)==false){
			$('#jumlah_terbanyak').html(num(Math.max.apply(null, res.item_value)).replace(',-','') + " " + res.item_sat[indexMax]);
		}

		$('#nama_barang_terbanyak').html(res.item_name[indexMax] );
		// $('#satuan').html();
		// alert(total_pengambilan);
		

		$('#group_name').html(res.group_name=='' ? 'All Group':res.group_name);


		$('#myChart').prop('height',res.item_value.length*70+100);

		if(res.item_value=='' || res.item_name =='' || res.item_total == ''){
			$('#nodata').fadeIn('fast');
		}else{
			$('#myChart').fadeIn('fast');
			
			
			try{
				data_item = {
		            
		            datasets: [{
		                label:'Jumlah ',
		                data: res.item_value,
		                backgroundColor: bg_color
		            }],
		            labels: res.item_name_null
		        }
			}catch(e){

			}

		    setCanvas(data_item,parseInt(res.item_total),res.item_name);


		}

		if(el!=null){
			el.attr('disabled',false);
		}

		var count = setInterval(function(){
			var i = parseInt($('#total_pengambilan').html());

			i += 35;
			$('#total_pengambilan').html(i);

			if(i > total_pengambilan){
				$('#total_pengambilan').html(num(total_pengambilan).replace(',-',''));
				clearInterval(count);
			}
		},1);

		
	}).fail(function(e){
		endloading();
		if(el!=null){
			el.attr('disabled',false);
		}
	});


	
}

function change(by='',bln='',thn='',group='',type='',el=null,gr_name=null){
	toURL = {
		user : by,
		bulan : bln,
		tahun : thn,
		group : group,
		type : type,
		type_sel : type,
		element:el
	};
	$('#group_name').html(el);
}


