function sender(from,to){
    var xhttp = new XMLHttpRequest();
    var output = document.getElementById('input').value;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('input').value = '';
            document.getElementById('messages').innerHTML = document.getElementById('messages').innerHTML+this.responseText;
            document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
        }
    };
    xhttp.open('POST', 'newMessage.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('from='+from+'&to='+to+'&mess='+output);
}

var input = document.getElementById('input');
input.addEventListener('keyup', function(event) {
  if (event.keyCode === 13) {
   event.preventDefault();
   document.getElementById('send').click();
   document.getElementById('input').value='';
  }
});
