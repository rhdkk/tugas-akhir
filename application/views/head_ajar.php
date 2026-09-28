<script>
	var tabelDet = $('#tbDet').dataTable();
	$(document).ready(function(){
		var tabel = $('#tbAjar').dataTable({
			"ajax": "<?php echo site_url('report/ajax_ajar'); ?>",
			"columns": [null,{"width":"100px"},null,{"width":"70px"},{"width":"30px"},{"width":"30px"},{"width":"30px"},{"width":"30px"}], 
			"order": [[1,"desc"],[3,"desc"],[0,"asc"]],
			"columnDefs": [
				{ className:"text-center","targets":[3,4,5,6,7] }				
			]
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"}]
		});	

		var tabelDet = $('#tbDet').dataTable({
			"ajax": "<?php echo site_url('report/ajax_detAjar/');?>",
			autoWidth: false,
			"columns": [
				null,{"width":"15%"},{"width":"20%"},{"width":"40%"},{"width":"20%"},{"width":"5%"}
			], 
			"order": [[0,"asc"],[2,'asc']],
			"columnDefs": [
				{ "targets": [0,1,2,3,4,5],"orderable": false },
				{ "targets": [0],"visible": false },
				{ className:"text-center","targets":[2,-1] }				
			],
			initComplete: function() {
				$('#nMK').val(this.api().data().length)
			},
			"paging": false,
			"searching": false,
			"bInfo": false			
		});
		
		function reload_table(){
      tabel.api().ajax.reload(null,false); 
    }
		
		$("#fDet").submit(function(event) {
			var id = $('#idD').val();
			tabelDet.api().ajax.url("<?php echo site_url('report/ajax_detAjar/')?>/" + id).load();			
			$('#modal_form').modal('show');			
			$('.modal-title').text('Detil Ajar');
			event.preventDefault();
		});
	});	
	
	function det_ajar(id){
		$('#idD').val(id);
		$("#fDet").submit();		
	}
</script>	
</head>