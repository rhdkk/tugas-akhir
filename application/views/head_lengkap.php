<script>
	var tabelDet = $('#tbDet').dataTable();
	$(document).ready(function(){
		var tabel = $('#tbLengkap').dataTable({
			"ajax": "<?php echo site_url('report/ajax_lengkap'); ?>",
			"columns": [null,{"width":"80px"},{"width":"100px"},null,{"width":"20px"},{"width":"50px"},{"width":"80px"},{"width":"50px"}], 
			"order": [[0,"asc"],[5,"asc"],[6,"asc"]],
			"columnDefs": [
				{ className:"text-center","targets":[4,6] }				
			]
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"},{type:"text"}]
		});	

		function reload_table(){
      tabel.api().ajax.reload(null,false); 
    }
	});	
	
	function det_ajar(id){
		$('#idD').val(id);
		$("#fDet").submit();		
	}
</script>	
</head>