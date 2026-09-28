<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbKarya').dataTable({
			"ajax": "<?php echo site_url('karya/ajax_list')?>",			
			"columns": [
				{"width":"20px"},{"width":"200px"},null,{"width":"20px"},{"width":"40px"}
			], 
			"order": [[ 0,"asc" ]],
<?php $hak=$this->session->userdata('hak_thesis'); if ($hak==1 or $hak==2) { ?>
			"columnDefs": [
				{ "targets": [-1],"orderable": false },
				{ className:"text-center","targets":[0,3,4] }				
			],
<?php } else { ?>
			"columnDefs": [
				{ "targets": [-1],"orderable": false },
				{ className:"text-center","targets":[0,3,4] },
				{ "targets":[-1],"visible":false }
			],
<?php } ?>			
			scrollY: '60vh'
		});	
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},null]
		});

		$(".btn-tambah").click(function() {
			$('#fKarya')[0].reset();										
			$('#modal_form').modal('show');			
			$('.modal-title').text('Input Data Karya Tulis');
			$('#btSave').text('Simpan');
		});		
		
		$('#fKarya').validate({ 
			rules: {
				nim: 	 {minlength:5, required:true, number:true},
        nama:  {minlength:5, required:true},
        judul: {required:true},
				tahun: {required:true, number:true, minlength:4, maxlength:4}
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
			$('#fKarya').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');			
		});
		
		function save_data() 
		{
			var url,state,msg;
			state = $("#btSave").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('karya/add_data')?>"; }
      else
      { url = "<?php echo site_url('karya/update_data')?>"; }

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fKarya').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_form').modal('hide');
					$('#modal_message').modal('show');			
					$('.modal-title').text('Berhasil');
					if (state=='Simpan') 
					{ msg = 'Data Karya Tulis sudah disimpan'; }
					else
					{ msg = 'Data Karya Tulis sudah diubah'; }
					$('.alert-msg').text(msg);
					setTimeout(function(){
						$("#modal_message").modal('hide');
					}, delay);
					reload_table();				
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					if (state=='Simpan')
					{ alert('Error Simpan'); }
					else { alert('Error Update'); }
				}
			});
		}
		
		function delete_data() 
		{
			$.ajax({
				url : "<?php echo site_url('karya/delete_data')?>",
				type: "POST",
				data: $('#fHapus').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');
					$('#modal_message').modal('show');			
					$('.modal-title').text('Berhasil');
					$('.alert-msg').text('Data Karya Tulis sudah dihapus');					
					setTimeout(function(){
						$("#modal_message").modal('hide');
					}, delay);
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
		
		$('.dtp').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			todayHighlight: true
		});
		
		var datepicker = $.fn.datepicker.noConflict();
		$.fn.bootstrapDP = datepicker;
	});
	
	function edit_karya(id)
	{
		$('#fKarya')[0].reset();	

		$.ajax({
			url : "<?php echo site_url('karya/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));			
				$('[name="idKarya"]').val(data.data[0].id_karya);
				$('[name="idProdi"]').val(data.data[0].id_prodi_karya);
				$('[name="idKonsentrasi"]').val(data.data[0].id_konsentrasi_karya);
				$('[name="nim"]').val(data.data[0].nim_karya);				
				$('[name="nama"]').val(data.data[0].nama_karya);
				$('[name="judul"]').val(data.data[0].judul_karya);
				$('[name="abstrak"]').val(data.data[0].abstrak_karya);
				$('[name="tahun"]').val(data.data[0].th_karya);
				$('[name="dosen1"]').val(data.data[0].nm_dosen1);
				$('[name="dosen2"]').val(data.data[0].nm_dosen2);
				$('[name="dosen3"]').val(data.data[0].nm_dosen3);
				
				$('#btSave').text('Update');
				$('.modal-title').text('Edit Data Karya Tulis'); 
				$('#modal_form').modal('show'); // show bootstrap modal when complete loaded				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});
	}
	
	function detil_karya(id)
	{
		$('#fKarya')[0].reset();	

		$.ajax({
			url : "<?php echo site_url('karya/ajax_detil/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));			
				var str = "<table class='table table-condensed table-striped'><tbody>";
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>NIM</td><td>" + data.data[0].nim_karya + "</td></tr>";				
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>Nama</td><td>" + data.data[0].nama_karya + "</td></tr>";				
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>Prodi</td><td>" + data.data[0].prodi_karya + "</td></tr>";				
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>Konsentrasi</td><td>" + data.data[0].konsentrasi_karya + "</td></tr>";				
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>Judul</td><td>" + data.data[0].judul_karya + "</td></tr>";				
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>Abstrak</td><td>" + data.data[0].abstrak_karya + "</td></tr>";				
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>Tahun</td><td>" + data.data[0].th_karya + "</td></tr>";				
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>Pembimbing 1</td><td>" + data.data[0].nm_dosen1 + "</td></tr>";				
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>Pembimbing 2</td><td>" + data.data[0].nm_dosen2 + "</td></tr>";				
				str += "<tr><td style='width:120px; text-align:right; font-weight:bold'>Pembimbing 3</td><td>" + data.data[0].nm_dosen3 + "</td></tr></tbody>";				
				$("#modal_detil .modal-body").empty();
				$("#modal_detil .modal-body").append(str);
				$('#modal_detil').modal('show');				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});
	}
	
	function hapus_karya(id)
	{
		$('#idHapus').val(id);		
		$('.modal-title').text('Hapus Karya Tulis');
		$('#modal_confirm').modal('show');
		$('.confirm-msg').text('Yakin akan menghapus data karya tulis?');
	}
</script>	
</head>
<body>