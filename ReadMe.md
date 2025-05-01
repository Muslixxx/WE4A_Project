# Guide démmarage du projet

### à l'emplacement du projet:

- composer install (cela peut prendre quelques minutes)

### dans PhpMyAdmin:

- créer une base de donnée nommée: "app"

### à l'emplacement du projet:

- php bin/console doctrine:schema:update --force

- php bin/console doctrine:fixtures:load (yes)

### dans XAMPP:

- démarrer son serveur apache et MySQL

### à l'emplacement du projet:

- symfony serve -d

### Vous pouvez désormais accéder au projet!

users par défaut dans la base:

- admin: admin@admin.com -- mdp: admin1234
- eleve: eleve@school.com -- mdp: aaaaaa
- prof: professeur@school.com -- mdp: aaaaaa
