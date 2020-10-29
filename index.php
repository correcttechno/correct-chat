<html>
    <head>

    </head>
    <body>
        <div id="list">

        </div>
        <input type="text" id="message">
        <button>
            Gonder
        </button>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script>
            $(function(){
                var token=null;
                if(localStorage.getItem('chat_token')==undefined){
                    token="<?=md5(time()+rand(10,100));?>";
                    localStorage.setItem('chat_token',token);
                }
                else{
                    token=localStorage.getItem('chat_token');
                }
                
                var socket=new WebSocket('ws://127.0.0.1:147');
                socket.onopen=function(){
                    console.log('connected');
                    send_message('start-chat');
                }
                socket.onclose=function(){
                    console.log('disconnected');
                }

                socket.onmessage=function(e){
                    $('#list').append(e.data+'<br>');
                    console.log(e.data);
                }

                function send_message(type,message=true){
                    var data={
                        'token':token,
                        'type':type,
                        'message':message
                    };
                    data=JSON.stringify(data);
                    socket.send(data);
                }

                $('button').click(function(){
                    data=$('input').val();
                    send_message('send-message',data);
                })

            })
        </script>
    </body>
</html>