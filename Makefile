up:
	docker-compose up -d

stop:
	docker-compose stop

cache-clear:
	docker exec -it php_ubot bin/console cache:clear
