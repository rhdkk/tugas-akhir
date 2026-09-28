<script>
	/* CONFIG */
	var BASE_URL   = '<?= base_url() ?>';
	var CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
	var CSRF_TOKEN = '<?= $this->security->get_csrf_hash() ?>';
	var dataExcel = [];   
	/* DataTable halaman utama */
	$(document).ready(function(){
		var delay = 1000;
		var importedData = [];
		var selectedFile = null;
		var dtMhs, dtDosen;
		function initMhs() {		
			if ($.fn.DataTable.isDataTable('#tbMhs')) {
				$('#tbMhs').DataTable().destroy();
      }        
			dtMhs = $('#tbMhs').dataTable({
				"ajax": "<?php echo site_url('user/ajax_list_mhs'); ?>",
				"paging":   true,
				"info":     true,
				"columns": [
					{"width":"80px"},null,{"width":"250px"},{"width":"70px"},{"width":"70px"}
				], 
				"order": [[ 0, "asc" ]],
				"columnDefs": [
					{ "targets": [-1],"orderable": false },
					{ className:"text-center","targets":[-1] }				
				],
			});	
		}
		function initDosen() {
			if ($.fn.DataTable.isDataTable('#tbDosen')) {
        $('#tbDosen').DataTable().destroy();
      }        
      dtDosen = $('#tbDosen').dataTable({
				"ajax": "<?php echo site_url('user/ajax_list_dosen'); ?>",
				"paging":   true,
				"info":     true,
				"columns": [
					{"width":"100px"},null,{"width":"120px"},{"width":"100px"},{"width":"200px"},{"width":"70px"}
				], 
				"order": [[2,"desc"],[ 1,"asc"]],
				"columnDefs": [
					{ "targets": [-1],"orderable": false },
					{ className:"text-center","targets":[-1] }				
				],
			});		
		}
		function initAdmin() {
			if ($.fn.DataTable.isDataTable('#tbAdmin')) {
        $('#tbAdmin').DataTable().destroy();
      }        
      dtAdmin = $('#tbAdmin').dataTable({
				"ajax": "<?php echo site_url('user/ajax_list_admin'); ?>",
				"paging":   true,
				"info":     true,
				"columns": [
					{"width":"100px"},null,{"width":"120px"},{"width":"100px"},{"width":"200px"},{"width":"70px"}
				], 
				"order": [[3,"desc"],[ 4,"asc"],[ 1,"asc"]],
				"columnDefs": [
					{ "targets": [-1],"orderable": false },
					{ className:"text-center","targets":[-1] }				
				],
			});		
		}
		
		if ($('#tab0').hasClass('active')) {
      initMhs();
    }
		$('a[href="#tab0"]').on('shown.bs.tab', function(e) {
			setTimeout(function() {
			if ($.fn.DataTable.isDataTable('#tbMhs')) {
				$('#tbMhs').DataTable().columns.adjust().responsive.recalc();
			} else {
				initMhs();
			}
			}, 100);
    });    
    $('a[href="#tab1"]').on('shown.bs.tab', function(e) {
			setTimeout(function() {
				if ($.fn.DataTable.isDataTable('#tbDosen')) {
					$('#tbDosen').DataTable().columns.adjust().responsive.recalc();
				} else {
					initDosen();
				}
			}, 100);
    });
		$('a[href="#tab2"]').on('shown.bs.tab', function(e) {
			setTimeout(function() {
				if ($.fn.DataTable.isDataTable('#tbAdmin')) {
					$('#tbAdmin').DataTable().columns.adjust().responsive.recalc();
				} else {
					initAdmin();
				}
			}, 100);
    });
		
		$('.ext-select').select2();
		$('.select2-container').css("width","100%");

		$("#addMhs").click(function() {
			$('#fMhs')[0].reset();	
			$('.ext-select').val(null).trigger('change');
			var caption = $(this).attr("id"); 
			$('#modal_mhs').modal('show');			
			$('.modal-title').text('Tambah Data User Mahasiswa');
			$('#btSaveMhs').text('Simpan');
		});	
		
		$("#addDosen").click(function() {
			$('#fDosen')[0].reset();	
			$('.ext-select').val(null).trigger('change');
			var caption = $(this).attr("id"); 
			$('#modal_dosen').modal('show');			
			$('.modal-title').text('Tambah Data User Dosen');
			$('#btSaveDosen').text('Simpan');
			
			$('#namaD').closest('.form-group').show();
			$('#namaDA').closest('.form-group').hide();
			$('#prodiD').closest('.form-group').hide();
			$('#jurD').closest('.form-group').hide();
			
			$('#prodiD').val('');
			$('#jurD').val('');
			if ($('#prodiD').hasClass('ext-select')) {
					$('#prodiD').trigger('change');
			}
		});	
		
		$("#addAdmin").click(function() {
			$('#fAdmin')[0].reset();	
			$('.ext-select').val(null).trigger('change');
			var caption = $(this).attr("id"); 
			$('#modal_admin').modal('show');			
			$('.modal-title').text('Tambah Data User Admin');
			$('#btSaveAdmin').text('Simpan');
			
			$('#namaAdm').closest('.form-group').show();
			$('#namaAA').closest('.form-group').hide();
			$('#prodiAdm').closest('.form-group').hide();
			$('#jurAdm').closest('.form-group').hide();
			
			$('#prodiAdm').val('');
			$('#jurAdm').val('');
			if ($('#prodiAdm').hasClass('ext-select')) {
					$('#prodiAdm').trigger('change');
			}
		});	
		
		$('#jabD').on('change', function() {
      var selJab = $('#jabD').val();
        
			if (selJab === '1') {
				$('#prodiD').closest('.form-group').hide();
				$('#jurD').closest('.form-group').show();
				$('#prodiD').val('');
				if ($('#prodiD').hasClass('ext-select')) {
					$('#prodiD').trigger('change');
				}
			} 
			else if (selJab === '2') {
				$('#prodiD').closest('.form-group').show();
				$('#jurD').closest('.form-group').hide();
				$('#jurD').val('');
			}
			else {
				$('#prodiD').closest('.form-group').hide();
				$('#jurD').closest('.form-group').hide();
				$('#prodiD').val('');
				$('#jurD').val('');
				if ($('#prodiD').hasClass('ext-select')) {
					$('#prodiD').trigger('change');
				}
				$('#prodiD').closest('.form-group').hide();
				$('#jurD').closest('.form-group').hide();
				$('#prodiD').val('');
				$('#jurD').val('');
				if ($('#prodiD').hasClass('ext-select')) {
						$('#prodiD').trigger('change');
				}
			}  
    });
		
		$('#jabAdm').on('change', function() {
      var selJab = $('#jabAdm').val();
        
			if (selJab === '3') {
				$('#prodiAdm').closest('.form-group').hide();
				$('#jurAdm').closest('.form-group').show();
				$('#prodiAdm').val('');
				if ($('#prodiAdm').hasClass('ext-select')) {
					$('#prodiAdm').trigger('change');
				}
			} 
			else if (selJab === '2') {
				$('#prodiAdm').closest('.form-group').show();
				$('#jurAdm').closest('.form-group').hide();
				$('#jurAdm').val('');
			}
			else {
				$('#prodiAdm').closest('.form-group').hide();
				$('#jurAdm').closest('.form-group').hide();
				$('#prodiAdm').val('');
				$('#jurAdm').val('');
				if ($('#prodiAdm').hasClass('ext-select')) {
					$('#prodiAdm').trigger('change');
				}
				$('#prodiAdm').closest('.form-group').hide();
				$('#jurAdm').closest('.form-group').hide();
				$('#prodiAdm').val('');
				$('#jurAdm').val('');
				if ($('#prodiAdm').hasClass('ext-select')) {
						$('#prodiAdm').trigger('change');
				}
			}  
    });
		
		$('#fMhs').validate({ 
			rules: {
				nim: { required:true, maxlength:15 },				
				nama: { required:true },
				prodi: { required:true },
				jalur: { required:true }
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
				save_mhs();
			}			
		});
		
		$('#fDosen').validate({ 
			rules: {
				namaD: { required:true },
				jurD: { required:true },
				prodiD: { required:true }
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
				save_dosen();
			}			
		});
		
		$('#fAdmin').validate({ 
			rules: {
				unameAdm: { required:true },
				namaAdm: { required:true },
				jabAdm: { required:true },
				jurAdm: { required:true },
				prodiAdm: { required:true }
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
				save_admin();
			}			
		});
		
		$( "#fHapus" ).submit(function(event) {
			delete_data();
			event.preventDefault();
		});
		
		$( "#fReset" ).submit(function(event) {
			reset_pass();
			event.preventDefault();
		});
		
		function save_mhs(){
			var url,state,msg;
			state = $("#btSaveMhs").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('user/add_data_mhs')?>"; }
      else
      { url = "<?php echo site_url('user/update_data_mhs')?>"; }
		
			$("#btSaveMhs").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fMhs').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						$('#modal_mhs').modal('hide');
						if (data.save) {
							if (state=='Simpan') { msg = 'Data User Mahasiswa sudah disimpan'; } 
							else { msg = 'Data User Mahasiswa sudah diubah'; }
							$.toast({ text:msg });
							dtMhs.api().ajax.reload(null,false); 
						}
					} else {
						var errorMsg = data.message || (state == 'Simpan' ? 'Gagal menyimpan data' : 'Tidak ada perubahan data');
						$('#nim').closest('.form-group').removeClass('has-success').addClass('has-error');
						$('#alert-mhs').html('<strong>Error!</strong> ' + errorMsg);
						$('#alert-mhs').show();
						setTimeout(function(){
							$("#alert-mhs").hide('slow');
						}, 3000);						
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{	
					if (state=='Simpan') { msg = "Error Simpan"; }
					else { msg = "Error Update"; }
					$.toast({ text : msg });
				},
				complete: function() {            
					if (state == 'Simpan') {
						$("#btSaveMhs").prop('disabled', false).html('Simpan');
					} else {
						$("#btSaveMhs").prop('disabled', false).html('Update');
					}
        }
			});
		}
		
		$('#modal_mhs').on('hidden.bs.modal', function () {
			$('#fMhs').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');			
			$('#nim').removeAttr('readonly');
			$('#prodi').prop('disabled', false).trigger('change');	
		});
		
		function save_dosen(){
			var url,state,msg;
			state = $("#btSaveDosen").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('user/add_data_dosen')?>"; }
      else
      { url = "<?php echo site_url('user/update_data_dosen')?>"; }
		
			$("#btSaveDosen").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fDosen').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						$('#modal_dosen').modal('hide');
						if (data.save) {
							if (state=='Simpan') { msg = 'Data User Dosen sudah disimpan'; } 
							else { msg = 'Data User Dosen sudah diubah'; }
							$.toast({ text:msg });
							dtDosen.api().ajax.reload(null,false); 
						}
					} else {
						var errorMsg = data.message || (state == 'Simpan' ? 'Gagal menyimpan data' : 'Tidak ada perubahan data');
						$('#alert-dosen').html('<strong>Error!</strong> ' + errorMsg);
						$('#alert-dosen').show();
						setTimeout(function(){
							$("#alert-dosen").hide('slow');
						}, 3000);						
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{	
					if (state=='Simpan') { msg = "Error Simpan"; }
					else { msg = "Error Update"; }
					$.toast({ text : msg });
				},
				complete: function() {            
					if (state == 'Simpan') {
						$("#btSaveDosen").prop('disabled', false).html('Simpan');
					} else {
						$("#btSaveDosen").prop('disabled', false).html('Update');
					}
        }
			});
		}
		
		$('#modal_dosen').on('hidden.bs.modal', function () {
			$('#fDosen').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');			
		});
		
		function save_admin(){
			var url,state,msg;
			state = $("#btSaveAdmin").text();
			if (state=='Simpan') 
			{ url = "<?php echo site_url('user/add_data_admin')?>"; }
      else
      { url = "<?php echo site_url('user/update_data_admin')?>"; }
		
			$("#btSaveAdmin").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

			$.ajax({
				url : url,
				type: "POST",
				data: $('#fAdmin').serialize(),
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						$('#modal_admin').modal('hide');
						if (data.save) {
							if (state=='Simpan') { msg = 'Data User Admin sudah disimpan'; } 
							else { msg = 'Data User Admin sudah diubah'; }
							$.toast({ text:msg });
							dtAdmin.api().ajax.reload(null,false); 
						}
					} else {
						var errorMsg = data.message || (state == 'Simpan' ? 'Gagal menyimpan data' : 'Tidak ada perubahan data');
						$('#alert-admin').html('<strong>Error!</strong> ' + errorMsg);
						$('#alert-admin').show();
						setTimeout(function(){
							$("#alert-admin").hide('slow');
						}, 3000);						
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{	
					if (state=='Simpan') { msg = "Error Simpan"; }
					else { msg = "Error Update"; }
					$.toast({ text : msg });
				},
				complete: function() {            
					if (state == 'Simpan') {
						$("#btSaveAdmin").prop('disabled', false).html('Simpan');
					} else {
						$("#btSaveAdmin").prop('disabled', false).html('Update');
					}
        }
			});
		}
		
		$('#modal_admin').on('hidden.bs.modal', function () {
			$('#fAdmin').validate().resetForm();
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');			
		});
		
		/* Buka modal import */
    $('#importMhs').on('click', function () {
			resetModal();
			$('#modal_import').modal('show');
    });

    /* Reset file input saat modal ditutup */
    $('#modal_import').on('hidden.bs.modal', function () { resetModal(); });
		
		$("#btSave").click(function() { 
			if (!dataExcel || dataExcel.length === 0) return;
	 
			$('#btSave').prop('disabled', true)
									.html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
	 
			var payload = { data: dataExcel };
			payload[CSRF_NAME] = CSRF_TOKEN;
	 
			$.ajax({
				url        : BASE_URL + 'user/save_import',
				type       : 'POST',
				contentType: 'application/json',
				data       : JSON.stringify(payload),
				dataType   : 'json',
				success: function (resp) {
					// Tandai status tiap baris di tabel preview
					if (resp.detail) {
						$.each(resp.detail, function (i, d) {
							var $row  = $('#pvRow_' + d.index);
							var badge = d.status === 'ok'
								? '<span class="label label-success">Berhasil</span>'
								: '<span class="label label-danger" title="' + esc(d.pesan) + '">Gagal</span>';
							$row.find('td:last').html(badge);
							if (d.status !== 'ok') $row.addClass('danger');
						});
					}

					// Alert ringkasan
					var tipe = (resp.gagal > 0) ? 'warning' : 'success';
					var pesan = '<strong>Selesai.</strong> '
							+ 'Berhasil: <strong>' + resp.berhasil + '</strong> baris.'
							+ (resp.gagal > 0
									? ' &nbsp;|&nbsp; Gagal: <strong>' + resp.gagal + '</strong> baris '
										+ '<small>(lihat kolom Status pada tabel di atas)</small>.'
									: '');
					$('#divHasil')
							.html('<div class="alert alert-' + tipe + '" style="margin-top:10px; margin-bottom:0;">'
										+ pesan + '</div>')
							.show();
						
					if (resp.berhasil>0) {
						dtMhs.api().ajax.reload(null,false); 
					}

					// Tombol Save hilang; Batal → Tutup
					$('#btSave').hide();
					$('#btBatal').text('Tutup').removeAttr('data-dismiss').off('click.tutup').on('click.tutup', function () {
						$('#modal_import').modal('hide');						
					});
				},
				error: function () {
					$('#btSave').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
					$('#divHasil').html('<div class="alert alert-danger" style="margin-top:10px; margin-bottom:0;">'+ 'Gagal menghubungi server. Silakan coba lagi.</div>').show();
				}
			});
		});
		
		function delete_data(){
			$("#btHapus").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menghapus...');
			$.ajax({
				url : "<?php echo site_url('user/delete_data')?>",
				type: "POST",
				data: $('#fHapus').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');
					$.toast({ text:"Data User sudah dihapus" });
					var nilai = $('#idHapus').val();
					var tipe = nilai.charAt(0);
					if (tipe==='1')	dtMhs.api().ajax.reload(null,false);
					else if (tipe==='2')	dtDosen.api().ajax.reload(null,false);
					else if (tipe==='3')	dtAdmin.api().ajax.reload(null,false);
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ alert('Error Hapus'); }
			});
		}
		
		function reset_pass(){
			$("#btReset").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mereset...');
			$.ajax({
				url : "<?php echo site_url('user/reset_pass')?>",
				type: "POST",
				data: $('#fReset').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_confirm').modal('hide');					
					$.toast({ text:"Password User sudah direset" });
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ alert('Error Reset'); }
			});
		}
	});
		
	/* RESET STATE MODAL */
	function resetModal() {
		dataExcel = [];
    $('#fileExcel').val('');
    $('#namaFile').text('');
    $('#divLoading').hide();
    $('#divPesanFile').hide().html('');
    $('#divPreview').hide();
    $('#tbodyPreview').html('');
    $('#infoJumlah').text('');
    $('#divHasil').hide().html('');
    $('#btSave').hide();
    // Kembalikan tombol Batal ke kondisi awal
    $('#btBatal').text('Batal').attr('data-dismiss', 'modal').off('click.tutup');
	}

	/* PILIH FILE → BACA EXCEL (SheetJS, client-side) */
	function onFileSelected(input) {
		$('#divPesanFile').hide().html('');
		$('#divPreview').hide();
		$('#tbodyPreview').html('');
		$('#infoJumlah').text('');
		$('#divHasil').hide().html('');
		$('#btSave').hide();
		dataExcel = [];
 
		if (!input.files || !input.files[0]) return;
 
		var file = input.files[0];
		var ext  = file.name.split('.').pop().toLowerCase();
		$('#namaFile').text(file.name);
 
		if (['xls', 'xlsx'].indexOf(ext) === -1) {
				pesanFile('danger', 'Format tidak valid. Hanya <strong>.xls</strong> atau <strong>.xlsx</strong>.');
				return;
		}
 
		$('#divLoading').show();
 
		var reader = new FileReader();
		reader.onload = function (e) {
			try {
				var wb    = XLSX.read(new Uint8Array(e.target.result), { type: 'array' });
				var sheet = wb.Sheets[wb.SheetNames[0]];
				var rows  = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '', raw: false });

				$('#divLoading').hide();

				// Find the row where column A contains "NIM" (case insensitive)
				var startRow = -1;
				for (var i = 0; i < rows.length; i++) {
					if (rows[i] && rows[i][0] && rows[i][0].toString().toUpperCase() === 'NIM') {
						startRow = i + 1; // Start from the next row after "NIM"
						break;
					}
				}

				if (startRow === -1) {
					pesanFile('warning', 'Tidak ditemukan baris dengan kolom A berisi "NIM".');
					return;
				}

				var hasil = [];
				// Start from the row after "NIM" found
				for (var i = startRow; i < rows.length; i++) {
					var r     = rows[i];
					var nim   = xTrim(r[0]);
					var nama  = xTrim(r[1]);
					var prodi = xTrim(r[2]);
					var jalur = xTrim(r[3]);
					if (nim === '' && nama === '' && prodi === '') continue;
					hasil.push({ nim: nim, nama: nama, prodi: prodi, jalur: jalur });
				}

				if (hasil.length === 0) {
					pesanFile('warning', 'Tidak ada data yang ditemukan setelah baris "NIM".');
					return;
				}

				dataExcel = hasil;
				renderPreview(hasil);

			} catch (err) {
				$('#divLoading').hide();
				pesanFile('danger', 'Gagal membaca file: ' + err.message);
			}
		};
		reader.onerror = function () {
				$('#divLoading').hide();
				pesanFile('danger', 'Gagal membaca file.');
		};
		reader.readAsArrayBuffer(file);
	}

	/* RENDER TABEL PREVIEW */
	function renderPreview(data) {
		var html = '';
		for (var i = 0; i < data.length; i++) {
			var r = data[i];
			html += '<tr id="pvRow_' + i + '">' +
				'<td>' + (i + 1) + '</td>' +
				'<td>' + esc(r.nim) + '</td>' +
				'<td>' + esc(r.nama) + '</td>' +
				'<td>' + esc(r.prodi) + '</td>' +
				'<td>' + esc(r.jalur) + '</td>' +
				'<td style="text-align:center; color:#aaa;">—</td>' +
			'</tr>';
		}
		$('#tbodyPreview').html(html);
		$('#infoJumlah').text('Total: ' + data.length + ' baris data siap diimport.');
		$('#divPreview').show();
		$('#btSave').show();
	}

	/* HELPER */
	function pesanFile(type, msg) {
		$('#divPesanFile').html('<div class="alert alert-' + type + '" style="margin-bottom:0;">' + msg + '</div>').show();
	}
	 
	function xTrim(v) { 
		return (v === null || v === undefined) ? '' : String(v).trim(); 
	}
	 
	function esc(v) {
		return String(v || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}
	
	function del_user(tipe,id) {
		$('#idHapus').val(tipe+"-"+id);
		$.ajax({
			url : "<?php echo site_url('user/ajax_edit/')?>/" + $('#idHapus').val(),
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$("#btHapus").prop('disabled', false).html('Ya');
				$('#fHapus').show();
				$('#fReset').hide();
				$('.modal-title').text('Hapus Data User');
				$('#modal_confirm').modal('show');
				$('.confirm-msg-hapus').empty();
				$('.confirm-msg-hapus').append("Yakin akan menghapus User berikut? <ul><li>Username <b class='text-danger'>" +data.data.username+ "</b></li><li>Nama <b class='text-danger'>" +data.data.nm_user+ "</b></li></ul>");
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});		
	}
	
	function edit_mhs(id) {		
		$.ajax({
			url : "<?php echo site_url('mahasiswa/ajax_edit/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.modal-title').text('Ubah Data User Mahasiswa');
				$('#modal_mhs').modal('show');				
				$('#nim').val(id).attr('readonly', 'readonly');
				$('#nama').val(data.data.nm_mhs);				
				$('#nmA').val(data.data.nm_mhs);				
				$('#prodi').val(data.data.jenjang+" "+data.data.nm_ps).trigger('change');
				$('#prodi').prop('disabled', true).trigger('change');				
				$('#jalur').val(data.data.jalur);				
				$('#jlrA').val(data.data.jalur);				
				$('#btSaveMhs').text('Update');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});		
	}
	
	function edit_dosen(id) {		
		$.ajax({
			url : "<?php echo site_url('dosen/ajax_edit_user/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.modal-title').text('Ubah Data User Dosen');
				$('#modal_dosen').modal('show');				
				$('#idUD').val(data.data.id_user);
				$('#namaDA').val(data.data.nm_dosen);				
				$('#namaD').closest('.form-group').hide();
				$('#namaDA').closest('.form-group').show();
				if (data.data.id_jur !== null){
					$('#jabD').val(1);
					$('#jurDA').val(data.data.id_jur);
					$('#jurD').val(data.data.id_jur);										
					$('#prodiD').closest('.form-group').hide();
					$('#jurD').closest('.form-group').show();
					$('#prodiD').val('');
					if ($('#prodiD').hasClass('ext-select')) {
						$('#prodiD').trigger('change');
					}
				}
				else if (data.data.id_ps !== null){
					$('#jabD').val(2);
					$('#psDA').val(data.data.jenjang+" "+data.data.nm_ps);
					$('#prodiD').val(data.data.jenjang+" "+data.data.nm_ps).trigger('change');										
					$('#prodiD').closest('.form-group').show();
					$('#jurD').closest('.form-group').hide();
				}
				else {
					$('#jabD').val('');
					$('#prodiD').closest('.form-group').hide();
					$('#jurD').closest('.form-group').hide();
					$('#prodiD').val('').trigger('change');
					$('#jurD').val('');					
				}
				$('#btSaveDosen').text('Update');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});		
	}
	
	function edit_admin(id) {		
		$.ajax({
			url : "<?php echo site_url('user/ajax_edit_admin/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.modal-title').text('Ubah Data User Dosen');
				$('#modal_admin').modal('show');				
				$('#idUAdm').val(data.data.id_user);
				$('#namaAA').val(data.data.nm_user);				
				$('#unameAdm').val(data.data.username).prop('disabled', true);
				$('#namaAdm').val(data.data.nm_user);				
				if (data.data.id_jur !== null){
					$('#jabAdm').val(3);
					$('#jurAA').val(data.data.id_jur);
					$('#jurAdm').val(data.data.id_jur);					
					$('#jurAdm').closest('.form-group').show();					
					$('#prodiAdm').closest('.form-group').hide();					
					$('#prodiAdm').val('');
					if ($('#prodiAdm').hasClass('ext-select')) {
						$('#prodiAdm').trigger('change');
					}
				}
				else if (data.data.id_ps !== null){
					$('#jabAdm').val(2);
					$('#psAA').val(data.data.jenjang+" "+data.data.nm_ps);
					$('#prodiAdm').val(data.data.jenjang+" "+data.data.nm_ps).trigger('change');										
					$('#prodiAdm').closest('.form-group').show();
					$('#jurAdm').closest('.form-group').hide();
				}
				else {
					$('#jabAdm').val('');
					$('#prodiAdm').closest('.form-group').hide();
					$('#jurAdm').closest('.form-group').hide();
					$('#prodiAdm').val('').trigger('change');
					$('#jurAdm').val('');					
				}
				$('#btSaveAdmin').text('Update');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});		
	}
	
	function res_pass(tipe,id) {
		$('#idReset').val(tipe+"-"+id);
		$.ajax({
			url : "<?php echo site_url('user/ajax_edit/')?>/" + $('#idReset').val(),
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$("#btReset").prop('disabled', false).html('Ya');
				$('#fHapus').hide();
				$('#fReset').show();				
				$('.modal-title').text('Reset Password User');
				$('#modal_confirm').modal('show');
				$('.confirm-msg-reset').empty();
				$('.confirm-msg-reset').append("Yakin akan Reset Password untuk User berikut? <ul><li>Username <b class='text-danger'>" +data.data.username+ "</b></li><li>Nama <b class='text-danger'>" +data.data.nm_user+ "</b></li></ul>");
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});		
	}
</script>	
</head>