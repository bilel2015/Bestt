Symfony Standard Edition
========================

```sh
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:create
```

##Later
And finally, you need to run the fixtures:

```sh
$ php app/console doctrine:fixtures:load
```