<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbSmt').dataTable({
			"ajax": "<?php echo site_url('smt/ajax_list'); ?>",
			"columns": [
				{"width":"60px"},{"width":"60px"},null
			], 
			"ordering": false,
			"columnDefs": [
				{ className:"text-center","targets":[0] }				
			],
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},null]
		});
		
		$(".btn-next").click(function() {
			$('#fSmt').show();
			$('#fAktif').hide();
			$('.modal-title').text('Konfirmasi');
			$('.confirm-msg').text('Yakin akan melanjutkan sistem ke semester baru?');
			$('#modal_confirm').modal('show');
		});

		$( "#fSmt" ).submit(function(event) {
			next_smt();
			event.preventDefault();
		});			

		$( "#fAktif" ).submit(function(event) {
			aktif_smt();
			event.preventDefault();
		});		
		
		function next_smt() 
		{
			$.ajax({
				url : "<?php echo site_url('smt/next_smt')?>",
				type: "POST",
				data: $('#fSmt').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');
					$('#modal_message').modal('show');			
					$('.modal-title').text('Berhasil');
					$('#lb-sem').text(data.sem);
					$('.alert-msg').text('Data Sistem sudah diganti ke Semester Baru');					
					setTimeout(function(){
						$("#modal_message").modal('hide');
					}, delay);
					reload_table();				
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ 
					alert('Error Ganti Semester Sistem'); 
					/*alert(jqXHR.responseText);*/				
				}
			});
		}
		
		function aktif_smt() 
		{
			$.ajax({
				url : "<?php echo site_url('smt/aktif_smt')?>",
				type: "POST",
				data: $('#fAktif').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');
					$('#modal_message').modal('show');			
					$('.modal-title').text('Berhasil');
					$('#lb-sem').text(data.sem);
					$('.alert-msg').text('Semester sudah diganti');					
					setTimeout(function(){
						$("#modal_message").modal('hide');
					}, delay);
					reload_table();				
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ 
					alert('Error Ganti Semester'); 
					/*alert(jqXHR.responseText);*/				
				}
			});
		}
		
		function reload_table()
    {
      tabel.api().ajax.reload(null,false); 
    }
	});
	
	function on_smt(id)
	{
		var th1 = '20' + id.substring(0,2);
		var th2 = parseInt(th1)+1;
		var sm = id.substring(2,3);
		var sem;		
		if (sm=='1') sem='Ganjil'; else sem='Genap';
		$('#fAktif').show();
		$('#fSmt').hide();
		$('#idOn').val(id);		
		$('.modal-title').text(sem+' '+th1+'/'+th2);
		$('#modal_confirm').modal('show');
		$('.confirm-msg').text('Yakin akan mangaktifkan semester ini?');
	}
</script>	
</head>