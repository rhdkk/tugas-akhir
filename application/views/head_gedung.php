<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbGedung').dataTable({
			"ajax": "<?php echo site_url('gedung/ajax_list'); ?>",
			"columns": [
				{"width":"150"},null,{"width":"60px"}
			], 
			"order": [[ 0, "asc" ]],
			"columnDefs": [
				{ "targets": [-1],"orderable": false },
				{ className:"text-center","targets":[-1] }				
			],
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},null]
		});		
		
		$(".btn-tambah").click(function() {
			$('#fGedung')[0].reset();				
			var caption = $(this).attr("id"); 
			$('#modal_form').modal('show');			
			$('.modal-title').text('Tambah Data Gedung');			
			$('#btSave').text('Simpan');
		});		
		
		$('#fGedung').validate({ 
			rules: {
				kode: { required:true, maxlength:10 },				
				nama: { required:true, maxlength:50 }			
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
				if(element.length) {
					error.insertAfter(element);
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
			$('#fGedung').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');	
			$("#errKode").hide();			
		});
		
		function save_data() 
		{
			var url,state;
			state = $("#btSave").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('gedung/add_data')?>"; }
      else
      { url = "<?php echo site_url('gedung/update_data')?>"; }

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fGedung').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					if (data.status)
					{				
						if (data.ada) {
							$("#errKode").text('Sudah ada data Gedung dengan Kode yang sama');
							if ($("#errKode").is(":hidden")) $("#errKode").slideDown('fast').removeClass('hide');
							$("#kode").closest('.form-group').removeClass('has-success').addClass('has-error');
						} else {
							$('#modal_form').modal('hide');
							if (data.save) {
								if (state=='Simpan') { msg = 'Data Gedung sudah disimpan'; } 
								else { msg = 'Data Gedung sudah diubah'; }
								$.toast({ text:msg });
								reload_table();
							}
						}
					} 
					else
					{
						$('#alert-gedung').show();
						setTimeout(function(){
							$("#alert-gedung").hide('hide');
						}, 2000);						
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					if (state=='Simpan')
					{ 
						alert('Error Simpan'); 	
						//alert(jqXHR.responseText); 						
					}
					else { alert('Error Update'); }
				}
			});
		}
		
		function delete_data() 
		{
			$.ajax({
				url : "<?php echo site_url('gedung/delete_data')?>",
				type: "POST",
				data: $('#fHapus').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');
					$.toast({ text:"Data Gedung sudah dihapus" });
					reload_table();							
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ alert('Error Hapus'); }
			});
		}
		
		function reload_table()
    {
      tabel.api().ajax.reload(null,false); 
    }
	});
	
	function edit_gedung(id)
	{
		$('#fGedung')[0].reset();	

		$.ajax({
			url : "<?php echo site_url('gedung/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));			
				$('[name="kdGed"]').val(data.data.kd_ged);
				$('[name="kode"]').val(data.data.kd_ged);
				$('[name="nama"]').val(data.data.nm_ged);
				
				$('#btSave').text('Update');
				$('.modal-title').text('Edit Data Gedung'); 
				$('#modal_form').modal('show');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				//alert('Error get data from ajax');
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function hapus_gedung(id)
	{
		$('#idHapus').val(id);		
		$('.modal-title').text('Hapus Data Gedung');
		$('#modal_confirm').modal('show');
		$('.confirm-msg').text('Yakin akan menghapus data Gedung?');
	}
</script>	
</head>