# php-udp-hole-punching
UDP hole punching POC written in PHP  
This POC need a server with public IP as intermediator.

## Server (Intermediator)
Run the server with listening port as the first argument. For example
```
php server.php 51111
```
This command will run the server on port 51111

## Client
Two clients (under NAT) run the command
```
php client.php {server_ip_address} 51111
```
After two clients are connected to server,
server sends the other peer's IP to each peer and punching happens. 
Server's job is done.
