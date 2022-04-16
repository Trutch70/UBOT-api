FROM nginx:latest

#set up self-signed keys
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt -subj "/C=PL/ST=Lukasz Traczyk/L=Warsaw/O=none/OU=none/CN=ubot.local" \