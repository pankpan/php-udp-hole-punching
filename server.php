<?php
// php server.php {port}
if (!$argv[1]) {
    echo "php $argv[0] {port}\n";
    exit;
}

$port=$argv[1];
$remote_ip_ports=[];
$n=0;

if (!($sock = socket_create(AF_INET, SOCK_DGRAM, 0))) {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Couldn't create socket: [$errorcode] $errormsg \n");
}
echo "Socket created\n";

if (!socket_bind($sock, "0.0.0.0", $argv[1])) {
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Could not bind socket : [$errorcode] $errormsg \n");
}
echo "Socket bind OK\n";
$ip=trim(file_get_contents('http://icanhazip.com'));
echo "Client side command: php client.php $ip $port\n\n";
while (true) {
    echo "Waiting for data ... \n";
    $r = socket_recvfrom($sock, $buf, 512, 0, $remote_ip, $remote_port);
    $key=trim(strtok($buf,'-'));
    $remote_ip_port=$remote_ip.':'.$remote_port;
    echo "Got: $buf from $remote_ip_port\n";
    if (is_null($remote_ip_ports[$key])) $remote_ip_ports[$key]=[];
    if (count($remote_ip_ports[$key])<2 && $remote_ip!=$remote_ip_ports[$key][0]['ip']) {
        $remote_ip_ports[$key][]=['ip'=>$remote_ip,'port'=>$remote_port];
    }
    if (count($remote_ip_ports[$key])>=2) {
        $n++;
        print_r($remote_ip_ports);
        if ($remote_ip==$remote_ip_ports[$key][0]['ip'])
            $response=$remote_ip_ports[$key][1]['ip'].':'.$remote_ip_ports[$key][1]['port'];
        else
            $response=$remote_ip_ports[$key][0]['ip'].':'.$remote_ip_ports[$key][0]['port'];
        socket_sendto($sock, $response, 100, 0, $remote_ip, $remote_port);
        if ($n>=2) break; // server job is done
    } else {
        socket_sendto($sock, "Server-ACK", 100, 0, $remote_ip, $remote_port);
    }
}
socket_close($sock);
?>