<?php 
	$id = $this->session->userdata('id_user_thesis');	
	$smt = $this->session->userdata('id_smt_thesis');	
	$hak = $this->session->userdata('hak_thesis');	
	$ps = $this->session->userdata('id_ps_thesis');	
?>
<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabelDP = $('#tbDP').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listAjuan'); if (!empty($ps)) echo "/".$ps; ?>",
			"columns": [
				null,null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,				
			"columnDefs": [
				{ className:"text-center","targets":[-1] }
			],
			"initComplete": function(settings, json){ 
        var nAjuan = this.api().data().length;
        if (nAjuan>0) {
					$("#badge0").text(nAjuan);
					$("#badge0").show();
				} else $("#badge0").hide();
        var nTampil = this.api().page.info().recordsDisplay;
			}			
		});		
		tabelDP.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},null]
		});
		
		var tabelBD = $('#tbBD').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listBimbDsn'); echo "/".$id; ?>",
			"columns": [
				{"width":"80px"},null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,				
			"columnDefs": [
				{ className:"text-center","targets":[0,-1] }
			],
			"initComplete": function(settings, json){ 
        var nBimb = this.api().data().length;
        if (nBimb>0) {
					$("#badge1").text(nBimb);
					$("#badge1").show();
				} else $("#badge1").hide();
        var nTampilBimb = this.api().page.info().recordsDisplay;
			}			
		});		
		tabelBD.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},null]
		});		

		var tabelPU = $('#tbPU').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listPenilaian'); echo "/".$id; ?>",
			"columns": [
				{"width":"80px"},null,null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,				
			"columnDefs": [
				{ className:"text-center","targets":[0,-1] }
			],
			"initComplete": function(settings, json){ 
        var nNilai = this.api().data().length;
        if (nNilai>0) {
					$("#badge2").text(nNilai);
					$("#badge2").show();
				} else $("#badge2").hide();
        var nTampilNilai = this.api().page.info().recordsDisplay;
			}			
		});		
		tabelPU.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},null]
		});	
		
		var tabelRU = $('#tbRU').dataTable({
			"ajax": "<?php echo site_url('home/ajax_listRevisi'); echo "/".$id; ?>",
			"columns": [
				{"width":"80px"},null,null,null,{"width":"20px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],	
			"ordering": false,				
			"columnDefs": [
				{ className:"text-center","targets":[0,-1] }
			],
			"initComplete": function(settings, json){ 
        var nRev = this.api().data().length;
        if (nRev>0) {
					$("#badge3").text(nRev);
					$("#badge3").show();
				} else $("#badge3").hide();
        var nTampilRev = this.api().page.info().recordsDisplay;
			}			
		});		
		tabelRU.columnFilter({
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
		
		$.validator.addClassRules("skor", {
			required: true,
			number: true,
			range: [0,100]
		})
						
		$('#fPU').validate({
			highlight: function(element) {
				$(element).closest('tr').removeClass('has-success').addClass('has-error');   								
			},
			unhighlight: function(element) {
				$(element).closest('tr').removeClass('has-error').addClass('has-success');								
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function(error, element) {
				var target = element.closest('tr').find('td:first');
				error.appendTo(target);
			},
			submitHandler: function() {
				save_nilai();
			}			
		});

<?php if ($hak!=5) { ?>		
		$('#fDosen').validate({
			rules: {				
<?php
	for ($i=1; $i<=$nPemb; $i++) 
		echo "pemb".$i.": { required:true, unik:true }, ";		
	for ($i=1; $i<=$nPeng; $i++) 
		echo "peng".$i.": { required:true, unik:true }, ";			
?>				
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
				save_dosen();
			}			
		});
<?php } ?>

		$("#viewBimb").click(function(){        
			$(this).blur();
      if ($("#histBM").hasClass('hide')) {
				$("#histBM").removeClass('hide');										
				$(this).html("Hide History &nbsp;&nbsp; <span class='glyphicon glyphicon-chevron-up'></span>");
				$(this).addClass('btn-danger').removeClass('btn-primary');
			}
			else {
				$("#histBM").addClass('hide');
				$(this).html("Show History &nbsp;&nbsp; <span class='glyphicon glyphicon-chevron-down'></span>");
				$(this).addClass('btn-primary').removeClass('btn-danger');
			}
    });
		
		$("#viewRev").click(function(){        
			$(this).blur();
      if ($("#histRU").hasClass('hide')) {
				$("#histRU").removeClass('hide');										
				$(this).html("Hide History &nbsp;&nbsp; <span class='glyphicon glyphicon-chevron-up'></span>");
				$(this).addClass('btn-danger').removeClass('btn-primary');
			}
			else {
				$("#histRU").addClass('hide');
				$(this).html("Show History &nbsp;&nbsp; <span class='glyphicon glyphicon-chevron-down'></span>");
				$(this).addClass('btn-primary').removeClass('btn-danger');
			}
    });
		
		$('#modal_formPemb').on('hidden.bs.modal', function () {		
			$('#fDosen').validate().resetForm();
			$('.form-comp').removeClass('has-error');			
			$('.form-comp').removeClass('has-success');				
			$('.alert-info').addClass('hide');			
		});
		
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			$($.fn.dataTable.tables(true)).DataTable()
				 .columns.adjust()
				 .responsive.recalc();
		});

		
		function save_dosen() {
			var url,state;
			$.ajax({
				url : "<?php echo site_url('Home/save_dosen')?>",
				type: "POST",
				data: $('#fDosen').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_formPemb').modal('hide');
					if (data.status) {
						$.toast({ text:"<b>Data Penetapan Pembimbing dan Penguji</b> sudah disimpan" });
						reload_tabelDP();
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
		
		function save_validasi() {
			$.ajax({
				url : "<?php echo site_url('bimbingan/validasi')?>",
				type: "POST",
				data: $('#fBD').serialize(),
				dataType: "JSON",
				success: function(data) {
					$('#modal_formBD').modal('hide');
					$.toast({ text:"Validasi Bimbingan sudah disimpan" });
					reload_tabelBD();				
				},
				error: function (jqXHR, textStatus, errorThrown){ 
					alert('Error Simpan Validasi: '+errorThrown); }
			});
		}
		
		function save_nilai() {
			var url,state;
			$.ajax({
				url : "<?php echo site_url('Home/save_nilai')?>",
				type: "POST",
				data: $('#fPU').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					$('#modal_formPU').modal('hide');
					if (data.status) {
						$.toast({ text:"<b>Data Penilaian Ujian</b> sudah disimpan" });
						reload_tabelPU();
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
		
		function save_validasiRev() {
			$.ajax({
				url : "<?php echo site_url('revisi/validasi')?>",
				type: "POST",
				data: $('#fRU').serialize(),
				dataType: "JSON",
				success: function(data) {
					$('#modal_formRU').modal('hide');
					$.toast({ text:"Validasi Revisi Ujian sudah disimpan" });
					reload_tabelRU();				
				},
				error: function (jqXHR, textStatus, errorThrown){ 
					alert('Error Simpan Validasi'); }
			});
		}
		
		$(".btn-pemb").click(function() {	
			if ($(this).parents('.input-group').next().hasClass('hide'))
				$(this).parents('.input-group').next().removeClass('hide');
			else $(this).parents('.input-group').next().addClass('hide');
		});
			
		$("#btDosen").click(function(){        
      $("#fDosen").submit();
    });
		
		$("#btNilai").click(function(){        
      $("#fPU").submit();
    });

		function reload_tabelDP(){
      tabelDP.api().ajax.reload(null,false); 
			var n = $("#badge0").text()-1;
			if (n>0) {
				$("#badge0").text(n);
				$("#badge0").show();
			} else $("#badge0").hide();
    }
		
		function reload_tabelBD(){
      tabelBD.api().ajax.reload(null,false); 
			var n = $("#badge1").text()-1;
			if (n>0) {
				$("#badge1").text(n);
				$("#badge1").show();
			} else $("#badge1").hide();
    }
		
		function reload_tabelPU(){
      tabelPU.api().ajax.reload(null,false); 
			var n = $("#badge2").text()-1;
			if (n>0) {
				$("#badge2").text(n);
				$("#badge2").show();
			} else $("#badge2").hide();
    }
		
		function reload_tabelRU(){
      tabelRU.api().ajax.reload(null,false); 
			var n = $("#badge3").text()-1;
			if (n>0) {
				$("#badge3").text(n);
				$("#badge3").show();
			} else $("#badge3").hide();
    }
		
		$("#btn-pdf").click(function() {						
			exp_pdf();
		});	
		
		$("#btn-excel").click(function() {						
			exp_xls();
		});	
		
		$("#btThesis").click(function(){        
      $("#fThesis").submit();
    });
		
		$("#btYesBD").click(function(){        
      $("#accBimb").val(2);
			save_validasi();
    });
		
		$("#btNoBD").click(function(){        
      $("#accBimb").val(3);
			save_validasi();
    });
		
		$("#btYesRU").click(function(){        
      $("#accRev").val(2);
			save_validasiRev();
    });
		
		$("#btNoRU").click(function(){        
      $("#accRev").val(3);
			save_validasiRev();
    });
		
	});
	
	$(document).on("input", ".skor", function() {
		var totalNilai = 0;
		var nilaiPemb = parseFloat($("#skorPemb").val());
		var nP1 = 0;
		var nP2 = parseFloat($('#nilaiPeng').val());
		var nilaiP1 = 0;
		var nilaiP2 = 0;
		
		$(".skor").each(function() {
			var index = $(this).attr('name').replace('nilai', '');
			var nilai = parseFloat($(this).val()) * (parseFloat($('#pers'+index).val()/100));
			if (!isNaN(nilai)) {
				totalNilai += nilai;
			}
		});
		
		
		if (totalNilai>0 && totalNilai<=100) {
			$("#totNilai").val(totalNilai.toFixed(1));			
			if (nilaiPemb==0) nP1 = totalNilai;
			else nP1 = (totalNilai+nilaiPemb)/2;
			
		} else {
			$("#totNilai").val("");
			nP1 = nilaiPemb;
		}
		$("#nilaiPemb").val(nP1.toFixed(1));
		
		var nilaiAkhir = ((nP1*parseFloat($('#persPemb').val()))/100)+((nP2*parseFloat($('#persPeng').val()))/100);
		$("#tNA").val(nilaiAkhir.toFixed(1));
		$("#NA").val(nilaiAkhir.toFixed(1));
	})
		
	function set_rev(idDP,idR,tipeR) {
		$('#fRU')[0].reset();	
		$.ajax({
			url : "<?php echo site_url('home/ajax_listRev/')?>",
			type: "GET",
			data: {
				idDP: idDP,
				idR: idR,
				tipeR: tipeR
			},
			dataType: "JSON",
			success: function(data)
			{
				$('#idRev').val(idR);												
				$('#idDPRev').val(idDP);
				$('#tipeRev').val(tipeR);
				$('#idDURev').val(data.data[0].id_det_uji);								
				$('#nimRev').val(data.data[0].nim);								
				$('#lbNamaRev').text(data.data[0].nm_mhs+" (NIM. "+data.data[0].nim+")");								
				$('#lbPSRev').text(data.data[0].nm_ps);								
				$('#lbTglRev').text(data.data[0].tgl_rev);								
				$('#lbUjiRev').html(data.data[0].nm_tuji);								
				$('#lbTglUjiRev').html(data.data[0].tgl_uji);								
				$('#lbKetRev').html(data.data[0].rev_uji);
				$('#accUji').val(data.data[0].acc_uji);		

				$('#lbTanyaRev').text("Apakah data Pengajuan Revisi/Saran di atas valid?");
				$('#titleRev').text("Validasi Revisi Ujian");
				
				$("#tbHR tbody").empty();
				if (data.data.length>1) {
					for (var i=1; i<data.data.length; ++i) 
						$("#tbHR tbody").append("<tr><td>"+data.data[i].rev_uji+"</td><td>"+data.data[i].acc_rev+"</td></tr>");
				}
				else {
					$("#tbHR tbody").append("<tr><td colspan='2' align='center'>Belum ada data</td></tr>");
				}
				
				$('#modal_formRU').modal('show');				
				if (!$("#histRU").hasClass('hide')) {
					$("#histRU").addClass('hide');
					$("#viewRev").html("Show History <span class='glyphicon glyphicon-chevron-down'></span>");
					$("#viewRev").addClass('btn-primary').removeClass('btn-danger');
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				/*alert('Error get data from ajax');*/
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function set_nilai(id,jab,urut) {
		$('#fPU')[0].reset();	
		$.ajax({
			url : "<?php echo site_url('home/ajax_nilaiUji/')?>",
			type: "GET",
			data: {
				idDetUji: id
			},
			dataType: "JSON",
			success: function(response)
			{
				var filename = response.data[0].filename_uji;
				var txFile = "<a class='btn btn-xs btn-primary' href='<?php echo base_url() ."upload/"?>"+filename+"' onclick='window.open(this.href, \"_blank\", \"width=600,height=800\"); return false;'>View Naskah</a>";
				$("#lbMhs").html("<b>" + response.data[0].nm_mhs + "</b><br/>NIM. " + response.data[0].nim);
				var txJenjang = "";
				if (response.data[0].jenjang=="S3") txJenjang="Doktor";
				else if (response.data[0].jenjang=="S2") txJenjang="Magister";
				else if (response.data[0].jenjang=="S1") txJenjang="Sarjana";
				else txJenjang="Profesi";
				$("#lbUjian").html("<b>" + response.data[0].nm_tuji + "</b><br/>Prodi. " + txJenjang + " " + response.data[0].nm_ps + " ");
				$("#lbFile").html(txFile);
				
				$("#idDU").val(id);
				$("#idU").val(response.data[0].id_uji);
				$("#idDPU").val(response.data[0].id_dospem);
				$("#nimPU").val(response.data[0].nim);
				$("#jmlP3").val(response.data[0].jml3);
				$("#jmlP4").val(response.data[0].jml4);
				$("#nTim").val(response.data[0].n_tim);
				$("#nPU").val(response.data.length);
				$("#rowTbNilai").empty();
				var capt = "";
				/* Jika PEMBIMBING UTAMA */
				if (jab==1 && urut==1) {					
					var jml3 = parseInt(response.data[0].jml3);
					var jml4 = parseInt(response.data[0].jml4);
					var tim = parseInt(response.data[0].n_tim);				
					if ((jml3+jml4)==(tim-1)) {
						capt="";
						$('#btNilai').show();
					}
					else {
						capt="readonly";
						$('#btNilai').hide();
					}
				}
				
				for (var i=0; i<response.data.length; ++i) {
					var tx1 = "<input class='form-control skor' name='nilai" + i + "' id='nilai" + i + "' type='text' oninput=\"this.value = this.value.replace(/[^0-9]/g,'')\" value='0' style='height:25px' " + capt + "/>";
					var tx2 = "<input name='krit" + i + "' id='krit" + i + "' value='" + response.data[i].id_kriteria + "' type='hidden'/>";
					var tx3 = "<input class='persen' name='pers" + i + "' id='pers" + i + "' value='" + response.data[i].pers_nilai + "' type='hidden'/>";
					var newRow = "<tr><td>" + response.data[i].nm_kriteria + "<br/><div class='errNilai'></div></td><td>" + tx1 + tx2 + tx3 + "</td></tr>";
					$("#rowTbNilai").append(newRow);
				}
				var tx = "<input class='form-control' name='totNilai' id='totNilai' type='text' value='0' readonly style='font-weight:bold; height:25px'/>";
				var newRow = "<tr><th>Total Nilai</th><td>" + tx + "</td></tr>";
				$("#rowTbNilai").append(newRow);

				/* jika PEMBIMBING UTAMA */
				if (jab==1 && urut==1) {
					var rtPemb = parseFloat(response.data[0].rata_pemb);
					if (rtPemb<0) rtPemb=0;
					var rtPeng = parseFloat(response.data[0].rata_peng);
					if (rtPeng<0) rtPeng=0;
					tx = "<input class='form-control' name='nilaiPemb' id='nilaiPemb' type='text' value='" + rtPemb + "' style='height:25px' readonly/>";
					var txP = "<input name='skorPemb' id='skorPemb' value='" + rtPemb + "' type='hidden'/><input class='persen' name='persPemb' id='persPemb' value='" + response.data[0].nilai_p1 + "' type='hidden'/>";
					newRow = "<tr><td>Nilai Pembimbing</td><td>" + tx + txP + "</td></tr>";
					$("#rowTbNilai").append(newRow);
					tx = "<input class='form-control' name='nilaiPeng' id='nilaiPeng' type='text' value='" + rtPeng + "' style='height:25px' readonly/>";
					txP = "<input class='persen' name='persPeng' id='persPeng' value='" + response.data[0].nilai_p2 + "' type='hidden'/>";
					newRow = "<tr><td>Nilai Penguji</td><td>" + tx + txP + "</td></tr>";
					$("#rowTbNilai").append(newRow);
					tx = "<input class='form-control' name='tNA' id='tNA' type='text' value='0' style='font-weight:bold; height:25px' readonly/><input name='NA' id='NA' type='hidden'/>";
					newRow = "<tr><th>Nilai Akhir</th><td>" + tx + "</td></tr>";
					$("#rowTbNilai").append(newRow);
				}
				
				$('#fPU').show();			
				$('.modal-title').text("Penilaian Ujian"); 
				$('#modal_formPU').modal('show');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				/*alert('Error get data from ajax');*/
				alert(jqXHR.responseText); 
			}
		});
	}
		
	function set_bimb(idDP,idB) {
		$('#fBD')[0].reset();	
		$.ajax({
			url : "<?php echo site_url('home/ajax_listBimb/')?>",
			type: "GET",
			data: {
				idDP: idDP,
				idB: idB
			},
			dataType: "JSON",
			success: function(data)
			{
				$('#idBimb').val(idB);								
				$('#idDP').val(idDP);								
				$('#nimBD').val(data.data[0].nim);								
				$('#nPb').val(data.data[0].p1);								
				$('#idPg').val(data.data[0].id_progress);								
				$('#lbNama').text(data.data[0].nm_mhs);								
				$('#lbKetMhs').text(data.data[0].ket_mhs);					
				$('#lbTglBimb').text(data.data[0].tgl_bimb);								
				$('#lbKetBimb').html(data.data[0].ket_bimb);								
				$('#jnsBimb').val(data.data[0].jns_bimb);								
				$('#accUji').val(data.data[0].acc_uji);					
				
				if (data.data[0].jns_bimb==2) {
					$('#lbTanya').text("Apakah Permohonan Ujian disetujui?");
					$('#titleBimb').text("Persetujuan Ujian");
					$('#titleBimb').addClass("text-primary");
					$('#lbKet').hide();
				}
				else if (data.data[0].jns_bimb==3) {
					$('#judulNew').val(data.data[0].judul_new);
					$('#lbTanya').text("Apakah Perubahan Judul disetujui?");
					$('#titleBimb').text("Konfirmasi Perubahan Judul");
					$('#titleBimb').addClass("text-primary");
					$('#lbKet').hide();
				}
				else {
					$('#lbTanya').text("Apakah data Bimbingan di atas valid?");
					$('#titleBimb').text("Validasi Bimbingan");
				}
				
				$("#tbHB tbody").empty();
				if (data.data.length>1) {
					for (var i=1; i<data.data.length; ++i) 
						$("#tbHB tbody").append("<tr><td>"+data.data[i].tgl_bimb+"</td><td>"+data.data[i].ket_bimb+"</td><td>"+data.data[i].acc_bimb+"</td></tr>");
				}
				else {
					$("#tbHB tbody").append("<tr><td colspan='2' align='center'>Belum ada data</td></tr>");
				}
				
				$('#modal_formBD').modal('show');				
				if (!$("#histBM").hasClass('hide')) {
					$("#histBM").addClass('hide');
					$("#viewBimb").html("Show History <span class='glyphicon glyphicon-chevron-down'></span>");
					$("#viewBimb").addClass('btn-primary').removeClass('btn-danger');
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				/*alert('Error get data from ajax');*/
				alert(jqXHR.responseText); 
			}
		});
	}
	
	function set_dosen(id,no) {
		$('#fDosen')[0].reset();	
		$('.ext-select').val(null).trigger('change');
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
				/*console.log(JSON.stringify(response));		
				$(".ext-select").select2("destroy");*/
				$('#nim').val(response.response[0].nim);				
				$('#nPemb').val(response.response[0].p1);				
				$('#nPeng').val(response.response[0].p2);				
				$('#judul').text(response.response[0].judul_ta);				
				for (var i=0; i<response.response.length; ++i) {	
					var idx=i+1;
					if (response.response[i].id_dosen)
					{
						$('#idPemb'+idx).val(response.response[i].id_dosen);
						$('#idDP'+idx).val(response.response[i].id_dospem);
						$('#pemb'+idx).val(response.response[i].id_dosen);
						$('#pemb'+idx).trigger('change');					
					}
				} 
				
				$('#fDosen').show();			
				$('#btAjuan').show();			
				$('.modal-title').text("Detil Pengajuan Pembimbing"); 
				$('#modal_formPemb').modal('show');				
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
				/*alert(JSON.stringify(data));*/
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
<style>
	.textarea-group {
		border-radius: 4px;
		overflow: hidden;
	}

	.textarea-block {
		border: 1px solid #ccc;
		border-top-width: 0;
		border-radius: 0;
		margin: 0;
		background-color: #fff;
		padding: 6px 12px;
	}

	.textarea-group .textarea-block:first-child {
		border-top-width: 1px;
		border-top-left-radius: 4px;
		border-top-right-radius: 4px;
	}

	.textarea-group .textarea-block:last-child {
		border-bottom-left-radius: 4px;
		border-bottom-right-radius: 4px;
	}

	.textarea-block:focus {
		outline: none;
		border-color: #66afe9;		
	}

	.textarea-block::placeholder {
		color: #999;
		opacity: 1;
	}

	.textarea-group textarea + textarea {
		
	}
</style>
</head>