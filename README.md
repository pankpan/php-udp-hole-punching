# php-udp-hole-punching
UDP hole punching POC written in PHP

## Server
Run the server with listening port as the first argument. For example
```
php server.php 11111
```
This command will run the server on port 11111

## Client
```
php client.php {server_ip_address} 11111
```
After two clients are connected to server,
server sends the other peer's IP to each peer and punching happens.
then server's job is done.
