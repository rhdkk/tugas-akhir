<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbMatkul').dataTable({
			"ajax": "<?php echo site_url('matkul/ajax_list'); ?>",
			"columns": [
				{"width":"60px"},{"width":"60px"},null,{"width":"30px"},{"width":"30px"},{"width":"80px"},{"width":"40px"},null,{"width":"60px"}
			], 
			"order": [[ 0, "asc" ]],
			"columnDefs": [
				{ "targets": [-1],"orderable": false },
				{ className:"text-center","targets":[0,1,3,4,5,6,-1] }				
			],
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},,{type:"text"},{type:"text"},null]
		});
		
		$('.ext-select').select2();
		$('.select2-container').css("width","100%");
		
		$(".btn-tambah").click(function() {
			$('#fMatkul')[0].reset();	
			$('.ext-select').val(null).trigger('change');
			var caption = $(this).attr("id"); 
			$('#modal_form').modal('show');			
			$('.modal-title').text('Tambah Data Mata Kuliah');
			$('#btSave').text('Simpan');
		});		
		
		$('#fMatkul').validate({ 
			rules: {
				kode: { required:true, maxlength:10 },				
				thkur: { required:true, minlength:4, maxlength:4, number:true },				
				nama: { required:true, maxlength:100 },
				prodi: { required:true }
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
			$('#fMatkul').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');			
		});
		
		function save_data(){
			var url,state,msg;
			state = $("#btSave").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('matkul/add_data')?>"; }
      else
      { url = "<?php echo site_url('matkul/update_data')?>"; }

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fMatkul').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						$('#modal_form').modal('hide');
						if (data.save) {
							if (state=='Simpan') { msg = 'Data Mata Kuliah sudah disimpan'; } 
							else { msg = 'Data Mata Kuliah sudah diubah'; }
							$.toast({ text:msg });
							reload_table();
						}
					} 
					else
					{
						$('#alert-matkul').show();
						setTimeout(function(){
							$("#alert-matkul").hide('hide');
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
				url : "<?php echo site_url('matkul/delete_data')?>",
				type: "POST",
				data: $('#fHapus').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');
					$.toast({ text:"Data Mata Kuliah sudah dihapus" });
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
	
	function edit_matkul(id)
	{
		$('#fMatkul')[0].reset();	

		$.ajax({
			url : "<?php echo site_url('matkul/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));			
				$('#idMatkul').val(data.data.id_mk);
				$('#kode').val(data.data.kode_mk);
				$('#thkur').val(data.data.thkur);
				$('#nama').val(data.data.nm_mk);				
				$('#sks').val(data.data.sks);				
				$('#sem').val(data.data.smt);				
				$('#jenis').val(data.data.jns);				
				$('#tawar').val(data.data.twr);				
				$('#prodi').val(data.data.ps);				
				$('#prodi').trigger('change');				
				
				$('#btSave').text('Update');
				$('.modal-title').text('Edit Data Mata Kuliah'); 
				$('#modal_form').modal('show');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				//alert('Error get data from ajax');
				alert(jqXHR.responseText); 
			}
		});
	};
	
	function hapus_matkul(id)
	{
		$('#idHapus').val(id);
		$.ajax({
			url : "<?php echo site_url('matkul/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.modal-title').text('Hapus Data Mata Kuliah');
				$('#modal_confirm').modal('show');
				$('.confirm-msg').empty();
				$('.confirm-msg').append("Yakin akan menghapus Mata Kuliah <b class='text-danger'>" +data.data.nm_mk+ "</b>?");
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});		
	}
</script>	
</head>