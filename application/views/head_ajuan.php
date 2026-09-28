<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbAjuan').dataTable({
			"ajax": "<?php echo site_url('ajuan/ajax_list'); ?>",
			"columns": [
				{"width":"100px"},null,null,{"width":"120px"},null,{"width":"60px"}
			], 
			"order": [[3,"desc"]],
			"columnDefs": [
				{ "targets": [-1],"orderable": false },
<?php $hak = $this->session->userdata('hak_layanan'); if ($hak==2) { ?>
				{ "targets": [0,1],"visible": false },
<?php } ?>
				{ className:"text-center","targets":[0,3,-1] }				
			]
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},null]
		});		
		
		$('.ext-select').select2();
		$('.select2-container').css("width","100%");
		
		$.fn.loadSyarat = function(id){       
			$('.newAdded').remove();			
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('jenis/viewSyarat/');?>/" + id,
				success: function(data)
				{
					var values = [];
					$.each(JSON.parse(data),function(id_tipe,nm_tipe) {
						values.push({id: parseInt(id_tipe), nama:nm_tipe});						
					})
					if (values.length>0) {
						$("#nS").val(values.length);
						$('.divider').show();
						$('.copyS').show();						
						for (var i=0; i<values.length; i++) {
							if (i==0) {
								$("#lbSyaratA0").html(values[i].nama);
								$("#nmS0").val(values[i].nama);
								$("#idS0").val(values[i].id);
							} else {
								newType = $(".copyS").first().clone().addClass("newAdded");						
								newType.appendTo("#kwS");			
								newType.attr('id','copyS'+i);	
								newType.find("#lbSyaratA0").attr('id','lbSyaratA'+i).text(values[i].nama);
								newType.find(".noS").val(i).attr('id','noS'+i).attr('name','noS'+i);
								newType.find(".nmS").val(values[i].nama).attr('id','nmS'+i).attr('name','nmS'+i);
								newType.find(".idS").val(values[i].id).attr('id','idS'+i).attr('name','idS'+i);
								newType.find("#syarat0").attr('id','syarat'+i).attr('name','syarat'+i);																
								$(".newAdded").show();												
							} 
						}
					} else {
						$('.divider').hide();
						$('.copyS').hide();
					}					
				}
			}); 
    }
		$.fn.cekAttrib = function(id){       						
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('jenis/cekAttr/');?>/" + id,
				success: function(data)
				{
					var attr = JSON.parse(data).attrib;
					judul = attr.charAt(0);
					kons = attr.charAt(1);
					$("#yJ").val(judul); 
					if (judul=='0') $(".jdl").hide();
					else $(".jdl").show();
					if (kons=='0') $(".dpt").hide();
					else $(".dpt").show();
				}
			}); 
    };		
		
		$("#jenis").change(function() {
			$('#fAjuan').validate().resetForm();			
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');	
			$('.newAdded').remove();			
			$('.alert-danger').addClass('hide');	
			$.fn.loadSyarat(this.value+"-1");
			$.fn.cekAttrib(this.value);
		});
		
		$(".btn-tambah").click(function() {
			$('#fAjuan').show();	
			$('#btSave').show();	
			$('#btNo').show();	
			for (var i=0; i<10; ++i) {			
				$('#form'+i).hide();	
				$('#btYF'+i).hide();	
				$('#btNF'+i).hide();	
			}
			
			$('#fAjuan')[0].reset();			
			var caption = $(this).attr("id"); 
			$('#modal_form').modal('show');			
			$('.modal-title').text('Pengajuan Layanan Baru');
			$('#btSave').text('Ajukan');
		});	
		
		$("#btSave").click(function(){        
      $("#fAjuan").submit();
    });
		
		$("#btYF1").click(function(){        
			ajuan_1(1);
		});
		
		$("#btNF1").click(function(){        
			ajuan_1(2);
		});
		
		$("#btYF2").click(function(){        
			$("#form2").submit();
		});
		
		$("#btYF3").click(function(){        
			$("#form3").submit();
		});
		
		$("#btYF4").click(function(){        
			$("#form4").submit();
		});
		
		$.validator.addMethod('filesize', function (value, element, param) {
			return this.optional(element) || (element.files[0].size <= param)
		}, function(size){
			return "MAX SIZE " + filesize(size,{exponent:2,round:1});
		});
		
		$("#fAjuan").submit(function(event) {
			$(".error-form-ajuan").empty().hide();			
			$(".error-form").empty().hide();			
			
			$('.syarat').each(function() {
				$(this).rules("add", {
					required: true,
					extension: 'pdf',					
					filesize: 20971520,
					messages: {
						required: "File tidak boleh kosong",
						extension: "File harus dalam format PDF",
						filesize: "File maksimal berukuran 20 MB"
					}
				});
			});
			event.preventDefault();
		});
		
		$('#fAjuan').validate({
			onfocusout: false,
			onkeyup :false,
			onclick : false,
			rules: {
				jenis: { required:true },
				judul: { required:true }
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
				no = parseInt(element.parents(".form-group").find(".noS").val());
				error.appendTo("#errAjuan"+no);				
				$("#errAjuan"+no).slideDown('fast').removeClass('hide');				
			},
			submitHandler: function() {
				save_ajuan();
			}			
		});
		
		$('#form2').validate({
			onfocusout: false,
			onkeyup :false,
			onclick : false,			
			rules: {
				dosen0: { required:true },				
				dosen1: { required:true }		
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
				no = parseInt(element.parents(".form-group").find(".noD").val());
				$("#errDosen"+no).empty();
				error.appendTo("#errDosen"+no);				
				$("#errDosen"+no).slideDown('fast').removeClass('hide');				
			},
			submitHandler: function() {
				ajuan_2();
			}			
		});
		
		$('#form3').validate({
			rules: {
				tglF3: { required:true }				
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
				$("#errTgl").empty();
				error.appendTo("#errTgl");				
				$("#errTgl").slideDown('fast').removeClass('hide');				
			},
			submitHandler: function() {
				ajuan_3();
			}			
		});
		
		$("#form4").submit(function(event) {
			$(".error-form-ajuan").empty().hide();			
			$(".error-form").empty().hide();			
			
			$('.berkas').each(function() {
				$(this).rules("add", {
					required: true,
					extension: 'pdf',					
					filesize: 20971520,
					messages: {
						required: "File tidak boleh kosong",
						extension: "File harus dalam format PDF",
						filesize: "File maksimal berukuran 20 MB"
					}
				});
			});
			event.preventDefault();
		});
		
		$('#form4').validate({
			onfocusout: false,
			onkeyup :false,
			onclick : false,
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
				no = parseInt(element.parents(".form-group").find(".noB").val());
				error.appendTo("#errBerkas"+no);				
				$("#errBerkas"+no).slideDown('fast').removeClass('hide');				
			},
			submitHandler: function() {
				ajuan_4();
			}			
		});
		
		$( "#fHapus" ).submit(function(event) {
			$(".gone").remove();
			del_data();
			event.preventDefault();
		});
		
		$('#modal_form').on('hidden.bs.modal', function () {
			$('.divider').hide();
			$('.copyS').hide();						
			$('.jdl').hide();						
			$('.dpt').hide();	
			$('#fAjuan').validate().resetForm();			
			$('.form-group').removeClass('has-error');			
			$('.form-group').removeClass('has-success');	
			$('.newAdded').remove();			
			$('.alert-danger').addClass('hide');	
		});
		
		function save_ajuan() {
			var url,state,msg,fn;
			state = $("#btSave").text();
			nS = $("#nS").val();
			url = "<?php echo site_url('ajuan/add_data')?>/" + nS;		
			$.ajax({
				url : url,
				type: "POST",
				data: new FormData($('#fAjuan')[0]),/*.serialize(),*/
				dataType: "JSON",
				processData:false,
				contentType:false,
				cache:false,				
				success: function(data)
				{
					if (data.status) {
						$('#modal_form').modal('hide');						
						$.toast({ text:'Data Permohonan sudah diajukan' });
						reload_table();						
					}				
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		function ajuan_1(act) {
			var id = "1-" + $("#idF1").val() + "-" +act;
			url = "<?php echo site_url('ajuan/proses')?>/" + id;		
			$.ajax({
				url : url,
				type: "POST",
				data: new FormData($('#form1')[0]),
				dataType: "JSON",
				processData:false,
				contentType:false,
				cache:false,				
				success: function(data)
				{
					$('#modal_form').modal('hide');						
					if (act==1) $.toast({ text:'Data Pengajuan sudah disetujui'});
					else $.toast({ text:'Data Pengajuan sudah ditolak'});
					reload_table();						
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		function ajuan_2() {
			var id = "2-" + $("#idF2").val() + "-1";
			url = "<?php echo site_url('ajuan/proses')?>/" + id;		
			$.ajax({
				url : url,
				type: "POST",
				data: new FormData($('#form2')[0]),
				dataType: "JSON",
				processData:false,
				contentType:false,
				cache:false,				
				success: function(data)
				{
					$('#modal_form').modal('hide');						
					$.toast({ text:'Data Penguji sudah disimpan'});					
					reload_table();						
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		function ajuan_3() {
			var id = "3-" + $("#idF3").val() + "-1";
			url = "<?php echo site_url('ajuan/proses')?>/" + id;		
			$.ajax({
				url : url,
				type: "POST",
				data: new FormData($('#form3')[0]),
				dataType: "JSON",
				processData:false,
				contentType:false,
				cache:false,				
				success: function(data)
				{
					$('#modal_form').modal('hide');						
					$.toast({ text:'Waktu Pelaksanaan Ujian sudah disimpan'});					
					reload_table();						
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		function ajuan_4() {
			var id = "4-" + $("#idF4").val() + "-1";
			url = "<?php echo site_url('ajuan/proses')?>/" + id;		
			$.ajax({
				url : url,
				type: "POST",
				data: new FormData($('#form4')[0]),
				dataType: "JSON",
				processData:false,
				contentType:false,
				cache:false,				
				success: function(data)
				{
					$('#modal_form').modal('hide');						
					$.toast({ text:'Ujian Sudah Terjadwal'});					
					reload_table();						
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		function reload_table() {
      tabel.api().ajax.reload(null,false); 
    }
		
		$('#tglF3').datepicker({
			startDate: "new Date()",					
			autoclose: true
		});
		$('#mulaiF3').clockpicker();
		$('#selesaiF3').clockpicker();
	});
	
	/*Form Action*/	
	function verf_ajuan(id){
		var idx=1;		
		$('#idF1').val(id);	
		$.ajax({
			url : "<?php echo site_url('ajuan/ajax_ajuan/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('#fAjuan').hide();	
				$('#btSave').hide();	
				$('#btNo').hide();	
				for (var i=0; i<10; ++i) {			
					if (i===idx) {
						$('#form'+i).show();	
						$('#btYF'+i).show();	
						$('#btNF'+i).show();	
					}
					else {
						$('#form'+i).hide();	
						$('#btYF'+i).hide();	
						$('#btNF'+i).hide();	
					}
				}
				$('#modal_form').modal('show');			
				$('.modal-title').text('Verifikasi '+data.data.jenis);
				$('#nimF1').empty();
				$('#nimF1').append(data.data.nim);
				$('#namaF1').empty();
				$('#namaF1').append(data.data.nama);
				$('#judulF1').empty();
				$('#judulF1').append(data.data.judul);
				$('#konfF1').empty();
				$('#konfF1').append('Apakah Pengajuan <b>' +data.data.jenis+ '</b> disetujui?');
				$('#fileF1').attr("href", "<?php echo base_url() ?>/upload/" +data.data.link[0]);
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});
	}
	
	function pilih_dosen(id){
		var idx=2;	
		$('#idF2').val(id);	
		$.ajax({
			url : "<?php echo site_url('ajuan/ajax_ajuan/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {	
				$('.ext-select').val(null).trigger('change');	
				$('#fAjuan').hide();	
				$('#btSave').hide();	
				for (var i=0; i<10; ++i) {			
					if (i===idx) {
						$('#form'+i).show();	
						$('#btYF'+i).show();	
						$('#btNF'+i).show();	
					}
					else {
						$('#form'+i).hide();	
						$('#btYF'+i).hide();	
						$('#btNF'+i).hide();	
					}
				}
				$('#modal_form').modal('show');			
				$('.modal-title').text('Penentuan Penguji '+data.data.jenis);
				$('#nimF2').empty();
				$('#nimF2').append(data.data.nim);
				$('#namaF2').empty();
				$('#namaF2').append(data.data.nama);
				$('#judulF2').empty();
				$('#judulF2').append(data.data.judul);				
				$('#fileF2').attr("href", "<?php echo base_url() ?>/upload/" +data.data.link[0]);
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});
	}
	
	function set_dt(id){
		var idx=3;	
		$('#idF3').val(id);	
		$.ajax({
			url : "<?php echo site_url('ajuan/ajax_ajuan/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {	
				$('#fAjuan').hide();	
				$('#btSave').hide();	
				for (var i=0; i<10; ++i) {			
					if (i===idx) {
						$('#form'+i).show();	
						$('#btYF'+i).show();	
						$('#btNF'+i).show();	
					}
					else {
						$('#form'+i).hide();	
						$('#btYF'+i).hide();	
						$('#btNF'+i).hide();	
					}
				}
				$('#modal_form').modal('show');			
				$('.modal-title').text('Set Waktu '+data.data.jenis);
				$('#tglF3').val('<?php echo date('Y-m-d'); ?>');			
				$('#mulaiF3').val('00:00');			
				$('#selesaiF3').val('00:00');					
				$('#judulF3').empty();
				$('#judulF3').append(data.data.judul);								
				$('#penguji1F3').empty();
				$('#penguji1F3').append(data.data.dosen[0]);
				$('#penguji2F3').empty();
				$('#penguji2F3').append(data.data.dosen[1]);								
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});
	}
	
	function up_ajuan(id){
		var idx=4;	
		$('#idF4').val(id);	
		$.ajax({
			url : "<?php echo site_url('ajuan/ajax_ajuan/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {	
				$('#fAjuan').hide();	
				$('#btSave').hide();	
				for (var i=0; i<10; ++i) {			
					if (i===idx) {
						$('#form'+i).show();	
						$('#btYF'+i).show();	
						$('#btNF'+i).show();	
					}
					else {
						$('#form'+i).hide();	
						$('#btYF'+i).hide();	
						$('#btNF'+i).hide();	
					}
				}
				$('#modal_form').modal('show');			
				$('.modal-title').text('Penjadwalan '+data.data.jenis);
				$('#nimF4').text(data.data.nim);
				$('#namaF4').text(data.data.nama);
				$('#tglF4').text(data.data.hartang);			
				$('#wktF4').text(data.data.wkt);											
				$('#penguji1F4').text(data.data.dosen[0]);
				$('#penguji2F4').text(data.data.dosen[1]);
				$('#nBF4').val(data.data.syarat.length);
				$('#idB0').val(data.data.syarat[0]);
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});
	}
</script>	
</head>

