# Task

[Getting Started](#Getting-Started)

#

### Getting Started

1\. First of all you need to clone 'dataninja-task' repository from github:

```sh
git clone https://github.com/NUG01/dataninja-task.git
```

2\.

```sh
composer install
```

3\.

```sh
npm install
```

4\. This step should have been copying .env file, but in that case I will provide it

5\. After setting up .env variables

```sh
php artisan config:cache
```

6\. Now execute in the root of you project following:

```sh
  php artisan key:generate
```

Which generates auth key.

7\. Also execute the following command:

```sh
  php artisan migrate
  &&
  php artisan db:seed
```

##### Now, you should be good to go with!

```sh
  php artisan serve

```
