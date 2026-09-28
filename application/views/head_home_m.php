<?php 
	$hak = $this->session->userdata('hak_thesis');		
?>
<script>
	$(document).ready(function(){		
		$('input[type="file"]').each(function() {
			$(this).change(function() {
				var fileName = this.files[0] ? this.files[0].name : $(this).prev('.file-label').text();				
				$(this).prev('.file-label').text(fileName);
				/*alert(fileName);*/
			});
		})
		
		var delay = 1000;
		var tabelRM = $('#tbRevM').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listRevMhs')."/".$nim; ?>",
			"columns": [
				{"width":"80px"},null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,				
			dom: 'lrtip',				
			"columnDefs": [
				{ className:"text-center","targets":[0,-1] }
			]		
		});		
		tabelRM.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},null]
		});
		
		var tabelBM = $('#tbBimbM').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listBimbMhs')."/".$nim; ?>",
			"columns": [
				{"width":"80px"},null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,				
			"columnDefs": [
				{ className:"text-center","targets":[0,-1] }
			]		
		});		
		tabelBM.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},null]
		});
		
		var tabelBA = $('#tbBerkasA').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listBerkasAju')."/".$nim; ?>",
			"columns": [
				null,{"width":"20px"}
			], 
			filter: false,
			paging: false,
      info: false,
			ordering: false,				
			"columnDefs": [
				{ className:"text-center","targets":[-1] }
			]		
		});
		
		var tabelBRA = $('#tbBerkasRA').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listBerkasAju')."/".$nim; ?>",
			"columns": [
				null,{"width":"20px"}
			], 
			filter: false,
			paging: false,
      info: false,
			ordering: false,				
			"columnDefs": [
				{ className:"text-center","targets":[-1] }
			],
			"initComplete": function(settings, json){ 
        $("#nBRA").val($(":input[class=fileRev]").length);	
			}				
		});
		
		$("#btn-excel").click(function() {						
			exp_xls();
		});	
		
		$('#tglRev').datepicker({
			autoclose : true
		});
		
		$('#tglBimb').datepicker({
			autoclose : true
		});
		
		$("#btRev").click(function(){        
      $("#fRev").submit();
    });
		
		$("#btRevAjuan").click(function(){        
      $("#fRevAjuan").submit();
    });

		$("#btAjuan").click(function(){        
      $("#fAjuan").submit();
    });
		
		$("#btBimb").click(function(){        
      $("#fBimb").submit();
    });
		
		$("#btUjian").click(function(){        
      if ($("#ketBimb").is(':hidden')) {
				$("#ketBimb").slideDown(200);						
				$("#jnsBimb").val(1);
				$("#btJudul").show();
				$(this).text("Ajukan Permohonan Persetujuan Ujian");								
				$(this).addClass('btn-primary').addClass('btn-xs').removeClass('btn-success');								
			}
			else {
				$("#ketBimb").slideUp(200);
				$("#jnsBimb").val(2);
				$("#btJudul").hide();
				$(this).text("Pengajuan Permohonan Persetujuan Ujian");				
				$(this).removeClass('btn-primary').removeClass('btn-xs').addClass('btn-success');								
			}
    });
		
		$("#btJudul").click(function(){        
      if ($("#ketBimb").is(':hidden')) {
				$("#ketBimb").slideDown(200);
				$("#judulNew").slideUp(200);
				$("#btUjian").show();						
				$("#jnsBimb").val(1);
				$(this).text("Ajukan Perubahan Judul");								
				$(this).addClass('btn-warning').addClass('btn-xs').removeClass('btn-success');								
			}
			else {
				$("#ketBimb").slideUp(200);
				$("#judulNew").slideDown(200);
				$("#btUjian").hide();
				$("#jnsBimb").val(3);
				$(this).text("Pengajuan Perubahan Judul");				
				$(this).removeClass('btn-warning').removeClass('btn-xs').addClass('btn-success');								
			}
    });
		
		timStat();
		
		$.validator.addMethod('filesize', function (value, element, param) {
			return this.optional(element) || (element.files[0].size <= param)
		}, function(size){
			return "MAX SIZE " + filesize(size,{exponent:2,round:1});
		});
		
		$.validator.addMethod("unik", function(value, element) {
      var parentForm = $(element).closest('form');
      var timeRepeated = 0;
      if (value != '') {
        $(parentForm.find('.ext-select')).each(function () {
          if ($(this).val() === value) {
            timeRepeated++;
          }
        });
      }
      return timeRepeated === 1 || timeRepeated === 0;      
		}, "Data tidak boleh berulang");
		
		$("#revBerkas").click(function() {
			$('#fBimb')[0].reset();							
			$.ajax({
				url : "<?php echo site_url('mahasiswa/ajax_tim'); echo "/".$nim; ?>",
				type: "GET",
				dataType: "JSON",
				success: function(data)
				{
					/*console.log(JSON.stringify(data));*/
					$('#dosen').children().remove().end().append('<option value="" style="width:100%"> - Pilih Dosen - </option>') ;
					var nP1=0; nP2=0; nm="";								
					for (var i=0; i<data.data.length; ++i) {	
						if (data.data[i].stat_dospem==1) {
							nm = ""; gelar1=$.trim(data.data[i].gelar1); gelar2=$.trim(data.data[i].gelar2);
							if (gelar1!="") nm += gelar1+" ";
							nm += data.data[i].nm_dosen;
							if (gelar2!="") nm += ", "+gelar2;
							if (data.data[i].jab_dosen==1) {
								++nP1; 
								if (data.data[i].acc_bimb!=1) {
									$('#dosen').append($('<option>', {
										value: data.data[i].id_dospem,
										text: nm
									}));
								}
							}
						}
						else if (data.data[i].jab_dosen==2) { ++nP2; }
					} 
					if ($('#dosen').find('option').length>1) {
						$('#fBimb').show();			
						$('#titleBimb').text("Tambah Data Bimbingan");				
						$('#modal_formBimb').modal('show');		
						
						$("#ketBimb").removeClass('hide');						
						$("#judulNew").hide();	
						$("#jnsBimb").val(1);
						$("#btUjian").text("Ajukan Permohonan Persetujuan Ujian");														
						$("#btUjian").addClass('btn-primary').addClass('btn-xs').removeClass('btn-success');
						$("#btJudul").text("Ajukan Perubahan Judul");								
						$("#btJudul").addClass('btn-warning').addClass('btn-xs').removeClass('btn-success');
					}
				},
				error: function (jqXHR, textStatus, errorThrown){
					alert(jqXHR.responseText); 
				}
			});
		});	
		
		$("#addRev").click(function() {
			$('#fRev')[0].reset();							
			$.ajax({
				url : "<?php echo site_url('mahasiswa/ajax_rev')."/".$nim; ?>",
				type: "GET",
				dataType: "JSON",
				success: function(data)
				{
					/*console.log(JSON.stringify(data));*/
					var nRev = data.data.length;
					var tipeRev = data.data[0].tipe_rev;
					$('#tpRev').val(tipeRev);
					if (tipeRev=='2') {
						$('#dosenRev').children().remove().end().append('<option value="" style="width:100%"> - Pilih Dosen - </option>') ;
						for (var i=0; i<nRev; ++i) {	
							nm = ""; gelar1=$.trim(data.data[i].gelar1); gelar2=$.trim(data.data[i].gelar2);
							if (gelar1!="") nm += gelar1+" ";
							nm += data.data[i].nm_dosen;
							if (gelar2!="") nm += ", "+gelar2;
							$('#dosenRev').append($('<option>', {
								value: data.data[i].id_dosen,
								text: nm
							}));
						} 
					} else {
						$('#ctDosen').addClass('hidden');
						$('#ketRev').show();
						$('#idDU').val(data.data[0].id_det_uji);
						$('#lbTUji').text(data.data[0].nm_tuji);
						$('#lbTglRev').text(data.data[0].tgl_uji);
						var strRev = "";
						var lnRev = "";
						for (var i=0; i<nRev; ++i) {
							nm = ""; gelar1=$.trim(data.data[i].gelar1); gelar2=$.trim(data.data[i].gelar2);
							if (gelar1!="") nm += gelar1+" ";
							nm += data.data[i].nm_dosen;
							if (gelar2!="") nm += ", "+gelar2;
							
							strRev += "<hr style='margin-top:3px; margin-bottom:2px;'/><b>"+nm+"</b><br/>";
							lnRev = data.data[i].rev_uji.split("|-|");
							if (lnRev.length>1) {
								strRev += "<ol style='padding-left:15px'>";
								for (var j=0; j<lnRev.length; ++j) {
									strRev += "<li>"+lnRev[j]+"</li>"; }
								strRev += "</ol>";
							}
							else {
								strRev += lnRev[0];
							}
						}
						$('#lbKetRev').html(strRev);						
					}
					
					if (nRev>0) {
						$('#fRev').show();	
						if (tipeRev=='2') $('#ketRev').hide();
						$('#titleBimb').text("Tambah Data Revisi");				
						$('#modal_formRev').modal('show');		
					} else {
						$('.info-msg').text("Belum ada data Revisi baru dari Penguji");	
						$('#modal_info').modal('show');	
					}
				},
				error: function (jqXHR, textStatus, errorThrown){
					alert(jqXHR.responseText); 
				}
			});
		});
		
		$("#addBimb").click(function() {
			$('#fBimb')[0].reset();							
			$.ajax({
				url : "<?php echo site_url('mahasiswa/ajax_tim')."/".$nim; ?>",
				type: "GET",
				dataType: "JSON",
				success: function(data)
				{
					/*console.log(JSON.stringify(data));*/
					$('#dosenBimb').children().remove().end().append('<option value="" style="width:100%"> - Pilih Dosen - </option>') ;
					var nP1=0; nP2=0; nm="";
					for (var i=0; i<data.data.length; ++i) {
						if (data.data[i].stat_dospem==1) {
							nm = ""; gelar1=$.trim(data.data[i].gelar1); gelar2=$.trim(data.data[i].gelar2);
							if (gelar1!="") nm += gelar1+" ";
							nm += data.data[i].nm_dosen;
							if (gelar2!="") nm += ", "+gelar2;
							if (data.data[i].jab_dosen==1) {
								++nP1; 
								if (data.data[i].acc_bimb!=1) {
									$('#dosenBimb').append($('<option>', {
										value: data.data[i].id_dospem,
										'data-jab': data.data[i].jab_dosen,
										'data-urut': data.data[i].urut_dosen,
										text: nm
									}));
								}
							}
						}
						else if (data.data[i].jab_dosen==2) { ++nP2; }
					} 
					if ($('#dosenBimb').find('option').length>1) {
						$('#fBimb').show();			
						$('#titleBimb').text("Tambah Data Bimbingan");				
						$('#modal_formBimb').modal('show');		
						
						$("#ketBimb").show();						
						$("#btUjian").show();						
						$("#btJudul").hide();						
						$("#judulNew").hide();						
						$("#jnsBimb").val(1);
						$("#btUjian").text("Ajukan Permohonan Persetujuan Ujian");								
						$("#btJudul").text("Ajukan Perubahan Judul");								
						$("#btUjian").addClass('btn-primary').addClass('btn-xs').removeClass('btn-success');
						$("#btJudul").addClass('btn-warning').addClass('btn-xs').removeClass('btn-success');
					}
				},
				error: function (jqXHR, textStatus, errorThrown){
					alert(jqXHR.responseText); 
				}
			});
		});	
		
		$("#dosenBimb").change(function() {			
			var selectedOption = $(this).find('option:selected');
			var jab = selectedOption.data('jab');
			var urut = selectedOption.data('urut');
			
			$('#fBimb').show();			
			$('#titleBimb').text("Tambah Data Bimbingan");				
			$('#modal_formBimb').modal('show');		
			
			$("#ketBimb").show();						
			$("#btUjian").show();						
			$("#btJudul").hide();						
			$("#judulNew").hide();						
			$("#jnsBimb").val(1);
			$("#btUjian").text("Ajukan Permohonan Persetujuan Ujian");								
			$("#btJudul").text("Ajukan Perubahan Judul");								
			$("#btUjian").addClass('btn-primary').addClass('btn-xs').removeClass('btn-success');
			$("#btJudul").addClass('btn-warning').addClass('btn-xs').removeClass('btn-success');
			
			if (jab==1 && urut==1) {
				$('#btJudul').show();
			} else {
				$('#btJudul').hide();
			}
		});
		
		$("#btThesis").click(function(){        
      $("#fThesis").submit();
    });
		
		$("#dosenRev").change(function() {			
			$.ajax({
				url : "<?php echo site_url('mahasiswa/ajax_rev')."/".$nim; ?>",
				type: "GET",
				data: {
					idD	: $(this).val()
				},
				dataType: "JSON",
				success: function(data)
				{
					if ($("#dosenRev").val()==="") {
						$('#ketRev').hide();
					} else {
						$('#ketRev').show();
						$('#idDU').val(data.data[0].id_det_uji);
						$('#lbTUji').text(data.data[0].nm_tuji);
						$('#lbTglRev').text(data.data[0].tgl_uji);
						$('#lbKetRev').text(data.data[0].rev_uji);
					}
				},
				error: function (jqXHR, textStatus, errorThrown){
					alert(jqXHR.responseText); 
				}
			});
		});
		
		$('.ext-select').select2();
		$('.select2-container').css("width","100%");
		$(".ext-select").change(function() {			
			$(this).valid();
		});
		
		$("#fAjuan").submit(function(event) {
			$('.berkas').each(function() {
				$(this).rules("add", {
					extension: 'pdf',	
					filesize: 20971520,					
					messages: {
						extension: "File harus dalam format PDF",
						filesize: "File maksimal berukuran 20 MB"
					}
				});
			});
			event.preventDefault();
		});
		
		$('#fAjuan').validate({
			rules: {				
<?php
	if ($mhs->urut_progress>=5 and $mhs->urut_progress<=7)
	{
		$i=0; $nS=count($syarat);
		foreach($syarat as $s) 
		{
			$i++;
			if ($s['jns_syarat']==1)
			{		
				if (($s['filename']!='x' and $s['naskah']==1) or ($s['filename']=='x'))
				{
					if ($i>1) echo ", ";
					echo "berkas".$i.": {required:true}";
				}
			}
		}
	}
?>				
			},
			messages: {
<?php
	if ($mhs->urut_progress==5 or $mhs->urut_progress==6)
	{
		$i=0; $nS=count($syarat);
		foreach($syarat as $s) 
		{
			if ($s['jns_syarat']==1)
			{			
				$i++;
				if ($i>1) echo ", ";
				echo "berkas".$i.": {required:'File tidak boleh kosong'}";
			}
		}
	}
?>	
			},
			highlight: function(element) {
				if($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
					$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
				}				
        else $(element).closest('.form-group').removeClass('has-success').addClass('has-error');   								
			},
			unhighlight: function(element) {
				if($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
					$(element).closest('.form-group').removeClass('has-error').addClass('has-success');					
				}				
        else $(element).closest('.form-group').removeClass('has-error').addClass('has-success');								
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				if (element.hasClass('ext-select'))
					error.insertAfter(element.next('.select2-container'));
				else error.insertAfter(element);
			},
			submitHandler: function() {
				save_ajuan();
			}			
		});
		
		$("#fRevAjuan").submit(function(event) {
			$('.fileRev').each(function() {
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
		
		$('#fRevAjuan').validate({
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');								
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				error.insertAfter(element.next('#btRevFile'));
			},			
			submitHandler: function() {
				save_revAjuan();
			}			
		});
		
		function reload_tabelRM(){
      tabelRM.api().ajax.reload(null,false); 
    }
		
		function reload_tabelBM(){
      tabelBM.api().ajax.reload(null,false); 
    }
		
		function reload_tabelBA(){
      tabelBA.api().ajax.reload(null,false); 
    }
		
		function timStat() {
			var url,state;
			$.ajax({
				url : "<?php echo site_url('mahasiswa/ajax_tim')."/".$nim; ?>",
				type: "GET",
				dataType: "JSON",
				success: function(data)
				{
					/*console.log(JSON.stringify(data));*/
					<?php if ($stage!=3) { ?>
					for (var i=0; i<data.data.length; ++i) {	
						if (data.data[i].jab_dosen==1) {
							if (data.data[i].stat_dospem==1) {
								$('#lbDsn1'+data.data[i].urut_dosen+'1').text('B');
								$('#lbDsn1'+data.data[i].urut_dosen+'1').prop("title","Bimbingan");
							}
							else if (data.data[i].stat_dospem==2) {
								$('#lbDsn1'+data.data[i].urut_dosen+'1').text('U');
								$('#lbDsn1'+data.data[i].urut_dosen+'1').prop("title","ACC Ujian");
							}
							
							if (data.data[i].acc_bimb==1) {
								$('#lbDsn1'+data.data[i].urut_dosen+'2').text('V');
								$('#lbDsn1'+data.data[i].urut_dosen+'2').prop("title","Belum Validasi");
							}
						}
					}
					<?php } else { ?>
					for (var i=0; i<data.data.length; ++i) {
						if (data.data[i].stat_dospem==3) {
							$('#lbDsn'+data.data[i].jab_dosen+data.data[i].urut_dosen+'3').removeClass('label-success').addClass('label-danger');
							$('#lbDsn'+data.data[i].jab_dosen+data.data[i].urut_dosen+'3').text('R');
							$('#lbDsn'+data.data[i].jab_dosen+data.data[i].urut_dosen+'3').prop("title","REVISI Ujian");
						}
						else if (data.data[i].stat_dospem==4) {
							$('#lbDsn'+data.data[i].jab_dosen+data.data[i].urut_dosen+'3').removeClass('label-danger').addClass('label-success');
							$('#lbDsn'+data.data[i].jab_dosen+data.data[i].urut_dosen+'3').text('S');
							$('#lbDsn'+data.data[i].jab_dosen+data.data[i].urut_dosen+'3').prop("title","SUDAH Penilaian");
						}
						else {
							$('#lbDsn'+data.data[i].jab_dosen+data.data[i].urut_dosen+'2').text('B');
							$('#lbDsn'+data.data[i].jab_dosen+data.data[i].urut_dosen+'2').prop("title","BELUM Penilaian");
						}
					}						
					<?php } ?>
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		$('#fRev').validate({ 
			rules: {				
				tglRev: { required:true },
				dosenRev: { required:true }
			},
			highlight: function(element) {
				if ($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
					$(element).closest('.form-comp').removeClass('has-success').addClass('has-error');
				}				
        else $(element).closest('.form-comp').removeClass('has-success').addClass('has-error');   								
			},
			unhighlight: function(element) {
				if($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
					$(element).closest('.form-comp').removeClass('has-error').addClass('has-success');					
				}				
        else $(element).closest('.form-comp').removeClass('has-error').addClass('has-success');								
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				if (element.hasClass('ext-select')){					
					error.insertAfter(element.next('.select2-container'));										
				}
				else error.insertAfter(element);
			},
			submitHandler: function() {
				save_revisi();
			}			
		});
		
		function save_revisi() {
			var url,state;
			$.ajax({
				url : "<?php echo site_url('home/save_revisi')?>",
				type: "POST",
				data: $('#fRev').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					if (data.status) {
						$('#modal_formRev').modal('hide');
						$.toast({ text:"<b>Data Revisi Ujian</b> sudah disimpan" });
						reload_tabelRM();
					} 
					else
					{
						$('#alert-thesis').show();
						setTimeout(function(){
							$("#errThesis").hide('hide');
						}, 2000);						
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		$('#modal_formRev').on('hidden.bs.modal', function () {		
			$('#fRev').validate().resetForm();
			$('.form-comp').removeClass('has-error');			
			$('.form-comp').removeClass('has-success');							
		});
		
		function save_ajuan() {
			var url,state;
			nS = $("#nS").val();
			$.ajax({
				url : "<?php echo site_url('Home/save_ajuan')?>/" + nS,
				type: "POST",
				data: new FormData($('#fAjuan')[0]),
				dataType: "JSON",
				processData:false,
				contentType:false,
				cache:false,				
				success: function(data)
				{
					if (data.status) {
						window.location.href = "<?php echo site_url('home')?>";						
					} 
					else
					{
						$('#alert-ajuan').show();
						setTimeout(function(){
							$("#errAjuan").hide('hide');
						}, 2000);						
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		function save_revAjuan() {
			var url,state;
			nBRA = $("#nBRA").val();
			$.ajax({
				url : "<?php echo site_url('Home/save_revAjuan')?>/" + nBRA,
				type: "POST",
				data: new FormData($('#fRevAjuan')[0]),
				dataType: "JSON",
				processData:false,
				contentType:false,
				cache:false,				
				success: function(data)
				{
					if (data.status) {
						window.location.href = "<?php echo site_url('home')?>";						
					} 
					else
					{
						$('#alert-revAjuan').show();
						setTimeout(function(){
							$("#errRevAjuan").hide('hide');
						}, 2000);						
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		$('#fBimb').validate({
			rules: {				
				tglBimb: { required:true },
				ketBimb: { required:true },
				judulNew: { required:true },
				dosenBimb: { required:true }
			},
			highlight: function(element) {
				if ($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
					$(element).closest('.form-comp').removeClass('has-success').addClass('has-error');
				}				
        else $(element).closest('.form-comp').removeClass('has-success').addClass('has-error');   								
			},
			unhighlight: function(element) {
				if($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
					$(element).closest('.form-comp').removeClass('has-error').addClass('has-success');					
				}				
        else $(element).closest('.form-comp').removeClass('has-error').addClass('has-success');								
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				if (element.hasClass('ext-select')){					
					error.insertAfter(element.next('.select2-container'));										
				}
				else error.insertAfter(element);
			},
			submitHandler: function() {
				save_bimbingan();
			}			
		});
		
		function save_bimbingan() {
			var url,state;
			$.ajax({
				url : "<?php echo site_url('Home/save_bimbingan')?>",
				type: "POST",
				data: $('#fBimb').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_formBimb').modal('hide');
					if (data.status) {						
						$.toast({ text:"<b>Data Bimbingan</b> sudah disimpan" });
						reload_tabelBM();
					} 
					else {
						$.toast({ text:"<b>Data Bimbingan</b> GAGAL disimpan!!!" });
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
		
		$('#modal_formBimb').on('hidden.bs.modal', function () {		
			$('#fBimb').validate().resetForm();
			$('.form-comp').removeClass('has-error');			
			$('.form-comp').removeClass('has-success');							
		});
		
		$('#fThesis').validate({
			rules: {				
<?php
	if ($jenjang!='S1') 
	{
		for ($i=1; $i<=$nPemb; $i++) 
			echo "pemb".$i.": { required:true, unik:true }, "; 
	}
?>				
				judul: { required:true }
			}
			,
			highlight: function(element) {
				if($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
					$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
				}				
        else $(element).closest('.form-group').removeClass('has-success').addClass('has-error');   								
			},
			unhighlight: function(element) {
				if($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
					$(element).closest('.form-group').removeClass('has-error').addClass('has-success');					
				}				
        else $(element).closest('.form-group').removeClass('has-error').addClass('has-success');								
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				if (element.hasClass('ext-select'))
					error.insertAfter(element.next('.select2-container'));
				else error.insertAfter(element);
			},
			submitHandler: function() {
				save_thesis();
			}			
		});
		
		function save_thesis() {
			var url,state;
			$.ajax({
				url : "<?php echo site_url('Home/save_thesis')?>",
				type: "POST",
				data: $('#fThesis').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					if (data.status) {
						window.location.href = "<?php echo site_url('home')?>";						
					} 
					else
					{
						$('#alert-thesis').show();
						setTimeout(function(){
							$("#errThesis").hide('hide');
						}, 2000);						
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
	});
	
	function exp_pdf(tipe,id) {
		window.open("<?php echo base_url('ujian/expPDF/')?>/"+tipe+"/"+id);		
	}	
		
	function exp_xls() {
		window.open("<?php echo base_url('kelas/expXLS/')?>");		
	}	
	
	$(document).on('change', '.fileRev', function() {
    var fileName = $(this).val().split('\\').pop();
		if (fileName) {
			$(this).closest('tr').find('button[id="btRevFile"]').text(fileName);
			$(this).closest('tr').find('button[id="btRevFile"]').removeClass('btn-danger').addClass('btn-primary');
		}
	});	
	$(document).on('click', '#btRevFile', function() {
		$(this).closest('tr').find('input[class="fileRev"]').trigger('click');		
		$(this).blur();
	});
</script>	
</head>