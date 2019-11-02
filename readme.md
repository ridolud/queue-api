
# Queue API

## Passport seccret key

Client ID: 1
Client secret: G8acNFxOZRQOIqWRh4GLLFJaZFicmwyfs6C6MngR

Client ID: 2
Client secret: 2xuZ75hjPATI3Mj8AdKSszXFDxQXlnwjhiNmxIsy

## Documentation API v1

Login
post /api/v1/login

	body: {
		"email": admin@email.com,
		"password": password
	}

	response: {
		"success": { 
		 	 "token": token  
		}
	}

Login
post /api/v1/register

	body: {
		"name": John,
		"email": john@example.com,
		"password": password,
		"c_password": password
	}

	response: {
		"success": { 
		 	 "token": token  
		}
	}

Login
post /api/v1/getUser

	header: {
		"Authorization": Bearer . token
	}

	response: {
		"success": {
			"id":1,
			"name":"admin",
			"email":"admin@email.com",
			"email_verified_at":null,
			"created_at":"2019-10-25 20:06:17",
			"updated_at":"2019-10-25 20:06:17"
		}
	}
		

## Setup APNs (Apple Push Notification)

Kita menggunakan package https://github.com/edujugon/PushNotification
Cara setup certificate https://stackoverflow.com/questions/21250510/generate-pem-file-used-to-set-up-apple-push-notifications

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
