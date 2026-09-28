<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbDosen').dataTable({
			"ajax": "<?php echo site_url('dosen/ajax_list'); ?>",
			"columns": [
				null,{"width":"30px"},{"width":"30px"},{"width":"30px"},{"width":"30px"},{"width":"30px"},{"width":"30px"},{"width":"60px"},{"width":"30px"},{"width":"30px"},{"width":"60px"}
			], 
			"order": [[ 0, "asc" ]],
			"columnDefs": [
				{ "targets": [-1],"orderable": false },
				{ className:"text-center","targets":[3,5,6,8,-1] }				
			],
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},,{type:"text"},{type:"text"},{type:"text"},null]
		});
		
		$(".btn-tambah").click(function() {
			$('#fDosen')[0].reset();	
			var caption = $(this).attr("id"); 
			$('#modal_form').modal('show');			
			$('.modal-title').text('Tambah Data Dosen');
			$("#secAsal").hide();
			$('#btSave').text('Simpan');
		});	
		
		$("#jenis").change(function() {			
			var jns = $("#jenis").val();
			if (jns>3) {
				$("#secHB").hide();
				$("#secAsal").show();
				if (jns>4) {
					$("#txAsal").show();
					$("#cbAsal").hide();
				}
				else {
					$("#txAsal").hide();
					$("#cbAsal").show();
				}
			}
			else {
				$("#secHB").show();
				$("#secAsal").hide();				
			}
		});
		
		$('#fDosen').validate({ 
			rules: {
				nama: { required:true, maxlength:50 },
				no: { required:true, number:true },
				txAsal: { required:true, maxlength:50 }
			},
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');    								
			},
			unhighlight: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');								
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				if (element.hasClass('ext-select')) {
        	error.insertAfter('.select2-container');
				} else {
					error.insertAfter(element);					
				}
			},
			submitHandler: function() {
				save_data();
			}			
		});
		
		$( "#fHapus" ).submit(function(event) {
			delete_data();
			event.preventDefault();
		});

		$('#modal_form').on('hidden.bs.modal', function () {		
			$('#fDosen').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');	
			$("#errNo").hide();			
		});
		
		$("#nama").keyup(function () {  
			$(this).val($(this).val().toUpperCase());  
		}); 
		
		function save_data(){
			var url,state,msg;
			state = $("#btSave").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('dosen/add_data')?>"; }
      else
      { url = "<?php echo site_url('dosen/update_data')?>"; }

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fDosen').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						if (data.ada) {
							$("#errNo").text('Sudah ada data Dosen dengan No Induk yang sama');
							if ($("#errNo").is(":hidden")) $("#errNo").slideDown('fast').removeClass('hide');
							$("#no").closest('.form-group').removeClass('has-success').addClass('has-error');							
						} else {
							$('#modal_form').modal('hide');
							if (data.save) {
								if (state=='Simpan') { msg = 'Data Dosen sudah disimpan'; } 
								else { msg = 'Data Dosen sudah diubah'; }
								$.toast({ text:msg });
								reload_table();
							}
						}
					} 
					else
					{
						$('#alert-dosen').show();
						setTimeout(function(){
							$("#alert-dosen").hide('hide');
						}, 2000);						
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					if (state=='Simpan') { msg = "Error Simpan"; }
					else { msg = "Error Update"; }
					$.toast({ text : msg });
				}
			});
		}
		
		function delete_data(){
			$.ajax({
				url : "<?php echo site_url('dosen/delete_data')?>",
				type: "POST",
				data: $('#fHapus').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');
					$.toast({ text:"Data Dosen sudah dihapus" });
					reload_table();				
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ alert('Error Hapus'); }
			});
		}
		
		function reload_table(){
      tabel.api().ajax.reload(null,false); 
    }
	});
	
	function edit_dosen(id) {
		$('#fDosen')[0].reset();	

		$.ajax({
			url : "<?php echo site_url('dosen/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));			
				var jns = data.data.stat_dosen; 
				var act = "";
				if (jns>2) {
					$("#secHB").hide();
					$("#secAsal").show();
					if (jns>4) {
						$("#txAsal").show();
						$("#cbAsal").hide();
						act = "#txAsal";
					}
					else {
						$("#txAsal").hide();
						$("#cbAsal").show();
						act = "#cbAsal";
					}
				}
				else {
					$("#secHB").show();
					$("#secAsal").hide();				
				}
				$('#idDosen').val(data.data.id_dosen);
				$('#nama').val(data.data.nm_dosen);
				$('#prodi').val(data.data.id_ps);
				$('#jenis').val(data.data.stat_dosen);				
				$('#gol').val(data.data.id_pangkat);				
				$('#jab').val(data.data.jabfung);				
				$('#no').val(data.data.no_dosen);				
				$('#nidn').val(data.data.nidn);				
				$('#gelar1').val(data.data.gelar1);				
				$('#gelar2').val(data.data.gelar2);				
				$('#jk').val(data.data.jk);				
				$(act).val(data.data.asal_dosen);				
				$('#pend').val(data.data.pend_akhir);				;				
				
				$('#btSave').text('Update');
				$('.modal-title').text('Edit Data Dosen'); 
				$('#modal_form').modal('show');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function hapus_dosen(id)
	{
		$('#idHapus').val(id);
		$.ajax({
			url : "<?php echo site_url('dosen/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.modal-title').text('Hapus Data Dosen');
				$('#modal_confirm').modal('show');
				$('.confirm-msg').empty();
				var nama = "";
				if (data.data.gelar1 != "") {	
					nama = data.data.gelar1 + " " + data.data.nm_dosen;
					if (data.data.gelar2 != "") nama += ", " + data.data.gelar2;
				}	else { 
					if (data.data.gelar2 != "") nama = data.data.nm_dosen + ", " + data.data.gelar2; 
					else nama = data.data.nm_dosen; 
				}				
				$('.confirm-msg').append("Yakin akan menghapus Dosen <b class='text-danger'>" + nama + "</b>?");
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});		
	}
</script>	
</head>