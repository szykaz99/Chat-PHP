function refresh(from,to){
    i=0;
    const interval = setInterval(function() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('messages').innerHTML = document.getElementById('messages').innerHTML+this.responseText;
                document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
                if (this.responseText == ""){
                    //console.log('zba');
                } else {
                    //console.log(this.responseText);
                    var text = this.responseText;
                    const myArray = text.split("'");
                    if (myArray[1] == "msg-r") {

                    } else {
                        var audio = new Audio('pikpik.mp3');
                        audio.play();
                    }
                }
            }
        };
        xhttp.open('POST', 'newRefresh.php', true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.send('from='+from+'&to='+to);


    if (i==-1){
        clearInterval(interval);
        //console.log('STOP');
    } else {
        i++;
        //console.log(i);
    }
    }, 1000);
}