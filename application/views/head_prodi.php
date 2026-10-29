<script>
	var currentNmPs = '';
	var currentNmTU = '';
	var currentIdTU = '';
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbProdi').dataTable({
			"ajax": "<?php echo site_url('prodi/ajax_list'); ?>",
			"paging":   false,
      "info":     false,
			"columns": [
				{"width":"100px"},null,{"width":"60px"},{"width":"60px"},{"width":"60px"},{"width":"70px"},{"width":"60px"},{"width":"60px"}
			], 
			"order": [[ 0, "asc" ]],
			"columnDefs": [
				{ "targets": [-1],"orderable": false },
				{ className:"text-center","targets":[2,3,4,5,6,-1] }				
			]
		});

		var tabelTahap = $('#tbTahap').DataTable({
				"ajax": {
					"url": "<?php echo site_url('prodi/ajax_list_tahap'); ?>/",
					"type": "GET",
					"dataType": "json",
					"dataSrc": function(json) {
						currentIdPs = json.id_ps || '';
						currentNmPs = json.nm_ps || '';
						currentP1 = json.p1 || 0; 
						currentP2 = json.p2 || 0; 
            return json.data || [];
					},
					"error": function(xhr, error, thrown) {
						console.error('Ajax error:', error);
						console.error('Response:', xhr.responseText);
					}
				},
				"searching": false,
				"paging": false,
				"autoWidth": false,
				"info": false,
				"columns": [
					{"width":"20px"},null,{"width":"20px"},{"width":"140px"},{"width":"20px"},{"width":"40px"},{"width":"40px"},{"width":"70px"}
				],
				"order": [[0, "asc"]],
				"columnDefs": [
					{"targets": [2,3,4,5,5,-1], "orderable": false},
					{"className": "text-center", "targets": [0,2,4,5,6,-1]}
				],
				"initComplete": function() {
					if (currentNmPs) {
						$('#title-tahap').text('Tahap Ujian - ' + currentNmPs);
						$('#idPS').val(currentIdPs);
						$('#p1').val(currentP1);
						$('#p2').val(currentP2);
						$('#title-form').text('Form Tahap Ujian');
					}
        },
        "drawCallback": function() {
					if (currentNmPs) {
						$('#title-tahap').text('Tahap Ujian - ' + currentNmPs);
						$('#idPS').val(currentIdPs);
						$('#p1').val(currentP1);
						$('#p2').val(currentP2);
						$('#title-form').text('Form Tahap Ujian');
					}
        }				
		});
		
		var tabelSyarat = $('#tbSyarat').DataTable({
				"ajax": {
					"url": "<?php echo site_url('ujian/ajax_list_syarat'); ?>/001",
					"type": "GET",
					"dataType": "json",
					"dataSrc": function(json) {
						currentIdTU = json.id_tahap || '';
						currentNmTU = json.nm_tahap || '';
            return json.data || [];
					},
					"error": function(xhr, error, thrown) {
						console.error('Ajax error:', error);
						console.error('Response:', xhr.responseText);
					}
				},
				"searching": false,
				"paging": false,
				"autoWidth": false,
				"info": false,
				"columns": [
					{"width":"20px"},null,{"width":"20px"},{"width":"50px"}
				],
				"order": [[0, "asc"]],
				"columnDefs": [
					{"targets": [-1], "orderable": false},
					{"className": "text-center", "targets": [0,2,-1]}
				],
				"initComplete": function() {
					if (currentNmTU) {
						$('#title-syarat').text("Syarat Ujian - "+currentNmTU);						
						$('#title-form').text('Form Syarat Ujian');
					}
        },
        "drawCallback": function() {
					if (currentNmTU) {
						$('#title-syarat').text("Syarat Ujian - "+currentNmTU);						
						$('#title-form').text('Form Syarat Ujian');
					}
        }				
		});
		
		var tabelNilai = $('#tbNilai').DataTable({
				"ajax": {
					"url": "<?php echo site_url('ujian/ajax_list_nilai'); ?>/001",
					"type": "GET",
					"dataType": "json",
					"dataSrc": function(json) {
						currentIdTU = json.id_tahap || '';
						currentNmTU = json.nm_tahap || '';
            return json.data || [];
					},
					"error": function(xhr, error, thrown) {
						console.error('Ajax error:', error);
						console.error('Response:', xhr.responseText);
					}
				},
				"searching": false,
				"paging": false,
				"autoWidth": false,
				"info": false,
				"columns": [
					null,{"width":"20px"},{"width":"50px"}
				],
				"order": [[0, "asc"]],
				"columnDefs": [
					{"targets": [1,-1], "orderable": false},
					{"className": "text-center", "targets": [1,-1]}
				],
				"initComplete": function() {
					if (currentNmTU) {
						$('#title-nilai').text("Kriteria Penilaian - "+currentNmTU);						
						$('#title-form').text('Form Kriteria Penilaian');
					}
        },
        "drawCallback": function() {
					if (currentNmTU) {
						$('#title-nilai').text("Kriteria Penilaian - "+currentNmTU);						
						$('#title-form').text('Form Kriteria Penilaian');
					}
        }				
		});
		
		$(".btn-tambah").click(function() {
			$('#fProdi')[0].reset();	
			$('#modal_form').modal('show');			
			$('.modal-title').text('Tambah Data Program Studi');			
			$('#btSave').text('Simpan');
		});		
		
		$("#btBatalTahap").click(function() {			
			$('#form_tahap').slideToggle(200);
			$('#data_tahap').slideToggle(300);
			$('#fTahap').trigger('reset');
			$('#fTahap').validate().resetForm();		
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');
		});
		
		$("#btBatalSyarat").click(function() {			
			$('#form_syarat').slideToggle(200);
			$('#data_syarat').slideToggle(300);
			$('#fSyarat').trigger('reset');
			$('#fSyarat').validate().resetForm();		
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');
		});
		
		$("#btBatalNilai").click(function() {			
			$('#form_nilai').slideToggle(200);
			$('#data_nilai').slideToggle(300);
			$('#fNilai').trigger('reset');
			$('#fNilai').validate().resetForm();		
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');
		});
		
		$("#addTahap").click(function() {
			$('#fTahap').trigger('reset');
			$('#fTahap').validate().resetForm();
			$('#title-form').text('Tambah Data Tahap Ujian');				
			$('#groupPemb').show();
			$('#groupPeng').show();
			$('#idProdiThp').val($('#idPS').val());
			$('#nPemb').val($('#p1').val());
			$('#nPeng').val($('#p2').val());
			$('#btSaveTahap').text('Simpan');
			$('#form_tahap').slideToggle(400);
			$('#data_tahap').slideToggle(300);
		});
		
		$("#addSyarat").click(function() {
			$('#fSyarat').trigger('reset');
			$('#fSyarat').validate().resetForm();
			$('#title-form-syarat').text('Tambah Syarat - ' + currentNmTU);				
			$('#idTahapSyarat').val(currentIdTU);
			$('#btSaveSyarat').text('Simpan');
			$('#form_syarat').slideDown(400);
			$('#data_syarat').slideUp(300);
			
			$.ajax({
				url: "<?php echo site_url('ujian/ajax_unlisted_syarat'); ?>/" + currentIdTU,
				type: 'GET',
				dataType: 'json',
				beforeSend: function () {
					$('#cbSyarat')
						.prop('disabled', true)
						.html('<option value="">Loading...</option>');
				},
				success: function (response) {
					var $cb = $('#cbSyarat').empty();
					$.each(response.data, function (i, row) {
						$cb.append($('<option>', {
							value: row[0],   
							text: row[1]     
						}));
					});
					$cb.prop('disabled', false);
				},
				error: function (xhr, status, err) {
					console.error('Failed to load syarat:', err);
					$('#cbSyarat')
						.html('<option value="">-- Gagal memuat data --</option>')
						.prop('disabled', false);
				}
			});
		});	
		
		$("#addNilai").click(function() {
			$('#fNilai').trigger('reset');
			$('#fNilai').validate().resetForm();
			$('#title-form-nilai').text('Tambah Kriteria Penilaian - ' + currentNmTU);				
			$('#idTahapNilai').val(currentIdTU);
			$('#btSaveNilai').text('Simpan');
			$('#form_nilai').slideDown(400);
			$('#data_nilai').slideUp(300);			
		});	
		
		$('.ext-select').select2();
		
		$("#closeSyarat").click(function() {			
			$('#data_tahap').slideToggle(200);
			$('#data_syarat').slideToggle(300);
		});
		
		$("#newSyarat").click(function() {			
			$('#syaratNow').hide();
			$('#syaratBaru').show();
		});
		
		$("#hideSyarat").click(function() {			
			$('#syaratNow').show();
			$('#syaratBaru').hide();
		});
		
		$("#closeNilai").click(function() {			
			$('#data_tahap').slideToggle(200);
			$('#data_nilai').slideToggle(300);
		});
		
		$('#penilai').on('change', function() {
			var selectedValue = $(this).val();
			if (selectedValue=="2") {
				$('#groupPemb').hide();
				$('#groupPeng').hide();
			}
			else {
				$('#groupPemb').show();
				$('#groupPeng').show();
			}
    });
		
		$.validator.addClassRules("skor", {
			required: true,
			number: true,
			range: [0,100]
		})
		
		$('#fProdi').validate({ 
			rules: {
				prodi: { required:true, maxlength:30 }								
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
		
		$('#fTahap').validate({ 
			rules: {
				tahap: { required:true, maxlength:30 },
				pNilai: { required:true },
				pPeng: { required:true },
				pPemb: { required:true },
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
				save_tahap();
			}			
		});
		
		$('#fSyarat').validate({
			rules: {
				txSyarat: { required: true, maxlength: 50 }
			},
			highlight: function (element) {
				$(element)
					.closest('.input-group, .form-group')
					.removeClass('has-success')
					.addClass('has-error');
			},
			unhighlight: function (element) {
				$(element)
					.closest('.input-group, .form-group')
					.removeClass('has-error')
					.addClass('has-success');
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function (error, element) {
				if (element.attr('id') === 'txSyarat') {
					error.insertAfter('#syaratBaru');
				}
				else if (element.hasClass('ext-select')) {
					error.insertAfter('.select2-container');
				}
				else {
					error.insertAfter(element);
				}
			},
			submitHandler: function () {
				save_syarat();
			}
		});
		
		$('#fNilai').validate({ 
			rules: {
				nilai: { required:true, maxlength:50 },
				pNilaiThp: { required:true }
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
				save_nilai();
			}			
		});
		
		$( "#fHapus" ).submit(function(event) {
			delete_data();
			event.preventDefault();
		});

		$('#modal_form').on('hidden.bs.modal', function () {		
			$('#fProdi').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');			
		});
		
		function save_data(){
			var url,state,msg;
			state = $("#btSave").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('prodi/add_data')?>"; }
      else
      { url = "<?php echo site_url('prodi/update_data')?>"; }

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fProdi').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						$('#modal_form').modal('hide');
						if (data.ada) {
							msg = 'Sudah ada data Program Studi dengan Jurusan, Jenjang dan Nama yang sama';
							$.toast({ text:msg });
						} else {
							if (data.save) {
								if (state=='Simpan') { msg = 'Data Program Studi sudah disimpan'; } 
								else { msg = 'Data Program Studi sudah diubah'; }
								$.toast({ text:msg });
								reload_table();
							}
						}
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
		 
		function save_tahap(){
			var url,state,msg;
			state = $("#btSaveTahap").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('prodi/add_tahap')?>"; }
      else
      { url = "<?php echo site_url('prodi/update_tahap')?>"; }

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fTahap').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						$('#form_tahap').slideUp(300);
						$('#data_tahap').slideDown(400);
						if (data.save) {
							if (state=='Simpan') { msg = 'Data Tahap Ujian sudah disimpan'; } 
							else { msg = 'Data Tahap Ujian sudah diubah'; }
							$.toast({ text:msg });
							reload_table();
							reload_table_tahap();
						}
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
		
		function save_syarat(){
			var url,state,msg;
			state = $("#btSaveSyarat").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('ujian/add_syarat')?>"; }
      else
      { url = "<?php echo site_url('ujian/update_syarat')?>"; }

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fSyarat').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						$('#form_syarat').slideUp(300);
						$('#data_syarat').slideDown(400);
						if (data.save) {
							if (state=='Simpan') { msg = 'Data Syarat Tahap Ujian sudah disimpan'; } 
							else { msg = 'Data Syarat Tahap Ujian sudah diubah'; }
							$.toast({ text:msg });							
							reload_table_tahap();
							reload_table_syarat();
						}
					} 					
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					if (state=='Simpan') { msg = "Error Simpan Syarat"; }
					else { msg = "Error Update Syarat"; }
					$.toast({ text : msg });
				}
			});
		}
		
		function save_nilai(){
			var url,state,msg;
			state = $("#btSaveNilai").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('ujian/add_kriteria')?>"; }
      else
      { url = "<?php echo site_url('ujian/update_kriteria')?>"; }

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fNilai').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						$('#form_nilai').slideUp(300);
						$('#data_nilai').slideDown(400);
						if (data.save) {
							if (state=='Simpan') { msg = 'Data Kriteria Nilai Ujian sudah disimpan'; } 
							else { msg = 'Data Kriteria Nilai Ujian sudah diubah'; }
							$.toast({ text:msg });
							reload_table_tahap();
							reload_table_nilai();
						}
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
				url : "<?php echo site_url('prodi/delete_data')?>",
				type: "POST",
				data: $('#fHapus').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');
					$.toast({ text:"Data Program Studi sudah dihapus" });
					reload_table();				
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ alert('Error Hapus'); }
			});
		}
		
		function reload_table(){
      tabel.api().ajax.reload(null,false);       
    }
		
		function reload_table_tahap() {
      tabelTahap.ajax.reload(null,false); 
    }
		
		function reload_table_syarat() {
      tabelSyarat.ajax.reload(null,false); 
    }
		
		function reload_table_nilai() {
      tabelNilai.ajax.reload(null,false); 
    }
	});
	
	function edit_prodi(id)
	{
		$('#fProdi')[0].reset();	

		$.ajax({
			url : "<?php echo site_url('prodi/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				//alert(JSON.stringify(data));			
				$('#idProdi').val(data.data.id_ps);
				$('#jur').val(data.data.id_jur);
				$('#prodi').val(data.data.nm_ps);
				$('#jen').val(data.data.jenjang);				
				$('#pemb').val(data.data.p1);			
				$('#peng').val(data.data.p2);			
				$('#reg').val(data.data.tipe_reg);			
				$('#rev').val(data.data.tipe_rev);			
				
				$('#btSave').text('Update');
				$('.modal-title').text('Edit Data Program Studi'); 
				$('#modal_form').modal('show');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				//alert('Error get data from ajax');
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function det_tahap(id) {
		var table = $('#tbTahap').DataTable();
		table.ajax.url("<?php echo site_url('prodi/ajax_list_tahap'); ?>/" + id).load();
		
		$('#fTahap').trigger('reset');
		$('#fTahap').validate().resetForm();		
		$('.form-group').removeClass('has-error');			
		$('.form-group').removeClass('has-success');		
			
		$('#form_tahap').hide();	$('#data_tahap').show();
		$('#form_syarat').hide();	$('#data_syarat').hide();
		$('#form_nilai').hide();	$('#data_nilai').hide();
		$('#modal_tahap').modal('show');
	}
	
	function syarat_tahap(id) {
		var table = $('#tbSyarat').DataTable();
		table.ajax.url("<?php echo site_url('ujian/ajax_list_syarat'); ?>/" + id).load();
		
		$('#fSyarat').trigger('reset');
		$('#fSyarat').validate().resetForm();		
		$('.form-group').removeClass('has-error');			
		$('.form-group').removeClass('has-success');		
			
		$('#form_syarat').hide();
		$('#data_syarat').slideDown(400);
		$('#data_tahap').slideUp(300);
	}
	
	function nilai_tahap(id) {
		var table = $('#tbNilai').DataTable();
		table.ajax.url("<?php echo site_url('ujian/ajax_list_nilai'); ?>/" + id).load();
		
		$('#fNilai').trigger('reset');
		$('#fNilai').validate().resetForm();		
		$('.form-group').removeClass('has-error');			
		$('.form-group').removeClass('has-success');		
			
		$('#form_nilai').hide();
		$('#data_nilai').slideDown(400);
		$('#data_tahap').slideUp(300);
	}
	
	function edit_tahap(id) {		
		$.ajax({
			url : "<?php echo site_url('prodi/ajax_tahap/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				$('#fTahap').trigger('reset');
				$('#fTahap').validate().resetForm();
				$('.form-group').removeClass('has-error');			
				$('.form-group').removeClass('has-success');
				
				$('#idTahap').val(data.data.id_tuji);
				$('#tahap').val(data.data.nm_tuji);
				$('#jenis').val(data.data.jns_tuji);
				$('#penilai').val(data.data.penilai);				
				$('#pNilai').val(data.data.pers_tuji);							
				$('#groupPemb').show();
				$('#groupPeng').show();
				$('#idProdiThp').val($('#idPS').val());							
				$('#pPemb').val(data.data.nilai_p1);		
				$('#pPeng').val(data.data.nilai_p2);			
				$('#nPemb').val($('#p1').val());
				$('#nPeng').val($('#p2').val());	
				
				if (data.data.penilai=="2") {
					$('#groupPemb').hide();
					$('#groupPeng').hide();
				}
				else {
					$('#groupPemb').show();
					$('#groupPeng').show();
				}
				
				$('#btSaveTahap').text('Update');
				$('#title-form').text('Ubah Data Tahap Ujian');				
				$('#form_tahap').slideToggle(400);
				$('#data_tahap').slideToggle(300);
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				//alert('Error get data from ajax');
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function edit_syarat(id) {
    var pList = $.ajax({
			url: "<?php echo site_url('ujian/ajax_unlisted_syarat'); ?>/" + currentIdTU,
			type: 'GET',
			dataType: 'json'
    });

    var pItem = $.ajax({
			url: "<?php echo site_url('ujian/ajax_syarat/') ?>/" + id,
			type: 'GET',
			dataType: 'json'
    });

    $.when(pList, pItem).done(function (listRes, itemRes) {
			var listData = listRes[0];   
			var itemData = itemRes[0];

			/* 1. Populate dropdown */
			var $cb = $('#cbSyarat').empty();
			$cb.append('<option value="">-- Pilih --</option>');
			$.each(listData.data, function (i, row) {
				$cb.append($('<option>', { value: row[0], text: row[1] }));
			});

			/* 2. Append current item if not present */
			var idBerkas = itemData.data.id_berkas;
			if ($cb.find('option[value="' + idBerkas + '"]').length === 0) {
				$cb.append($('<option>', {
					value: idBerkas,
					text: itemData.data.nm_berkas
				}));
			}

			/* 3. Select it (AFTER options exist) */
			$cb.val(idBerkas).trigger('change');
			$cb.prop('disabled', false);

			/* 4. Fill the rest */
			$('#idSyarat').val(itemData.data.id_syarat);
			$('#jenisSyarat').val(itemData.data.jns_syarat);
			$('#btSaveSyarat').text('Update');
			$('#title-form-syarat').text('Ubah Data Syarat Ujian');
			$('#form_syarat').slideDown(400);
			$('#data_syarat').slideUp(300);
		}).fail(function (xhr) {
			console.error(xhr);
			$('#cbSyarat').html('<option value="">-- Gagal memuat --</option>').prop('disabled', false);
			alert('Gagal memuat data.');
    });
	}
	
	function edit_kriteria(id) {
    $.ajax({
			url : "<?php echo site_url('ujian/ajax_kriteria/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				$('#fNilai').trigger('reset');
				$('#fNilai').validate().resetForm();
				$('.form-group').removeClass('has-error');			
				$('.form-group').removeClass('has-success');
				
				$('#idNilai').val(data.data.id_kriteria);
				$('#nilai').val(data.data.nm_kriteria);
				$('#pNilaiThp').val(data.data.pers_nilai);
				
				$('#btSaveNilai').text('Update');
				$('#title-form').text('Ubah Data Kriteria Nilai Ujian');				
				$('#form_nilai').slideToggle(400);
				$('#data_nilai').slideToggle(300);
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