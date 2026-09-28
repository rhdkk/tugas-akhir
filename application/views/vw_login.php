<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "Login - SATRIA";	
$var['icon'] = "ruang.png";		
$this->load->view('template/header',$var);
$this->load->view('head_login');
?> 
<body>
<div class="login-container">
    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="logo-icon">
                <i class="glyphicon glyphicon-education"></i>
            </div>
            <h1 style="font-family:'Inter'; font-weight:800; font-size:4em; letter-spacing:-1px">SATRIA</h1>
            <p>Sistem Administrasi dan Monitoring Tugas Akhir</p>
        </div>

        <!-- Alert error -->
        <div id="login-alert" class="alert alert-danger"></div>

        <!-- Form Login -->
        <form id="fLogin" method="POST">
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="glyphicon glyphicon-user"></i>
                    <input type="text" class="form-control" id="user" name="user" placeholder="Username" autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="glyphicon glyphicon-lock"></i>
                    <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
                </div>
            </div>

            <button type="submit" id="btSave" class="btn-login">LOGIN</button>
        </form>
    </div>
</div>

<!-- Loading Modal -->
<div id="load-modal">
    <div class="loading-content">
        <img src="http://localhost/thesis//assets/img/loading.gif" alt="Loading..."/>
        <p>Memverifikasi...</p>
    </div>
</div>
</body>