<?php
// php client.php {server-ip} {port}
if (!$argv[2]) {
    echo "php $argv[0] {server-ip} {port}\n";
    exit;
}
$server = $argv[1];
$port = $argv[2];
if (!($sock = socket_create(AF_INET, SOCK_DGRAM, 0))) {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Couldn't create socket: [$errorcode] $errormsg \n");
}
$id=rand(1000,9999);
$key='hello';
$buf=$key.'-'.$id;
echo "This node id: $id\n";
while (true) {
    echo "Send $buf to $server:$port\n";
    if (!socket_sendto($sock, $buf , strlen($buf) , 0 , $server , $port)) {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
        die("Could not send data: [$errorcode] $errormsg \n");
    }
    if (socket_recv($sock, $reply, 100, MSG_WAITALL) === FALSE) {
        $errorcode = socket_last_error();
        $errormsg = socket_strerror($errorcode);
        die("Could not receive data: [$errorcode] $errormsg \n");
    }
    echo "Got: $reply\n";
    if (strstr($reply,':')) { // got peer ip port
        $arr=explode(':',$reply);
        $server=$arr[0];
        $port=$arr[1];
        $buf='peer-'.$id; // change send data to peer-id
    }
    sleep(1);
}
?>