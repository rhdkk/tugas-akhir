<script>
	$(document).ready(function(){
		$('#fPass').validate({ 
			rules: {
				pass0: { required:true, minlength:4, maxlength:25 },				
				pass1: { required:true, minlength:4, maxlength:25 },				
				pass2: { required:true, minlength:4, maxlength:25, equalTo: "#pass1" }
			},
			messages: {
				pass2: { equalTo: "Password tidak sama"}
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
				save_data();
			}			
		});		
		
		$( "#fPass" ).submit(function(event) {			
			event.preventDefault();
		});	
		
		$("#pass0").keyup(function() {			
			$("#errPass").closest('.form-group').removeClass('has-error');
			$("#errPass").addClass('hide');
		});

		function save_data(){
			$.ajax({
				url : "<?php echo site_url('pass/update_data')?>",
				type: "POST",
				data: $('#fPass').serialize(),
				dataType: "JSON",
				success: function(data)
				{
					if (data.status){
						if (data.db) {
							$.toast({ text:"Password berhasil diubah" });
						}
					}
					else {
						$("#errPass").text("Password Salah");
						$("#errPass").closest('.form-group').removeClass('has-success').addClass('has-error');
						if ($("#errPass").is(":hidden")) $("#errPass").slideDown('fast').removeClass('hide');
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{ alert('Error Ubah Password'); }
			});
		};
	});
</script>	
</head>