document.getElementById('start').addEventListener('click', function(e){
	var i = 0;
	document.getElementById('loading').style.width = `${i}%`;
	document.getElementById('loading').textContent = `${i}%`;
	setInterval( function() {
		i++;
		document.getElementById('loading').style.width = `${i}%`;
		document.getElementById('loading').textContent = `${i}%`;
		document.getElementById('start').textContent = "In Progress"
        if(i > 100){
        	document.getElementById('loading').textContent = "Loading Complete";
        	document.getElementById('start').textContent = "Start Now"
        	return
        }
	},100)
});