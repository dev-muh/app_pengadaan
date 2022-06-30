var URL = $('#BASE_URL').val();
var TYPE = $('#type_pos').val();
// alert('');

$(function(){

    reset_form('#add-customer');
    $('#add-customer').on('hidden.bs.modal', function (e) {
        $(this).find('input[name=username]').removeAttr('readonly').attr('required','');
        $(this).find('input[name=password]').removeAttr('readonly').attr('required','');

        $('.pr').css('display','none');
        $('.pr div input').prop('required',false);
        $('.pr div input').val('');
    });

    $('#tblCustomer').DataTable({pageLength:100});

});

function asdw() {
	alert();
}

function select_rule(x) {

    $(x).popover({
        html: true,
        content: function() {

            $('#pop_select_rule').find('.id_pem').val($(x).val());
            return $('#pop_select_rule').html();
        }
    });

    $(x).popover('toggle');
}

function v_form(x){

    $('.pr').css('display','none');
    $('.pr div input').prop('required',false);

    if($('#id_customer').val()==''){
        $('.pr div input').val('');
    }


    if(x=='Karyawan'){
        $('.v_username').css('display','inline');
            $('#u_username').prop('required',true);
        $('.v_nama').css('display','inline');
            $('#u_nama').prop('required',true);
        $('.v_email').css('display','inline');
            $('#u_email').prop('required',true);
        $('.v_group').css('display','inline');
            $('#u_group').prop('required',true).select2();
        $('.v_lantai').css('display','inline');
            $('#u_lantai').prop('required',true).select2();
        $('.v_active').css('display','inline');
            $('#u_active').prop('required',true);
        
        $('.v_department').css('display','inline').prop('required',true);
        $('.v_direktorat').css('display','inline').prop('required',true);
    }else{
        $('.v_username').css('display','inline');
            $('#u_username').prop('required',true);
        //$('.v_password').css('display','inline');
            //$('#u_password').prop({'required':true,'disabled':false}).val('12345678');
        //$('.v_password').css('display','inline').prop('required',true);
        $('.v_nama').css('display','inline');
        $('#u_nama').prop('required',true);

        $('.v_group').css('display','inline');
        $('#u_group').prop('required',true).select2();

        $('.v_active').css('display','inline');
        $('#u_active').prop('required',true);

        $('.v_jabatan').css('display','inline');
        $('#u_jabatan').prop('required',true);

        $('.v_lantai').css('display','inline');
        $('#u_lantai').prop('required',true).select2();
        //$('.v_jabatan').css('display','inline').prop('required',true);
        //$('.v_department').css('display','inline').prop('required',true);
    }
}

$('#frm-import-customer').submit(function(event) {
    /* Act on the event */
    event.preventDefault();
    const fd = new FormData();
    fd.append('file_csv', document.getElementById("file-csv").files[0]);

    startloading('Sedang mengirim data registrasi...');
    
    $.ajax({
        url: URL +   'customer/add_csv',
        type: 'POST',
        data: fd,
        contentType: "application/json",
        dataType: "json",
        contentType: false,
        processData: false
      })
    .always(function(result) {
        console.log(result)
        endloading();
        $.alert({
            title:'Done',
            content:result.responseText,
            columnClass: 'col-md-12',
            buttons:{
                ok:function(){
                    window.location.replace(URL + 'customer/view');
                }
            }
        });
    });
});

$('#frm-customer').on('submit',function(event){
    var errTxt = '';

    try{
        if($('#u_privilege').val()=='Karyawan'){
            $('.kr').each(function(index){
                if($(this).val()==null || $(this).val().length<1 || $(this).val()==''){
                    var e = $(this).parents().eq(1).find('label').text();
                    errTxt+= '<b>' + e + '</b> belum diisi.<br>';
                }
                
            });
        }else{
            // alert('');
            $('.n-kr').each(function(index){
                if($(this).val()==null || $(this).val().length<1 || $(this).val()==''){
                    var e = $(this).parents().eq(1).find('label').text();
                    errTxt+= '<b>' + e + '</b> belum diisi.<br>';
                }
                
            });
        }

        
    }catch(e){
        alert(e);
    }

    if(errTxt==''){
        if(form_validate($('#frm-customer'))){
            if(v_username($('#frm-customer').find('input[name=username]').val())==true){
                if($('#id_customer').val()==''){
                    startloading('Sedang mengirim data registrasi...');
                    $.post(URL +   'customer/add', $(this).serialize(), function(data, textStatus, xhr) {
                        endloading();
                        if(data=='email_false'){
                            $.alert({
                                title:'Warning',
                                content:'Hanya boleh menggunakan email dengan domain @tugu.com',
                                buttons:{
                                    ok:function(){
                                        
                                    }
                                }
                            });
                        }
                        if(data=='duplicate'){
                            $.alert({
                                title:'Warning',
                                content:'Duplicate Username/Email',
                                buttons:{
                                    ok:function(){

                                    }
                                }
                            });
                        }else{
                            if(data=='success'){
                                $.alert({
                                    content:'Sukses Input Data',
                                    buttons:{
                                        ok:function(){
                                            window.location.replace(URL+'customer/view');
                                        }
                                    }
                                });
                            }else {
    							if (data == 'error') {
    								$.alert({
    									content: 'Error Input Data',
    									buttons: {
    										ok: function () {
    											window.location.replace(URL + 'customer/view');
    										}
    									}
    								});
    							}
    						}

                        }
                    });
                }else{
                    startloading('Sedang mengirim data registrasi...');
                    $.post(URL +   'customer/edit', $(this).serialize(), function(data, textStatus, xhr) {
                        endloading();
                        if(textStatus=='success'){
                            $.alert({
                                content:'Sukses Update Data',
                                buttons:{
                                    ok:function(){
                                        window.location.replace(URL+'customer/view');
                                    }
                                }
                            });
                        }
                    });
                }
            }else{
                $.alert({
                    title:'Warning',
                    content:'Username hanya boleh menggunakan huruf dan angka',
                });
                endloading();
            }
        }
    }else{
        $.alert(errTxt);
    }
    
    event.preventDefault();
});

function del(id){

    $.confirm({
        title: 'Confirmation!',
        content: 'Delete data?',
        buttons: {
            confirm: function () {
                startloading('Menghapus user...');
                $.ajax({
                    url: URL + 'customer/delete',
                    type : "post",

                    data:{id:id},
                    error: function(result){
                        endloading();
                        $.alert('Error saat menghapus user.');
                    },
                    success: function(result) {
                        endloading();
                        if(result=='success'){
                            $.alert({
                                title:'Sukses',
                                content:'Sukses menghapus Data',
                                buttons:{
                                    ok:function(){
                                        window.location = URL + 'customer/view';
                                    }
                                }
                            })
                        }
                    }
                });
            },
            cancel: function () {
            }
        }
    });
}

function deactivate(id){

    $.confirm({
        title: 'Confirmation!',
        content: 'Delete data?',
        buttons: {
            confirm: function () {
                startloading('Menghapus user...');
                $.ajax({
                    url: URL + 'customer/deactive',
                    type : "post",

                    data:{id:id},
                    error: function(result){
                        endloading();
                        $.alert('Error saat menonaktifkan User.');
                    },
                    success: function(result) {
                        endloading();
                        if(result=='success'){
                            $.alert({
                                title:'Sukses',
                                content:'Sukses menonaktifkan User.',
                                buttons:{
                                    ok:function(){
                                        window.location = URL + 'customer/view';
                                    }
                                }
                            })
                        }
                    }
                });
            },
            cancel: function () {
            }
        }
    });
}
function activate(id){
    $.confirm({
        title: 'Confirmation!',
        content: 'Activate User?',
        buttons: {
            confirm: function () {
                startloading('Mengaktivasi user...');
                $.ajax({
                    url: URL + 'customer/activate',
                    type : "post",
                    data:{id:id},
                    error: function(result){
                        endloading();
                    },
                    success: function(result) {
                        endloading();
                        if(result=='success'){
                            $.alert({
                                title:'Sukses',
                                content:'Sukses megaktivasi User',
                                buttons:{
                                    ok:function(){
                                        window.location = URL + 'customer/view';
                                    }
                                }
                            })
                        }
                    }
                });
            },
            cancel: function () {
            }
        }
    });
}

function reset(id){
    $.confirm({
        title: '',
        content: 'Reset Password User?',
        buttons: {
            confirm: {
                text:'RESET!',
                btnClass:'bg-red',
                action: function () {
                            startloading('Mengirim data...');
                            $.post(URL+'user/reset_password',{id:id}).done(function(data){
                                endloading();
                                var res = JSON.parse(data);
                                if(res.status==1){
                                    $.alert({
                                        title:'',
                                        content:res.message,
                                        buttons:{
                                            ok:function(){
                                                window.location = URL + 'customer/view';
                                            }
                                        }
                                    })
                                }else{
                                    $.alert(res.message);
                                }
                            }).fail(function(e){
                                endloading();
                                $.alert('Terjadi kesalahan. Error-Code:RES-USR(5172638912)');
                            });
                        }
            },
            cancel: function () {
            }
        }
    });
}

function edit(el,id){
    var v = el.val().split('|');
    $('#id_customer').val(v[0]);
    $('#u_nama').val(v[2]);
    $('#u_username').val(v[1]);

    $('#u_username').removeAttr('required').attr('readonly','');
    $('#u_password').removeAttr('required').attr({
        placeholder: 'Only Change Password',
        readonly: ''
    });
    $('#u_privilege').val(v[4]);
    $('#u_no_peg').val(v[5]);
    $('#u_department').val(v[6]);
    $('#u_jabatan').val(v[7]);
    $('#u_email').val(v[8]);
    $('#u_group').val(v[9]).select2({width:"100%"});
    $('#u_lantai').val(v[10]).select2({width:"100%"});
    $('#u_active').val(v[11]);
    $('#u_direktorat').val(v[12]);


    if(v[4]=='Karyawan'){
        $('.v_username').css('display','inline');
            $('#u_username').prop('required',true);
        $('.v_nama').css('display','inline');
            $('#u_nama').prop('required',true);
        $('.v_email').css('display','inline');
            $('#u_email').prop('required',true);
        $('.v_group').css('display','inline');
            $('#u_group').prop('required',true);
        $('.v_lantai').css('display','inline');
            $('#u_lantai').prop('required',true);
        $('.v_active').css('display','inline');
            $('#u_active').prop('required',true);
        $('.v_department').css('display','inline').prop('required',true);
        $('.v_direktorat').css('display','inline').prop('required',true);
    }else{
        $('.v_username').css('display','inline');
            $('#u_username').prop('required',true);
        //$('.v_password').css('display','inline');
            //$('#u_password').prop({'required':true,'disabled':false}).val('12345678');
        //$('.v_password').css('display','inline').prop('required',true);
        $('.v_nama').css('display','inline');
        $('#u_nama').prop('required',true);
        $('.v_group').css('display','inline');
        $('#u_group').prop('required',true);
        $('.v_active').css('display','inline');
        $('#u_active').prop('required',true);

        $('.v_jabatan').css('display','inline');
        $('#u_jabatan').prop('required',true);

        $('.v_lantai').css('display','inline');
        $('#u_lantai').prop('required',true).prop('selectedIndex',0).select2({width:'100%'});
        //$('.v_jabatan').css('display','inline').prop('required',true);
        //$('.v_department').css('display','inline').prop('required',true);
    }

    $('#add-customer').modal('show');
}
