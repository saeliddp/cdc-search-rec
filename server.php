<?php

$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($sock, '0.0.0.0', 10174);

for (;;) {
    socket_recvfrom($sock, $message, 1024, 0, $ip, $port);
    echo "Got a request...\n";
    $reply = shell_exec("/usr/bin/python3.6 custom_query.py > ./session/results");
    socket_sendto($sock, $reply, strlen($reply), 0, $ip, $port);
}
?>
