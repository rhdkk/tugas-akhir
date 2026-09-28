<?php 
	$smt = $this->session->userdata('id_smt_thesis');	
	$hak = $this->session->userdata('hak_thesis');	
	$ps = $this->session->userdata('id_ps_thesis');	
	$jur = $this->session->userdata('id_jur_thesis');	
	if (!empty($ps)) $key=$ps;
	elseif (!empty($jur)) $key=$jur;
?>
<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabelSah = $('#tbSah').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listPengesahan'); if (!empty($key)) echo "/".$key; ?>",
			"columns": [
				null,null,null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,				
			"columnDefs": [
				{ className:"text-center","targets":[-1] }
			],
			"initComplete": function(settings, json){ 
        var nSah = this.api().data().length;
        if (nSah>0) {
					$("#badge0").text(nSah);
					$("#badge0").show();
				} else $("#badge0").hide();
        var nTampil = this.api().page.info().recordsDisplay;
			}			
		});		
		tabelSah.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},null]
		});		
		
		var tabelAju = $('#tbAju').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listAjuji'); if (!empty($key)) echo "/".$key; ?>",
			"columns": [
				{"width":"40px"},null,null,null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,				
			"columnDefs": [
				{ className:"text-center","targets":[-1] }
			],
			"initComplete": function(settings, json){ 
        var nAju = this.api().data().length;
        if (nAju>0) {
					$("#badge1").text(nAju);
					$("#badge1").show();
				} else $("#badge1").hide();
        var nTampil = this.api().page.info().recordsDisplay;
			}			
		});		
		tabelAju.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},null]
		});	
		
		var tabelUji = $('#tbUji').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listUji'); if (!empty($key)) echo "/".$key; ?>",
			"columns": [
				{"width":"40px"},null,null,null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,				
			"columnDefs": [
				{ className:"text-center","targets":[-1] }
			],
			"initComplete": function(settings, json){ 
        var nUji = this.api().data().length;
        if (nUji>0) {
					$("#badge2").text(nUji);
					$("#badge2").show();
				} else $("#badge2").hide();
        var nTampil = this.api().page.info().recordsDisplay;
			}			
		});		
		tabelUji.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},null]
		});

		var tabelMhs = $('#tbMhs').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listMhs'); if (!empty($key)) echo "/".$key; ?>",
			"columns": [
				{"width":"40px"},null,null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,
			"columnDefs": [{ className:"text-center","targets":[-1] }]		
		});		
		tabelMhs.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},null]
		});
		
		$('.ext-select').select2();
		$('.select2-container').css("width","100%");
		$(".ext-select").change(function() {	
			$(this).valid();	
			if ($(this).hasClass('dosen')) {
				if ($.trim($(this).val())!="") {
					var idJA = $(this).val()+"-"+$(this).siblings('.input-group-btn').find('.btn-pemb').attr('id');			
					var idJB = $(this).val()+"-"+$(this).parents('.input-group').next().attr('id')+"-"+<?php echo $ps; ?>;			
					console.log(idJA+" & "+idJB);
					get_jml_bimbingan(idJA);
					get_jml_bimb_ps(idJB);
				}
			}				
		});
		
		function save_ujian() {
			var url,state;
			$.ajax({
				url : "<?php echo site_url('home/save_ujian')?>",
				type: "POST",
				data: $('#fUji').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_formUji').modal('hide');
					if (data.status) {
						$.toast({ text:"<b>Data Penjadwalan Ujian</b> sudah disimpan" });
						reload_tabelUji(1);
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
		
		$( "#fVerif" ).submit(function(event) {			
			save_verifikasi();
			event.preventDefault();
		});	

		function save_verifikasi(){
			var nS = $('input:checkbox').length;			
			$.ajax({
				url : "<?php echo site_url('home/save_verif')?>/"+nS,
				type: "POST",
				data: $('#fVerif').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_formVerif').modal('hide');
					if (data.status) {
						$.toast({ text:"<b>Data Verifikasi Berkas Ujian</b> sudah disimpan" });
						reload_tabelAju();
						reload_tabelUji(2);
					} 
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ alert(jqXHR.responseText); }
			});
		};
		
		$('#fUji').validate({
			rules: {
				tglUji: {required:true},
				jamUji1: {required:true},
				jamUji2: {required:true},
				ruangUji: {required:true}
			},
			highlight: function(element) {
				if($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
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
					error.insertAfter(element.parents().siblings('.alert-info'));										
				}
				else error.insertAfter(element);
			},
			submitHandler: function() {
				save_ujian();
			}			
		});
		
		$('#modal_formUji').on('hidden.bs.modal', function () {		
			$('#fUji').validate().resetForm();
			$('.form-comp').removeClass('has-error');			
			$('.form-comp').removeClass('has-success');				
		});
		
		$('#fSah').validate({
			rules: {
				noSK: {required:true},
				tglSK: {required:true}
			},
			highlight: function(element) {
				if($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
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
					error.insertAfter(element.parents().siblings('.alert-info'));										
				}
				else error.insertAfter(element);
			},
			submitHandler: function() {
				save_pengesahan();
			}			
		});
		
		$('#modal_formSah').on('hidden.bs.modal', function () {		
			$('#fSah').validate().resetForm();
			$('.form-comp').removeClass('has-error');			
			$('.form-comp').removeClass('has-success');				
			$('.alert-info').addClass('hide');			
		});
		
		function save_pengesahan() {
			var url,state;
			$.ajax({
				url : "<?php echo site_url('home/save_pengesahan')?>",
				type: "POST",
				data: $('#fSah').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_formSah').modal('hide');
					if (data.status) {
						$.toast({ text:"<b>Data Pengesahan Pembimbing dan Penguji</b> sudah disimpan" });
						reload_tabelSah();
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
		
		$('#fDosen').validate({
			rules: {
				pemb1: {required:true},
				noSKReg: {required:true},
				tglSKReg: {required:true},
			},
			highlight: function(element) {
				if($(element).hasClass('ext-select') && $(element).next('.select2-container').length) {
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
					error.insertAfter(element.parents().siblings('.alert-info'));										
				}
				else error.insertAfter(element);
			},
			submitHandler: function() {
				save_registrasi();
			}			
		});
		
		$('#modal_formReg').on('hidden.bs.modal', function () {		
			$('#fDosen').validate().resetForm();
			$('.form-comp').removeClass('has-error');			
			$('.form-comp').removeClass('has-success');				
			$('.alert-info').addClass('hide');			
		});
		
		function save_registrasi() {
			var url,state;
			$.ajax({
				url : "<?php echo site_url('home/save_registrasi')?>",
				type: "POST",
				data: $('#fDosen').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_formReg').modal('hide');
					if (data.status) {
						$.toast({ text:"<b>Data Tim Pembimbing dan Penguji</b> sudah disimpan" });
						reload_tabelSah();
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
		
		$(".btn-pemb").click(function() {	
			if ($(this).parents().siblings(".alert-info").hasClass('hide'))
				$(this).parents().siblings(".alert-info").removeClass('hide');
			else $(this).parents().siblings(".alert-info").addClass('hide');
		});
		
		$("#btUji").click(function(){        
      $("#fUji").submit();
    });
		
		$("#btSah").click(function(){        
      $("#fSah").submit();
    });
		
		$("#btDosen").click(function(){        
      $("#fDosen").submit();
    });
		
		function reload_tabelUji(jns){
      tabelUji.api().ajax.reload(null,false); 
			if (jns==1) var n = $("#badge2").text()-1;
			else var n = $("#badge2").text()+1;
			if (n>0) {
				$("#badge2").text(n);
				$("#badge2").show();
			} else $("#badge2").hide();
    }
		
		function reload_tabelAju(){
      tabelAju.api().ajax.reload(null,false); 
			var n = $("#badge1").text()-1;
			if (n>0) {
				$("#badge1").text(n);
				$("#badge1").show();
			} else $("#badge1").hide();
    }
		
		function reload_tabelSah(){
      tabelSah.api().ajax.reload(null,false); 
			var n = $("#badge0").text()-1;
			if (n>0) {
				$("#badge0").text(n);
				$("#badge0").show();
			} else $("#badge0").hide();
    }
		
		$('#tglSKReg').datepicker({
			autoclose: true
		});
		
		$('#tglSK').datepicker({
			autoclose: true
		});
		
		$('#tglUji').datepicker({
			autoclose: true
		});
		
		$('#jamUji1').clockpicker({
			autoclose: true
		});
		
		$('#jamUji2').clockpicker({
			autoclose: true
		});
		
		$("#btn-pdf").click(function() {						
			exp_pdf();
		});	
		
		$("#btn-excel").click(function() {						
			exp_xls();
		});	
	});
	
	function det_mhs(id) {
		$('#fMhs')[0].reset();	
		$.ajax({
			url : "<?php echo site_url('home/ajax_detMhs/')?>",
			type: "GET",
			data: {
				nim: id
			},
			dataType: "JSON",
			success: function(response) {
				/*console.log(JSON.stringify(response));*/
				$('#nimMhs').val(response.response[0].nim);								
				$('#idAjuan').val(response.response[0].id_ajuan);								
				$('#lbNmMhs').text(response.response[0].nm_mhs);				
				$('#lbNimMhs').text("NIM. "+response.response[0].nim);
				
				var tstr = "";
				for (var i=1; i<=np1; ++i) {	
					tstr += "<div style='margin-top:8px'>";
					if (strata=="S3") {
						if (i==1) tstr += "<b>Promotor</b><br/><h5>"+ar_p1[i]+"</h5>";  
						else tstr += "<b>Co-Promotor "+(i-1)+"</b><br/><h5>"+ar_p1[i]+"</h5>";  
					}
					else {
						tstr += "<b>Pembimbing "+i+"</b><br/><h5>"+ar_p1[i]+"</h5>";  
					}			
					tstr += "</div>";
				}		
				$("#txPembUji").html(tstr);
				tstr = "";
				for (var i=1; i<=np2; ++i) {	
					tstr += "<div style='margin-top:8px'>";
					tstr += "<b>Penguji "+i+"</b><br/><h5>"+ar_p2[i]+"</h5>";  					
					tstr += "</div>";
				}		
				$("#txPengUji").html(tstr);
				
				$('#fUji').show();			
				$('#btUji').show();			
				$('.modal-title').text("Penjadwalan Ujian"); 
				$('#modal_formUji').modal('show');				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				/*alert('Error get data from ajax');*/
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function plot_uji(id) {
		$('#fUji')[0].reset();	
		$.ajax({
			url : "<?php echo site_url('home/ajax_detUji/')?>",
			type: "GET",
			data: {
				nim: id
			},
			dataType: "JSON",
			success: function(response) {
				/*console.log(JSON.stringify(response));*/
				$('#nimUji').val(response.response[0].nim);								
				$('#idAjuan').val(response.response[0].id_ajuan);								
				$('#fnUji').val(response.response[0].filename);								
				$('#nmUji').text(response.response[0].nm_mhs);				
				$('#nimDiuji').text("NIM. "+response.response[0].nim);
				$('#nmPS').text(response.response[0].nm_ps);
				$('#thpUji').text(response.response[0].nm_tuji);				
								
				var np1 = response.response[0].p1;				
				var np2 = response.response[0].p2;								
				var strata = response.response[0].jenjang;
				
				var i1=0; i2=0; ar_p1=[], ar_p2=[]; nm="", gelar1="", gelar2="";
				for (var i=0; i<response.response.length; ++i) {	
					nm = ""; gelar1=$.trim(response.response[i].gelar1); gelar2=$.trim(response.response[i].gelar2);
					if (gelar1!="") nm += gelar1+" ";
					nm += response.response[i].nm_dosen;
					if (gelar2!="") nm += ", "+gelar2;
					if (response.response[i].jab_dosen==1) { ++i1; ar_p1[i1]=nm; }
					else if (response.response[i].jab_dosen==2) { ++i2; ar_p2[i2]=nm; }
				} 
				
				var tstr = "";
				for (var i=1; i<=np1; ++i) {	
					tstr += "<div style='margin-top:8px'>";
					if (strata=="S3") {
						if (i==1) tstr += "<b>Promotor</b><br/><h5>"+ar_p1[i]+"</h5>";  
						else tstr += "<b>Co-Promotor "+(i-1)+"</b><br/><h5>"+ar_p1[i]+"</h5>";  
					}
					else {
						tstr += "<b>Pembimbing "+i+"</b><br/><h5>"+ar_p1[i]+"</h5>";  
					}			
					tstr += "</div>";
				}		
				$("#txPembUji").html(tstr);
				tstr = "";
				for (var i=1; i<=np2; ++i) {	
					tstr += "<div style='margin-top:8px'>";
					tstr += "<b>Penguji "+i+"</b><br/><h5>"+ar_p2[i]+"</h5>";  					
					tstr += "</div>";
				}		
				$("#txPengUji").html(tstr);
				
				$('#fUji').show();			
				$('#btUji').show();			
				$('.modal-title').text("Penjadwalan Ujian"); 
				$('#modal_formUji').modal('show');				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				/*alert('Error get data from ajax');*/
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function verf_file(id) {
		$.ajax({
			url : "<?php echo site_url('home/ajax_detAjuji/')?>",
			type: "GET",
			data: {
				nim: id
			},
			dataType: "JSON",
			success: function(response){
				/*console.log(JSON.stringify(response));*/
				$('#idAju').val(response.response[0].id_ajuan);								
				$('#nim').val(response.response[0].nim);								
				$('#lbNama').text(response.response[0].nm_mhs);				
				$('#lbKetMhs').text("NIM. "+response.response[0].nim);				
				$('#lbNmUji').text(response.response[0].nm_tuji);
				$('#lbProdi').text("Program Studi "+response.response[0].nm_ps);
				var chk								
				var strata = response.response[0].jenjang;
				
				$("#tbVerif tbody").empty();
				var tData;
				for (var i=0; i<response.response.length; ++i) {
					tData = response.response[i];
					if (tData.ket==2) chk="checked"; else chk="";
					$("#tbVerif tbody").append("<tr><td><a href='<?php echo base_url() ."upload/"?>"+tData.filename+"' onclick='window.open(this.href, \"_blank\", \"width=600,height=800\"); return false;'>"+tData.nm_berkas+"</a></td><td><input name='idSA"+(i+1)+"' id='idSA"+(i+1)+"' type='hidden' value='"+tData.id_syarat+"'/><input name='idBM"+(i+1)+"' id='idBM"+(i+1)+"' type='hidden' value='"+tData.id_berma+"'/><input type='checkbox' name='berkas"+(i+1)+"' "+chk+" data-toggle='toggle' data-on='YES' data-off='NO' data-onstyle='success' data-offstyle='danger' data-size='mini'></td></tr>");
				}
				
				$('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();				
				$('#modal_formVerif').modal('show');				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				/*alert('Error get data from ajax');*/
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function set_tim(id) {
		$('#fDosen')[0].reset();	
		$.ajax({
			url : "<?php echo site_url('home/ajax_detReg/')?>",
			type: "GET",
			data: {
				nim: id
			},
			dataType: "JSON",
			success: function(response)
			{
				/*console.log(JSON.stringify(response.response.judul_ta));*/
				$('#nimReg').val(response.response.nim);								
				$('#judulReg').text(response.response.judul_ta);				
				$('#nPemb').val(response.response.p1);				
				$('#nPeng').val(response.response.p2);				
				var np1 = response.response.p1;				
				var np2 = response.response.p2;				
				
				if (np2>1) {
					$("#kwPeng").children(".newAddedPeng").remove();
					for (var i=2; i<=np2; ++i) {					
						$(".ext-select").select2("destroy");
						newType = $(".copyPeng").first().clone().addClass("newAddedPeng");						
						newType.appendTo("#kwPeng");			
						newType.attr('id','copyPeng'+i);			
						newType.find("#lbPeng1").attr('id','lbPeng'+i).attr('for','peng'+i).text("Penguji "+i);
						newType.find("#idPeng1").attr('id','idPeng'+i).attr('name','idPeng'+i);						
						newType.find("#peng1").attr('id','peng'+i).attr('name','peng'+i);						
						newType.find("#infoPeng1").attr('id','infoPeng'+i).attr('name','infoPeng'+i);																															
						$(".newAdded").show();					
						$(".ext-select").select2();			
						$('.select2-container').css("width","100%");
					}
				}
				
				$('#fDosen').show();			
				$('#btDosen').show();			
				$('.modal-title').text("Tim Dosen"); 
				$('#modal_formReg').modal('show');				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				/*alert('Error get data from ajax');*/
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function set_sk(id,no) {
		$('#fSah')[0].reset();	
		$.ajax({
			url : "<?php echo site_url('home/ajax_detAjuan/')?>",
			type: "GET",
			data: {
				nim: id,
				urut: no
			},
			dataType: "JSON",
			success: function(response)
			{
				/*console.log(JSON.stringify(response));*/
				$('#nimSah').val(response.response[0].nim);								
				$('#judul').text(response.response[0].judul_ta);				
				$('#nmMhs0').text(response.response[0].nm_mhs);				
				$('#nimMhs0').text("NIM. "+response.response[0].nim);				
				var np1 = response.response[0].p1;				
				var np2 = response.response[0].p2;								
				var strata = response.response[0].jenjang;
				
				var i1=0; i2=0; ar_p1=[], ar_p2=[]; nm="", gelar1="", gelar2="";
				for (var i=0; i<response.response.length; ++i) {	
					nm = ""; gelar1=$.trim(response.response[i].gelar1); gelar2=$.trim(response.response[i].gelar2);
					if (gelar1!="") nm += gelar1+" ";
					nm += response.response[i].nm_dosen;
					if (gelar2!="") nm += ", "+gelar2;
					if (response.response[i].jab_dosen==1) { ++i1; ar_p1[i1]=nm; }
					else if (response.response[i].jab_dosen==2) { ++i2; ar_p2[i2]=nm; }
				} 
				
				var tstr = "";
				for (var i=1; i<=np1; ++i) {	
					tstr += "<div style='margin-top:8px'>";
					if (strata=="S3") {
						if (i==1) tstr += "<b>Promotor</b><br/><h5>"+ar_p1[i]+"</h5>";  
						else tstr += "<b>Co-Promotor "+(i-1)+"</b><br/><h5>"+ar_p1[i]+"</h5>";  
					}
					else {
						tstr += "<b>Pembimbing "+i+"</b><br/><h5>"+ar_p1[i]+"</h5>";  
					}			
					tstr += "</div>";
				}		
				$("#txPemb").html(tstr);
				tstr = "";
				for (var i=1; i<=np2; ++i) {	
					tstr += "<div style='margin-top:8px'>";
					tstr += "<b>Penguji "+i+"</b><br/><h5>"+ar_p2[i]+"</h5>";  					
					tstr += "</div>";
				}		
				$("#txPeng").html(tstr);
				
				$('#fSah').show();			
				$('#btSah').show();			
				$('.modal-title').text("Detil Pengesahan"); 
				$('#modal_formSah').modal('show');				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				/*alert('Error get data from ajax');*/
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function get_jml_bimbingan(id) {
		$.ajax({
			url : "<?php echo site_url('dosen/ajax_jmlBimbingan/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('#'+data.data.comp).text(data.data.jml);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function get_jml_bimb_ps(id) {
		$.ajax({
			url : "<?php echo site_url('dosen/ajax_jmlBimbPS/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				/*alert(JSON.stringify(data));*/				
				str="";
				if (data.data.jml1==0 && data.data.jml2==0) str = "Belum ada data mahasiswa bimbingan";
				else {
					str = "<ul><li><b>"+data.data.jml1+"</b> mahasiswa di Program Studi ini</li>";
					str += "<li><b>"+data.data.jml2+"</b> mahasiswa di Program Studi yang lain</li></ul>";
				}
				$('#'+data.data.comp).html(str);				
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function exp_pdf() {
		window.open("<?php echo base_url('kelas/expPDF/')?>");		
	}	
	function exp_xls() {
		window.open("<?php echo base_url('kelas/expXLS/')?>");		
	}	
</script>	
</head>