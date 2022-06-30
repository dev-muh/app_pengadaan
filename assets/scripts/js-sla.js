$(function () {
    $('#datetimepicker6').datetimepicker({format:'YYYY-MM-DD'});
    $('#datetimepicker7').datetimepicker({
        format:'YYYY-MM-DD',
        useCurrent: false //Important! See issue #1075
    });
    $("#datetimepicker6").on("dp.change", function (e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker7").on("dp.change", function (e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });


});


function filter_range(x){
    for(var i=0; i<x; i++){
        $('#eact').find('option').eq(i).prop('disabled',true);
        let selEACT = $('#eact').prop('selectedIndex');
        if($('#eact').find('option').eq(selEACT).prop('disabled')==true){
            $('#eact').prop('selectedIndex',x);
        }
        
    }
    for(var i=x; i<=5; i++){
        $('#eact').find('option').eq(i).prop('disabled',false);
        // $('#eact').prop('selectedIndex',x);
    }
}

function exec_act(x){
    var user_type = x.split('|')[1];
    if(user_type=='Admin Gudang'){
        for(var i=1; i<=5; i++){
            $('#sact').find('option').eq(i).hide();
        }
        for(var i=2; i<=5; i++){
            $('#eact').find('option').eq(i).hide();
            $('#eact').prop('selectedIndex',1);
        }
    }

    if(user_type=='Kurir'){
        for(var i=1; i<=5; i++){
            $('#sact').find('option').eq(i).show();
        }
        for(var i=2; i<=5; i++){
            $('#eact').find('option').eq(i).show();
        }
    }
}

function submit(x){
    if(!x.hasClass('disabled')){
    
        let sdate = $('#startper').val();
        let edate = $('#endper').val();
        let sact = $('#sact').val();
        let eact = $('#eact').val();

        let opt = {
            id_user:$('#id_user').val().split('|')[0],
            startACT:sact,
            endACT:eact,
            startper:sdate,
            endper:edate +' 23:59:59'
        }
        

        if(sdate=='' || edate==''){
            $.alert('Tanggal tidak boleh kosong.');
        }else{
            x.addClass('disabled');
            startloading('Mohon tunggu...');
            // $('#loading_ctrl').show(function(){
            //     $(this).addClass('fa-spin');
            // });

            $.post(URL+'report/tb_sla',opt).done(function(data){
                x.removeClass('disabled');
                endloading();
                // $('#loading_ctrl').hide(function(){
                //     $(this).removeClass('fa-spin');
                // });
                $('#tb_sla').html(data);
            }).fail(function(){
                endloading();
                x.removeClass('disabled');
                // $('#loading_ctrl').hide(function(){
                //     $(this).removeClass('fa-spin');
                // });
                $('#tb_sla').html("Error Saat mengambil data.");
            });
        }

    }

}

function export_pdf_report_sla(x) {

     if(!x.hasClass('disabled')){
    
        let sdate = $('#startper').val();
        let edate = $('#endper').val();
        let sact = $('#sact').val();
        let eact = $('#eact').val();


        let opt = 'id_user=' + $('#id_user').val().split('|')[0];
        opt += ('&name=' + $("#id_user option:selected").html());
        opt += ('&startACT=' + sact);
        opt += ('&endACT=' + eact);
        opt += ('&startper=' + sdate);
        opt += ('&endper=' + edate);
        

        if(sdate=='' || edate==''){
            $.alert('Tanggal tidak boleh kosong.');
        }else{
            window.location.href = URL + 'report/export_pdf_report_sla?' + opt;
        }

    }
}

function export_excel_report_sla(x) {
    if(!x.hasClass('disabled')){
    
        let sdate = $('#startper').val();
        let edate = $('#endper').val();
        let sact = $('#sact').val();
        let eact = $('#eact').val();


        let opt = 'id_user=' + $('#id_user').val().split('|')[0];
        opt += ('&name=' + $("#id_user option:selected").html());
        opt += ('&startACT=' + sact);
        opt += ('&endACT=' + eact);
        opt += ('&startper=' + sdate);
        opt += ('&endper=' + edate);
        

        if(sdate=='' || edate==''){
            $.alert('Tanggal tidak boleh kosong.');
        }else{
            window.location.href = URL + 'report/export_excel_report_sla?' + opt;
        }

    }
}