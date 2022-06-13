## Mailerlite test

This project run using Laravel Sail using PHP 8 and PostgreSQL.

Steps to run the project on a local env.
- Run `composer install && npm install` to install dependencies.
- Run `./vendor/bin/sail up -d` to install Docker containers (be aware to keep port 80 free to avoid issues).
- Run `./vendor/bin/sail art migrate:fresh --seed` to migrate the database and seed data into it.
- Run `./vendor/bin/sail npm run dev` to compile the project.
- Access to project navigating to `http://localhost`.

PHP dependencies can be installed using Docker with this command.
`docker run --rm --interactive --tty --volume $PWD:/app composer <command>` and then just `./vendor/bin/sail npm install` after installing Docker containers.
