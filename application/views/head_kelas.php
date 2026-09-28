<?php 
	$smt = $this->session->userdata('id_smt_thesis');	
	$hak = $this->session->userdata('hak_thesis');	
?>
<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbKelas').dataTable({
			"ajax": "<?php echo site_url('kelas/ajax_list'); if (!empty($ps)) echo "/".$ps; ?>",
			"columns": [
				null,{"width":"10px"},{"width":"70px"},null,{"width":"200px"},{"width":"20px"},{"width":"80px"}
			], 
			"lengthMenu": [[10, 50, -1], [10, 50, "All"]],
			"order": [[0,"asc"],[1,"asc"]],
			"columnDefs": [
				{ "targets": [3,4,-1],"orderable": false },
				{ className:"text-center","targets":[1,5,-1] }
			]
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},null]
		});		
		
		$("#btn-pdf").click(function() {						
			exp_pdf();
		});	
		
		$("#btn-excel").click(function() {						
			exp_xls();
		});	
		
		$("#btThesis").click(function(){        
      $("#fThesis").submit();
    });
		
		/*$("#fThesis").submit(function(event) {
			$(".error-form-thesis").empty().hide();			
			$(".error-form").empty().hide();			
			
			$('.dosen').each(function() {
				$(this).rules("add", 
				{
					required: true,
					messages: {required: "<strong>Dosen Pembimbing</strong> tidak boleh kosong"}
				});
			});
			event.preventDefault();
		});*/
		
		$('#fThesis').validate({
			rules: {
				judul: { required:true }				
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
				save_thesis();
			}			
		});
		
		function save_thesis() 
		{
			var url,state;
			$.ajax({
				url : "<?php echo site_url('kelas/save_thesis')?>",
				type: "POST",
				data: $('#fThesis').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					if (data.db===0) {
						if (!data.status0 || !data.status1 || !data.status2) {
							if (!data.status0) {
								for (var i=0; i<data.xPS.length; ++i) {
									var txt = data.xPS[i].split('|');
									if (data.status1 && data.status2) $("#errKelas"+txt[0]).append('Plot Jadwal untuk Program Studi <b>'+txt[1]+'</b> di Hari dan Sesi ini <b>SUDAH MAKSIMAL</b> ('+txt[2]+' plot Per Sesi)');
									else $("#errKelas"+txt[0]).append('<li>Plot Jadwal untuk Program Studi <b>'+txt[1]+'</b> di Hari dan Sesi ini <b>SUDAH MAKSIMAL</b> ('+txt[2]+' plot Per Sesi)</li>');
								}
							}
							if (!data.status1) {
								for (var i=0; i<data.xPlot.length; ++i) {
									var txt = data.xPlot[i].split('|');
									if (data.status0 && data.status2) $("#errKelas"+txt[0]).append('Plot Jadwal sudah digunakan untuk Mata Kuliah <b>'+txt[1]+'</b> kelas <b>'+txt[2]+'</b>, Program Studi <b>'+txt[3]+'</b>');
									else $("#errKelas"+txt[0]).append('<li>Plot Jadwal sudah digunakan untuk Mata Kuliah <b>'+txt[1]+'</b> kelas <b>'+txt[2]+'</b>, Program Studi <b>'+txt[3]+'</b></li>');									
								}
							}						 
							if (!data.status2) {
								for (var i=0; i<data.xDosen.length; ++i) {
									var txt = data.xDosen[i].split('|');																	
									if (data.status0 && data.status1) $("#errKelas"+txt[0]).append('<b>'+txt[1]+'</b> sudah terjadwal mengajar di Mata Kuliah <b>'+txt[2]+'</b> kelas <b>'+txt[3]+'</b>, Program Studi <b>'+txt[4]+'</b><br>Hari <b>'+txt[5]+'</b> Sesi <b>'+txt[6]+'</b><hr>');
									else $("#errKelas"+txt[0]).append('<li><b>'+txt[1]+'</b> sudah terjadwal mengajar di Mata Kuliah <b>'+txt[2]+'</b> kelas <b>'+txt[3]+'</b>, Program Studi <b>'+txt[4]+'</b><br>Hari <b>'+txt[5]+'</b> Sesi <b>'+txt[6]+'</b><hr></li>');
								}
							}
							if ($("#errKelas"+txt[0]).is(":hidden")) $("#errKelas"+txt[0]).slideDown('fast').removeClass('hide');
						}
						if (!data.unik) {							
							$('.alert-unik-plot').empty();
							$('.alert-unik-plot').append("Terdapat <b>Plot Jadwal</b> yang sama (berulang), harap dicek ulang data yang sudah diinput");
							$("#alert-plot").slideDown('fast').removeClass('hide');	
						}
						if (data.status0 && data.status1 && data.status2 && data.unik) $('#modal_form').modal('hide');
					} 
					else if (data.db===1) {
						$('#modal_form').modal('hide');						
						$.toast({ text:"<b>Data Kelas</b> sudah disimpan" });
						reload_table();						
					}
					else if (data.db>1) {										
						$('#modal_form').modal('hide');						
						$.toast({ text:"<b>Data Kelas</b> dan <b>Jadwal Kelas</b> sudah disimpan" });
						reload_table();												 
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR.responseText);					
				}
			});
		}
	});
	
	function exp_pdf() {
		window.open("<?php echo base_url('kelas/expPDF/')?>");		
	}	
	function exp_xls() {
		window.open("<?php echo base_url('kelas/expXLS/')?>");		
	}	
</script>	
</head>