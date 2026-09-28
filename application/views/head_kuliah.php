<script>
	$(document).ready(function(){
		var tabel = $('#tbKuliah').dataTable({
			"ajax": "<?php echo site_url('report/ajax_kuliah'); ?>",
			"columns": [
				null,null,{"width":"60px"}
			], 
			"order": [[0,"asc"],[1,"asc"]],
			"columnDefs": [
				{ className:"text-center","targets":[1,2] }				
			]
		});		
		tabel.columnFilter({
			aoColumns: [{type:"text"},{type:"text"},{type:"text"}]
		});		
		
		function reload_table(){
      tabel.api().ajax.reload(null,false); 
    }
	});
</script>	
</head>