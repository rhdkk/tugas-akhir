<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbAkun').dataTable({
			"ajax": "<?php echo site_url('akun/ajax_list'); ?>",
			"columns": [
				null,null,null,null,{"width":"80px"}
			], 
			"order": [[0,"asc"],[1,"asc"]],
			"columnDefs": [
				{ "targets": [-1],"orderable": false },
				{ className:"text-center","targets":[-1] }				
			]
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},null]
		});		
		
		$('.ext-select').select2();
		$('.select2-container').css("width","100%");
		
		$("#jenis").change(function() {
			var hak = this.value;
			if (hak!=4) {				
				$('#nProdi').val(0);
				$('.divider').hide();
				$('.copyA').hide();
			} else {
				$('#nProdi').val($('.copyA').length);
				$('.divider').show();
				$('.copyA').show();
			}
		});
		
		$(".btn-tambah").click(function() {
			$('#fAkun')[0].reset();				
			var caption = $(this).attr("id"); 
			$('#modal_form').modal('show');			
			$('.modal-title').text('Tambah Data User Sistem');
			$('#btSave').text('Simpan');
		});	
		
		$("#btSave").click(function(){        
      $("#fAkun").submit();
    });
		
		$("#fAkun").submit(function(event) {
			$(".error-form-akun").empty().hide();			
			$(".error-form").empty().hide();			
			
			$('.prodi').each(function() {
				$(this).rules("add", 
				{
					required: true,
					messages: {required: "Data tidak boleh kosong"}
				});
			});			
			event.preventDefault();
		});
		
		$('#fAkun').validate({
			onfocusout: false,
			onkeyup :false,
			onclick : false,
			rules: {
				nama: { required:true, maxlength:50 },				
				user: { required:true, maxlength:50 }
			},						
			highlight: function(element) {
				if($(element).hasClass('select2') && $(element).next('.select2-container').length) {
					$(element).closest('.select2').removeClass('has-success').addClass('has-error')
				}				
        else 
					$(element).closest('.form-group').removeClass('has-success').addClass('has-error');                
			},
			unhighlight: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');				
			},
			errorElement: 'span',
			errorPlacement: function(error, element) {	
				no = parseInt(element.parents(".form-group").find(".noA").val());
				error.appendTo("#errAkun"+no);				
				$("#errAkun"+no).slideDown('fast').removeClass('hide');				
			},
			submitHandler: function() {
				save_akun();
			}			
		});
		
		$( "#fHapus" ).submit(function(event) {
			$(".gone").remove();
			del_data();
			event.preventDefault();
		});
		
		$("body").on("click",".add-prodi",function(){ 
			$(".ext-select").select2("destroy");
			
			var num = $(".copyA").length,		
			newType = $(".copyA").first().clone().addClass("newAdded").fadeIn("slow");						
			newType.appendTo("#kwA");			
			newType.attr('id', 'copyA' + num);			
			newType.find("#lbAkun0").attr('id','lbAkun'+num).text("");;
			newType.find(".noA").val(num).attr('id','noA'+num).attr('name','noA'+num);;
			newType.find("#edProdi0").attr('id','edProdi'+num).attr('name','edProdi'+num).val(null);
			newType.find(".ext-select").attr('id','prodi'+num).attr('name','prodi'+num);			
			newType.find("#errAkun0").attr('id','errAkun'+num).attr('name','errAkun'+num).hide();
			$('.remove-prodi').prop("disabled",false);
			
			for (var i=0; i<num; ++i) {
				if ($("#idProdi"+i).val()!='')
					$("#idProdi"+i).parents(".form-group").find(".remove-prodi").prop("disabled",true);
				else $("#idProdi"+i).parents(".form-group").find(".remove-prodi").prop("disabled",false);
			}
			
			$(".ext-select").select2();			
			$(".newAdded").show();			
			$('#nProdi').val(num + 1);
		});
		
		$("body").on("click",".remove-prodi",function(){ 
			var num = $(".copyA").length,			
			newNum = num - 1,	no, idx, idProdi;
			$('#nProdi').val(newNum);			
			
			no = parseInt($(this).parents(".form-group").find(".noA").val()) + 1;
			idProdi = $("#idProdi"+(no-1)).val();
			$(this).parents(".form-group").slideUp('fast', function() {				
				$(this).remove();				
			});
			if (no<num) {			
				for (var i=no; i<num; ++i) {
					idx = i - 1;					
					$("#copyA"+i).attr('id','copyA'+idx);
					
					if (i===1) $("#lbAkun"+i).attr('id','lbAkun'+idx).text("Akses Prodi");					
					else $("#lbAkun"+i).attr('id','lbAkun'+idx);					
					$("#noA"+i).attr('id','noA'+idx).attr('name','noA'+idx).val(idx);
					$("#edProdi"+i).attr('id','edProdi'+idx).attr('name','edProdi'+idx);
					$("#prodi"+i).attr('id','prodi'+idx).attr('name','prodi'+idx);			
					$("#errAkun"+i).attr('id','errAkun'+idx).attr('name','errAkun'+idx);
				}			
			}
			
			if (newNum===1) {
				$('.remove-prodi').prop("disabled",true);				
			}							
		});
		
		$("body").on("click",".del-list",function(){ 
			$(this).parents(".list-group-item").addClass('gone');
		})

		$('#modal_form').on('hidden.bs.modal', function () {		
			$('#fAkun').validate().resetForm();
			$('.divider').show();
			$('.copyA').show();			
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');	
			$('.newAdded').remove();			
			$('.alert-danger').addClass('hide');				
		});
		
		function save_akun() 
		{
			var url,state,msg;
			state = $("#btSave").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('akun/add_data')?>"; }
			else
			{ url = "<?php echo site_url('akun/update_data')?>"; }
			$.ajax({
				url : url,
				type: "POST",
				data: $('#fAkun').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					if (data.status) {
						$('#modal_form').modal('hide');						
						if (state=='Simpan') { $.toast({ text:'Data Akun sudah disimpan' }); } 
						else {
							if (data.save)
							$.toast({ text:'Data Akun sudah diubah' }); 
						}
						
						reload_table();						
					}				
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		function del_data() {
			var url, ops, capt;
			ops = $("#ops").val();
			if (ops==='akses') {
				url = "<?php echo site_url('akun/delete_akses')?>"; 
				capt = "Akun";
			}
			else {
				url = "<?php echo site_url('akun/delete_data')?>/" + ops; 
				capt = "Akses Program Studi";
			}
			
			$.ajax({
			url : url,
			type: "POST",
			data: $('#fHapus').serialize(),
			dataType: "JSON",
			success: function(data)
			{
				$('#modal_confirm').modal('hide');
				$.toast({ text:"Data <b>" + capt + "</b> sudah dihapus" });
				reload_table();				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert('Error Hapus'); }
		});
		}
		
		function reload_table() {
      tabel.api().ajax.reload(null,false); 
    }
	});
		
	function edit_akun(id)
	{
		$('#fAkun')[0].reset();	

		$.ajax({
			url : "<?php echo site_url('akun/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));					
				$('#idAkun').val(data.data[0].id_user);
				$('#edUser').val(data.data[0].username);
				$('#user').val(data.data[0].username);
				$('#edNama').val(data.data[0].nm_user);
				$('#nama').val(data.data[0].nm_user);
				$('#edJenis').val(data.data[0].hak);
				$('#jenis').val(data.data[0].hak);
				if (data.data[0].hak==4) {
					$('#edNP').val(data.data.length);
					$('#nProdi').val(data.data.length);
					$('#edProdi0').val(data.data[0].id_ps);
					$('#prodi0').val(data.data[0].id_ps);
					$('#prodi0').trigger('change');					
					$('.remove-prodi').prop("disabled",true);				
				
					if (data.data.length>1) {
						for (var i=1; i<data.data.length; ++i) {	
							$(".ext-select").select2("destroy");
							newType = $(".copyA").first().clone().addClass("newAdded");						
							newType.appendTo("#kwA");			
							newType.attr('id','copyA'+i);	
							newType.find("#lbAkun0").attr('id','lbAkun'+i).text("");
							newType.find(".noA").val(i).attr('id','noA'+i).attr('name','noA'+i).val(i);
							newType.find("#edProdi0").attr('id','edProdi'+i).attr('name','edProdi'+i);
							newType.find(".ext-select").attr('id','prodi'+i).attr('name','prodi'+i);																					
							$('#edProdi'+i).val(data.data[i].id_ps);
							$('#prodi'+i).val(data.data[i].id_ps);
							$('#prodi'+i).trigger('change');
							$(".newAdded").show();					
							$(".ext-select").select2();			
							$('.select2-container').css("width","100%");
						}						
					}
				} else {
					$('#nProdi').val(0);
					$('.divider').hide();
					$('.copyA').hide();
				}
				
				$('#btSave').text('Update');
				$('.modal-title').text('Edit Data User Sistem'); 
				$('#modal_form').modal('show');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				//alert('Error get data from ajax');
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function set_akses(id)
	{
		$('#fAkses')[0].reset();
		$('.ext-select').val(null).trigger('change');		
		$.ajax({
			url : "<?php echo site_url('kelas/ajax_jadwal/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));		
				$('#idKelasP').val(data.data[0].id_kls);
				$('#edMhs').val(data.data[0].n_mhs);
				$('#mhs').val(data.data[0].n_mhs);
				$('#nPlot').val(data.data.length);				
				$('#idPlot0').val(data.data[0].id_plot);
				$('#edHari0').val(data.data[0].hari);
				$('#edSesi0').val(data.data[0].id_sesi);
				$('#edRuang0').val(data.data[0].id_ruang);
				$('#hari0').val(data.data[0].hari);
				$('#sesi0').val(data.data[0].id_sesi);
				$('#ruang0').val(data.data[0].id_ruang);
				$('#ruang0').trigger('change');				
				$('.remove-jadwal').prop("disabled",true);				
				for (var i=1; i<data.data.length; ++i) {					
					$(".ext-select").select2("destroy");
					newType = $(".copyP").first().clone().addClass("newAdded");						
					newType.appendTo("#kwP");			
					newType.attr('id','copyP'+i);			
					newType.find("#lbJadwal0").attr('id','lbJadwal'+i).text("");
					newType.find("#idPlot0").attr('id','idPlot'+i).attr('name','idPlot'+i).val(data.data[i].id_plot);						
					newType.find(".noP").val(i).attr('id','noP'+i).attr('name','noP'+i).val(i);						
					newType.find("#edHari0").attr('id','edHari'+i).attr('name','edHari'+i).val(data.data[i].hari);
					newType.find("#edSesi0").attr('id','edSesi'+i).attr('name','edSesi'+i).val(data.data[i].id_sesi);
					newType.find("#edRuang0").attr('id','edRuang'+i).attr('name','edRuang'+i).val(data.data[i].id_ruang);
					newType.find("#hari0").attr('id','hari'+i).attr('name','hari'+i).val(data.data[i].hari);
					newType.find("#sesi0").attr('id','sesi'+i).attr('name','sesi'+i).val(data.data[i].id_sesi);
					newType.find("#errKelas0").attr('id','errKelas'+i).attr('name','errKelas'+i);
					newType.find(".ext-select").attr('id','ruang'+i).attr('name','ruang'+i);																					
					$('#ruang'+i).val(data.data[i].id_ruang);
					$('#ruang'+i).trigger('change');				
					$(".newAdded").show();					
					$(".ext-select").select2();			
					$(".ext-select").select2();			
					$('.select2-container').css("width","100%");
				} 
				
				$('#fTawar').hide();			
				$('#btTawar').hide();
				$('#fDosen').hide();			
				$('#btDosen').hide();			
				$('#fKelas').show();
				$('#btKelas').show();			
				$('.modal-title').text(data.data[0].nm_mk+' [ '+data.data[0].nm_kls+' ]'); 
				$('#modal_form').modal('show');				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				//alert('Error get data from ajax');
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function hapus_akses(id)
	{
		$('#idHapus').val(id);
		$('#ops').val('akses');		
		$.ajax({
			url : "<?php echo site_url('akun/ajax_akses/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.modal-title').text('Hapus Akses Prodi');
				$('#modal_confirm').modal('show');
				$('.confirm-msg').empty();
				$('.confirm-msg').append("Yakin akan menghapus Prodi <b class='text-danger'>" +data.data.nm_ps+ "</b> dari User  <b>" +data.data.nm_user+ "</b>?");
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});
	}
	
	function hapus_akun(id)
	{
		$('#idHapus').val(id);	
		$('#ops').val('akun');		
		$.ajax({
			url : "<?php echo site_url('akun/ajax_akun/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.modal-title').text('Hapus Data Kelas');
				$('#modal_confirm').modal('show');
				$('.confirm-msg').empty();
				$('.confirm-msg').append("Yakin akan menghapus username <b class='text-danger'>" +data.data.username+ " </b> atas nama <b>" +data.data.nm_user+ "</b>?");				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});
	}
</script>	
</head>