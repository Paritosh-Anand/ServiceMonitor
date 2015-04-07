var getData = function() {
	if(arguments.length < 4) {
		service_name	= arguments[0];
		datacenter	= arguments[1];
		fabric		= (arguments.length == 3)?arguments[2]:'live';
	}
	console.log('ServiceName -' + service_name + ' DC -' + datacenter + ' fabric -' + fabric);
	//AJAX code to get ZK Data from REST API
	var url = "http://alp1.pv.sv.nextag.com/service_monitor/ServiceInfo.php?service_name="+ service_name +"&datacenter="+ datacenter +"&fabric="+fabric;
	var xmlhttp = new XMLHttpRequest();
	var dataStr = "";
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if (xmlhttp.status == 200){
				var obj = eval('(' + xmlhttp.responseText + ')');
				for(var key in obj){
					dataStr += "Host - " + obj[key]['serviceEndpoint']['host'] +
						   ", Port - " + obj[key]['serviceEndpoint']['port'] +
						   ", Status - " + obj[key]['status'] + "<br>";
				}
				document.getElementById("response_data").innerHTML = dataStr;
			}
			else if (xmlhttp.status == 400){
				console.error('There was a 400 error');
			}
			else{
				console.warn('Unknown response');
			}
		}
	}
	xmlhttp.open("GET", url);
	xmlhttp.send();

	document.getElementById("service").innerHTML = service_name;
	document.getElementById("popup").style.display="block";
	document.getElementById("dc").innerHTML = datacenter;
	document.getElementById("mask").style.display="block";
}

var hide = function () {
	document.getElementById("popup").style.display ="none";
	document.getElementById("mask").style.display ="none";
	document.getElementById("response_data").innerHTML = "Loading Data";
}
