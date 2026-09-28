<script>
	$(document).ready(function(){
		$.ajax({
			url:"<?php echo base_url('set/ajax_list');?>",
			success: function(data)
			{
				$("#dataset").html(data);	
				$('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
			},
			error: function (jqXHR, textStatus, errorThrown)
			{ alert(jqXHR.responseText); }
		});	

		$( "#fSet" ).submit(function(event) {			
			save_data();
			event.preventDefault();
		});	

		function save_data(){
			var num = $('input:checkbox').length;			
			$.ajax({
				url : "<?php echo site_url('set/update_data')?>/"+num,
				type: "POST",
				data: $('#fSet').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					for (var i=1; i<=2; ++i) {
						$("#ed"+i).val(data.sets[i]);	
					}
					if (data.db) {
						$.toast({ text:"Setting Sistem sudah disimpan" });
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ alert('Error Set Sistem'); }
			});
		};
	});
</script>	
</head>