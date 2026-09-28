<script>
	$(document).ready(function(){
		var delay = 1000;
		var tabel = $('#tbPakai').DataTable({
			"paging": false,
			"searching": false,
			"bInfo": false,
			"ajax": "<?php echo site_url('pakai/ajax_list'); if (!empty($id)) echo "/".$id; ?>",
			"columns": [
				{"width":"60px"},{"width":"50px"},{"width":"80px"},null,{"width":"50px"},null,{"width":"60px"}
			], 
			"order": [[0,"asc"],[2,"asc"]],
<?php if ($this->session->userdata('hak')==1) { ?>
			"columnDefs": [
				{ "targets": [1,-1],"orderable": false },
				{ className:"text-center","targets":[0,1,2,4,-1] }				
			],
<?php } else { ?>
			"columnDefs": [
				{ "targets":[1,-1],"orderable":false },
				{ className:"text-center","targets":[0,1,2,4,-1] },
				{ "targets":[-1],"visible":false }
			],
<?php } ?>			
		});	
		setInterval( function () {
			tabel.ajax.reload();
		}, 60000 );
	});
	function detil_pakai(id)
	{
		$.ajax({
			url : "<?php echo site_url('pakai/ajax_detil/')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				var str = "<table class='table table-condensed'><tbody>";
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Hari, Tanggal</td><td>" + data.data.tgl + "</td></tr>";				
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Waktu</td><td>" + data.data.waktu + "</td></tr>";	
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Tempat</td><td>" + data.data.ruang + "</td></tr>";								
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Jumlah Peserta</td><td>" + data.data.peserta + "</td></tr>";				
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Unit</td><td>" + data.data.unit + "</td></tr>";				
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Penanggung Jawab</td><td>" + data.data.pj + "</td></tr>";		
<?php $hak = $this->session->userdata('hak');
	if (!empty($hak)) {	?>
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>No. HP Pen. Jawab</td><td>" + data.data.hp + "</td></tr></tbody>";	
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Jenis Konsumsi</td><td>" + data.data.jenis + "</td></tr></tbody>";								
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Nama Konsumsi</td><td>" + data.data.konsumsi + "</td></tr></tbody>";								
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Jml Konsumsi</td><td>" + data.data.jml + "</td></tr></tbody>";								
				str += "<tr><td></td><td style='width:140px; text-align:right; font-weight:bold'>Biaya Konsumsi</td><td>" + data.data.biaya + "</td></tr></tbody>";								
	<?php } ?>					
				$("#modal_detil .modal-body").empty();
				$("#modal_detil .modal-body").append(str);
				$("#modal_detil .modal-title").text(data.data.acara);
				$('#modal_detil').modal('show');				
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
		});
	}
</script>	
</head>