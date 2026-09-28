<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbJalps').dataTable({
			"ajax": "<?php echo site_url('jalps/ajax_list'); ?>",
			"paging":   false,
      "info":     false,
			"columns": [
				null,{"width":"30px"},{"width":"30px"},{"width":"30px"},{"width":"30px"},{"width":"60px"}
			], 
			"columnDefs": [
			{ "targets": [-1],"orderable": false },
				{ className:"text-center","targets":[1,2,3,-1] }				
			],
		});				
		
		$('.ext-select').select2();
		$('.select2-container').css("width","100%");
		
		$(".btn-tambah").click(function() {
			$('#fJalps')[0].reset();	
			$('.ext-select').val(null).trigger('change');
			var caption = $(this).attr("id"); 
			$('#modal_form').modal('show');			
			$('.modal-title').text('Tambah Data Jalur Program Studi');
			$('#btSave').text('Simpan');
		});		
		
		$('#fJalps').validate({ 
			rules: {
				prodi: { required:true },								
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
			$('#fJalps').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');			
		});
		
		function save_data(){
			var url,msg;
			url = "<?php echo site_url('jalps/update_data')?>";

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fJalps').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						$('#modal_form').modal('hide');
						if (data.save) {
							msg = 'Kode Kelas Jalur Program Studi sudah diubah';
							$.toast({ text:msg });
							reload_table();
						}
					} 
					else
					{
						$('#alert-jalps').show();
						setTimeout(function(){
							$("#alert-jalps").hide('hide');
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
		
		function reload_table(){
      tabel.api().ajax.reload(null,false); 
    }
	});
	
	function edit_jalps(id)
	{
		$('#fJalps')[0].reset();	

		$.ajax({
			url : "<?php echo site_url('jalps/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));			
				$('#idPS').val(data.data.id_ps);				
				$('#prodi').val(data.data.nm_ps);
				$('#kode1').val(data.data.kode1);				
				$('#edKode1').val(data.data.kode1);				
				$('#kode2').val(data.data.kode2);				
				$('#edKode2').val(data.data.kode2);				
				$('#kode3').val(data.data.kode3);				
				$('#edKode3').val(data.data.kode3);				
				$('#kode4').val(data.data.kode4);				
				$('#edKode4').val(data.data.kode4);				
				$('#btSave').text('Update');
				$('.modal-title').text('Edit Data Jalur Program Studi'); 
				$('#modal_form').modal('show');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				//alert('Error get data from ajax');
				alert(jqXHR.responseText); 
			}
		});
	}
</script>	
</head>