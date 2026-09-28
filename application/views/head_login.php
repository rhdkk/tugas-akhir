<script>
    $(document).ready(function(){   
            var delay = 1500;
            
            // Override default error element styling
            $('#fLogin').validate({ 
                rules: {
                    user: {
                        required: true,
                        minlength: 3
                    },
                    pass: {
                        required: true,
                        minlength: 4
                    }
                },
                messages: {
                    user: {
                        required: "Username harus diisi",
                        minlength: "Username minimal 3 karakter"
                    },
                    pass: {
                        required: "Password harus diisi",
                        minlength: "Password minimal 4 karakter"
                    }
                },
                errorElement: "label",
                errorClass: "error",
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorPlacement: function(error, element) {
                    // Tempatkan error setelah input-wrapper
                    error.insertAfter(element.closest('.input-wrapper'));
                },
                submitHandler: function(form) {
                    verify();
                    return false;
                }           
            });
            
            function verify() 
            {
                $.ajax({                
                    url : "<?php echo site_url('login/verifyLogin'); ?>",
                    type: "POST",
                    data: new FormData($("#fLogin")[0]),
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $("#load-modal").fadeIn(200);
                        $("#btSave").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                    },
                    complete: function () {
                        $("#load-modal").fadeOut(200);
                        $("#btSave").prop('disabled', false).html('LOGIN');
                    },
                    success: function(data)
                    {
                        if (data[0].ada == 0) {
                            $('#login-alert').text('Username / Password salah!');
                            $('#login-alert').fadeIn(200);
                            setTimeout(function(){
                                $("#login-alert").fadeOut(500);
                            }, delay);
                            
                            // Reset form validation highlight
                            $('#user, #pass').closest('.form-group').removeClass('has-error');
                        }
                        else {
                            window.location.href = "http://localhost/thesis/home";                      
                        }                               
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        $('#login-alert').text('Terjadi kesalahan koneksi. Silakan coba lagi.');
                        $('#login-alert').fadeIn(200);
                        setTimeout(function(){
                            $("#login-alert").fadeOut(500);
                        }, delay);
                    }
                });                 
            };
            
            // Reset error styling saat user mulai mengetik
            $('#user, #pass').on('keyup focus', function() {
                $(this).closest('.form-group').removeClass('has-error');
                $('#login-alert').fadeOut(300);
            });
        });
</script>	
<style>
       * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #FFD700 0%, #FFC107 40%, #003153 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Background pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="rgba(255,255,255,0.03)" d="M10,10 L20,10 L15,20 Z M30,30 L40,30 L35,40 Z M50,50 L60,50 L55,60 Z M70,70 L80,70 L75,80 Z"/><circle cx="80" cy="20" r="5" fill="rgba(255,255,255,0.03)"/><circle cx="20" cy="80" r="8" fill="rgba(255,255,255,0.03)"/></svg>');
            background-repeat: repeat;
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            background: white;
            border-radius: 32px;
            padding: 48px 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease;
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #003153 0%, #004a7a 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px -5px rgba(0, 49, 83, 0.2);
        }

        .logo-icon i {
            font-size: 36px;
            color: #FFD700;
        }

        .login-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #003153;
            margin-bottom: 8px;
            letter-spacing: 2px;
        }

        .login-header p {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
            letter-spacing: 0.5px;
        }

        /* Form Group - Kunci perbaikan tumpang tindih */
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
            z-index: 2;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            font-size: 15px;
            border: 1.5px solid #e0e0e0;
            border-radius: 14px;
            background: #f8f9fa;
            transition: all 0.2s ease;
            font-family: inherit;
            display: block;
        }

        .form-control:focus {
            outline: none;
            border-color: #FFD700;
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
        }

        /* STYLING VALIDASI - TIDAK TUMPANG TINDIH */
        .form-group.has-error .form-control {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .form-group.has-error .input-wrapper i {
            color: #dc3545;
        }

        /* Pesan error diletakkan di bawah input dengan margin yang cukup */
        label.error, .help-block {
            color: #dc3545;
            font-size: 12px;
            margin-top: 6px;
            margin-bottom: 0;
            display: block;
            font-weight: normal;
            padding-left: 12px;
        }

        /* Button Login */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #003153 0%, #004a7a 100%);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 49, 83, 0.3);
            background: linear-gradient(135deg, #004a7a 0%, #005a8a 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Alert */
        .alert {
            border-radius: 14px;
            border: none;
            background: #f8d7da;
            color: #721c24;
            font-size: 13px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: none;
        }

        /* Loading modal */
        #load-modal {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: none;
        }

        .loading-content {
            background: white;
            border-radius: 20px;
            padding: 25px 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            text-align: center;
        }

        .loading-content img {
            width: 50px;
            height: 50px;
        }

        .loading-content p {
            margin-top: 10px;
            color: #666;
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 520px) {
            .login-card {
                padding: 32px 24px;
            }
            
            .login-header h1 {
                font-size: 28px;
            }
        }
</style>
</head>
<body>