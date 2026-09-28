<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbRuang').dataTable({
			"ajax": "<?php echo site_url('ruang/ajax_list'); ?>",
			"columns": [
				{"width":"60px"},null,{"width":"60px"},{"width":"30px"},{"width":"30px"},{"width":"30px"},{"width":"60px"}
			], 
			"order": [[1,"asc"]],
			"columnDefs": [
				{ "targets": [-1],"orderable": false },
				{ className:"text-center","targets":[2,3,4,5,-1] }				
			],
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},null]
		});
		
		$('.ext-select').select2();
		$('.select2-container').css("width","100%");
		
		$(".btn-tambah").click(function() {
			$('#fRuang')[0].reset();	
			$('.ext-select').val(null).trigger('change');
			var caption = $(this).attr("id"); 
			$('#modal_form').modal('show');			
			$('.modal-title').text('Tambah Data Ruang');
			$('#btSave').text('Simpan');
		});		
		
		$('#fRuang').validate({ 
			rules: {
				kode: { required:true, maxlength:10 },				
				ruang: { required:true, maxlength:50 },				
				lantai: { required:true },				
				kapkul: { required:true, minlength:1, maxlength:3, number:true },				
				kapuji: { required:true, minlength:1, maxlength:3, number:true }
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
			$('#fRuang').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');
			$("#errKode").hide();			
			$("#errRuang").hide();			
		});
		
		function save_data(){
			var url,state,msg,cek,c1,c2;
			state = $("#btSave").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('ruang/add_data')?>"; }
      else
      { url = "<?php echo site_url('ruang/update_data')?>"; }

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fRuang').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						cek = data.ada;
						if (cek==="NN") {
							$('#modal_form').modal('hide');
							if (data.save) {
								if (state=='Simpan') { msg = 'Data Ruang sudah disimpan'; } 
								else { msg = 'Data Ruang sudah diubah'; }
								$.toast({ text:msg });
								reload_table();
							}
						} else {
							c1 = cek.charAt(0);
							c2 = cek.charAt(1);
							if (c1==="Y") {
								$("#errKode").text('Sudah ada data Ruang dengan Kode yang sama');
								if ($("#errKode").is(":hidden")) $("#errKode").slideDown('fast').removeClass('hide');
								$("#kode").closest('.form-group').removeClass('has-success').addClass('has-error');
							} else if (!$("#errKode").is(":hidden")) $("#errKode").slideUp('fast');
							if (c2==="Y") {
								$("#errRuang").text('Sudah ada data Ruang dengan Nama yang sama');
								if ($("#errRuang").is(":hidden")) $("#errRuang").slideDown('fast').removeClass('hide');
								$("#ruang").closest('.form-group').removeClass('has-success').addClass('has-error');
							} else if (!$("#errRuang").is(":hidden")) $("#errRuang").slideUp('fast');
						}
					} 
					else
					{
						$('#alert-ruang').show();
						setTimeout(function(){
							$("#alert-ruang").hide('hide');
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
		
		function delete_data() 
		{
			$.ajax({
				url : "<?php echo site_url('ruang/delete_data')?>",
				type: "POST",
				data: $('#fHapus').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');
					$.toast({ text:"Data Ruang sudah dihapus" });
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
	
	function edit_ruang(id)
	{
		$('#fRuang')[0].reset();	

		$.ajax({
			url : "<?php echo site_url('ruang/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));			
				$('#idRuang').val(data.data.id_ruang);
				$('#kode').val(data.data.kd_ruang);
				$('#ruang').val(data.data.nm_ruang);
				$('#gedung').val(data.data.kd_ged);
				$('#gedung').trigger('change');
				$('#lantai').val(data.data.lantai);				
				$('#kapkul').val(data.data.kap_kul);				
				$('#kapuji').val(data.data.kap_uji);				
				
				$('#btSave').text('Update');
				$('.modal-title').text('Edit Data Ruang'); 
				$('#modal_form').modal('show');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				//alert('Error get data from ajax');
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function hapus_ruang(id)
	{
		$('#idHapus').val(id);
		$.ajax({
			url : "<?php echo site_url('ruang/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.modal-title').text('Hapus Data Ruang');
				$('#modal_confirm').modal('show');
				$('.confirm-msg').empty();
				$('.confirm-msg').append("Yakin akan menghapus Ruang <b class='text-danger'>" +data.data.nm_ruang+ "</b>?");
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});		
	}
</script>	
</head>