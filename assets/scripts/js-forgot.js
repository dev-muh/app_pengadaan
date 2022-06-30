function submit_forgot(){
    // startloading('Mohon tunggu...');
    // var username = $('input[name=username]').val();
    // var email = $('input[name=email]').val();


    // var t_forgot = setTimeout(function(){
    //     endloading();
    //     forgot.abort();
    // },15000);

    // var forgot = $.ajax({
    //     type:'POST',
    //     url: URL + 'user/submit_forget',
    //     data:{username:username,email:email},
    //     beforeSend:function(){
            
    //         t_forgot;
    //     },
    //     success:function(data){
    //         var res = JSON.parse(data);
            

    //         if(res.status=='1'){
    //             $.alert(res.message);
    //             endloading();
    //             clearTimeout(t_forgot);
    //             forgot.abort();
    //         }else{
    //             $.alert(res.message);
    //             endloading();
    //             clearTimeout(t_forgot);
    //         }
    //     },
    //     error:function(jqXHR,exception){
    //         endloading();
    //         clearTimeout(t_forgot);

    //         var msg = '';
    //         if (jqXHR.status === 0) {
    //             msg = 'Tidak terhubung.\n Cek koneksi anda.';
    //         } else if (jqXHR.status == 404) {
    //             msg = 'Halaman tidak ditemukan. [404]';
    //         } else if (jqXHR.status == 500) {
    //             msg = 'Internal Server Error [500].';
    //         } else if (exception === 'parsererror') {
    //             msg = 'Requested JSON parse failed.';
    //         } else if (exception === 'timeout') {
    //             msg = 'Time out error.';
    //         } else if (exception === 'abort') {
    //             msg = 'Ajax request aborted.';
    //         } else {
    //             msg = 'Uncaught Error.\n' + jqXHR.responseText;
    //         }

    //         forgot.abort();
    //         $.alert({
    //             title:'Error!',
    //             content:msg
    //         });
    //     }
    // });
    
    startloading('Mohon tunggu...');

    var username = $('input[name=username]').val();
    var email = $('input[name=email]').val();

    var xhr = new XMLHttpRequest();
    xhr.open('POST', URL + 'user/submit_forget', true);
    xhr.timeout = 15000;

    xhr.onload = function (data) {
      // Request finished. Do processing here.
        var res = JSON.parse(data.currentTarget.response);

        if(res.status=='1'){
            endloading();
            $.confirm({
                title:'Sukses!',
                content:res.message + '\n' + 'Password baru sudah dikirimkan ke <b>' + email + '</b>',
                buttons:{
                    ok:{
                        text:'OK',
                        action:function(){
                            window.location = URL;
                        }
                    }
                }
            });
            
            //window.location = URL;
        }else{
            $.alert(res.message);
            endloading();
        }
        //console.log(data);
    };

    xhr.ontimeout = function (jqXHR,exception) {
      // XMLHttpRequest timed out. Do something here.
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Tidak terhubung.\n Cek koneksi anda.';
        } else if (jqXHR.currentTarget.status == 404) {
            msg = 'Halaman tidak ditemukan. [404]';
        } else if (jqXHR.currentTarget.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (jqXHR.type === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (jqXHR.type === 'timeout') {
            msg = 'Time out error.';
        } else if (jqXHR.type === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }

        //forgot.abort();
        $.alert({
            title:'Error!',
            content:msg
        });
        endloading();
        //window.location = URL;
        console.log(jqXHR);
    };

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("username="+ username +"&email="+email);
}