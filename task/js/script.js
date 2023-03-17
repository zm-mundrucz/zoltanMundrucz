$(document).ready(function() {
	$('#searchCountry1').on('keydown', function(event) {
        if (event.keyCode == 13) {
            $('#getEarthquakes').click();
        }
    });
	$('#getEarthquakes').click(function() {
		const country = $('#searchCountry1').val();
		$.ajax({
		url: 'php/earthquakes.php',
		type: 'POST',
		dataType: 'json',
		data: { country: country },
		success: function(result) {
			console.log(JSON.stringify(result));
	
		if (result.status.name == "ok") {
		  $('#txtDateTime').html(result['data'][0]['datetime']);
		  $('#txtDepth').html(result['data'][0]['depth']);
		  $('#txtMagnitude').html(result['data'][0]['magnitude']);
		  $('#txtErrorEarthquakes').html('');
		} else {
		  $('#txtErrorEarthquakes').html('Error: ' + result.status.message);
		}
	  },
	  error: function(jqXHR, textStatus, errorThrown) {
		$('#txtErrorEarthquakes').html(textStatus + ', please enter a "Country".');
	  }
	});
  });
  
	$('#searchCountry2').on('keydown', function(event) {
		if (event.keyCode == 13) {
			$('#getTimezone').click();
		}
	});
	$('#getTimezone').click(function() {
		const country = $('#searchCountry2').val();
		$.ajax({
		url: 'php/timezone.php',
		type: 'POST',
		dataType: 'json',
		data: { country: country },
		success: function(result) {
			console.log(JSON.stringify(result));
  
		if (result.status.name == "ok") {
		  $('#txtTime').html(result['data']['time']);
		  $('#txtSunrise').html(result['data']['sunrise']);
		  $('#txtSunset').html(result['data']['sunset']);
		  $('#txtErrorTimezone').html('');
		} else {
		  $('#txtErrorTimezone').html('Error: ' + result.status.message);
		}
	  },
	  error: function(jqXHR, textStatus, errorThrown) {
		$('#txtErrorTimezone').html(textStatus + ', please enter a "Country".');
	  }
	});
});

	$('#searchAddress').on('keydown', function(event) {
		if (event.keyCode == 13) {
			$('#getPostalcode').click();
		}
	});
    $("#getPostalcode").click(function() {
        const postalcode = $("#searchAddress").val();
		$.ajax({
			url: 'php/postalcode.php',
			type: 'POST',
			dataType: 'json',
			data: { postalcode: postalcode },
			success: function(result) {
				console.log(JSON.stringify(result));
				
			if (result.status.name == "ok") {
				$("#txtCountry").html(result.data[0].countryCode);
				$("#txtCounty").html(result.data[0].adminName2);
				$("#txtCity").html(result.data[0].placeName);
				$('#txtErrorPostalcode').html('');
			} else {
				$('#txtErrorPostalcode').html('Error: ' + result.status.message);
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$('#txtErrorPostalcode').html(textStatus + ', please enter a "Postcode" without space.');
		  }
		});
    });
});
