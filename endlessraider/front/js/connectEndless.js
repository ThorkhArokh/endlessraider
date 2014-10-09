$(document).ready(function() {
	afficheUserEndless();
	
	$( "#encarConnexion" ).on('click', "#loginLink", function() {
			$("#loginLink").hide();
			$(".register").hide();
			$("#zoneSaisieConnexion").show();
	});
	
	$( "#encarConnexion" ).on('submit', "#formConnectEndless", function(event) {
		// On définie la gestion du submit du form
		event.preventDefault();
	   
		// On bind les valeurs du formulaire
		var values = $(this).serialize();
	 
		$.ajax({
			url: "../forum/connectUser.php",
			type: "post",
			data: values,
			dataType: 'json',
			success: function(data){
				if(data.statut == "success") {
					afficheUserEndless();
				}
				else {
					$("#encarConnexionErreur").html(data.errorMess);
				}
			},
			error:function(){
				$("#encarConnexionErreur").html('Une erreur est survenue lors de la validation du formulaire.');
			}
		});
	});
});

function afficheUserEndless() {
	$.ajax({
		url: "../forum/showUser.php",
        type: "get",
        success: function(data){
            $("#encarConnexion").html(data);
        },
        error:function(){
            $("#encarConnexionErreur").html('Une erreur est survenue.');
            console.log('toto');
        }
	});
}