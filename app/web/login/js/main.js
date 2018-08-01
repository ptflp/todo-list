
(function ($) {
    "use strict";


    /*==================================================================
    [ Focus input ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })
    })


    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }

        return check;
    });


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });
    function isset () {
        // +   original by: Kevin van Zonneveld
        // +   improved by: FremyCompany
        // +   improved by: Onno Marsman
        // *     example 1: isset( undefined, true);
        // *     returns 1: false
        // *     example 2: isset( 'Kevin van Zonneveld' );
        // *     returns 2: true

        var a=arguments, l=a.length, i=0;

        if (l===0) {
            throw new Error('Empty isset');
        }

        while (i!==l) {
            if (typeof(a[i])=='undefined' || a[i]===null) {
                return false;
            } else {
                i++;
            }
        }
        return true;
    }

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }

    /*==================================================================
    [ Show pass ]*/
    var showPass = 0;
    $('.btn-show-pass').on('click', function(){
        if(showPass == 0) {
            $(this).next('input').attr('type','text');
            $(this).addClass('active');
            showPass = 1;
        }
        else {
            $(this).next('input').attr('type','password');
            $(this).removeClass('active');
            showPass = 0;
        }

    });
    $('#login').on('submit',function(e) {
        e.preventDefault();
        var datastring = $(this).serialize();
        console.log(datastring);
        $.ajax({
            type: "POST",
            url: "/api/login",
            data: datastring,
            dataType: "json",
            success: function(data) {
                console.log(data);
                switch(data.success) {
                  case 0:
                    swal("Error!", data.error, "error");
                    break;
                  case 1:
                    swal({
                        title: 'Success!',
                        text: 'authorization successfull',
                        icon: 'success',
                        closeOnClickOutside: false,
                        button: false
                    });
                    setTimeout(function () {
                        window.location.replace("/");
                        window.location.href = "/";
                    },1500);
                    break;
                }
            },
            error: function() {
                alert('error handing here');
            }
        });
    });
    $('#register').on('submit',function(e) {
        e.preventDefault();
        var datastring = $(this).serialize();
        console.log(datastring);
        $.ajax({
            type: "POST",
            url: "/api/register",
            data: datastring,
            dataType: "json",
            success: function(data) {
                console.log(data.success);
                switch(data.success) {
                  case 0:
                    swal("Error!", data.error, "error");
                    break;
                  case 1:
                    swal({
                        title: 'Success!',
                        text: 'registration successfull',
                        icon: 'success',
                        closeOnClickOutside: false,
                        button: false
                    });
                    setTimeout(function () {
                        window.location.replace("/");
                        window.location.href = "/";
                    },1500);
                    break;
                }
            },
            error: function() {
                alert('error handing here');
            }
        });
    });


})(jQuery);