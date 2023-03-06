mysql:
	podman run -h db --name mysql-laragigs -p 3306:3306 -e MYSQL_ROOT_PASSWORD=password -d mysql:8.0
	
createdb:
	podman exec -ti mysql-laragigs mysql -u root -ppassword -e "CREATE DATABASE laragigs"

dropdb:
	podman exec -ti mysql-laragigs mysql -u root -ppassword -e "DROP DATABASE laragigs"

migrate:
	php artisan migrate

seed:
	php artisan db:seed

serve:
	php artisan serve

.PHONY: mysql createdb dropdb seed migrate serve
