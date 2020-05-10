# MVC_MY_Quiz

Project realized with Symfony 

## Start and quite the server in local

```bash
symfony server:start
```

```bash
ctrl + c
```

## versions

```bash
PHP 7.4.5
```

```bash
Symfony CLI version v4.14.3 
```

```bash
Composer version 1.10.5
```
## links

- Link to install and configure Symfony : [Link](https://symfony.com/doc/current/setup.html)
- Composer : [Link](https://getcomposer.org/)

# How to use it with docker
## Make the migration on cloud

You have to create a `.cloudbuild` directory and a `seeder-deploy.yaml`
```bash
gcloud builds submit \
  --project deploy-276111 \
  --config .cloudbuild/seeder-deploy.yaml \
  --substitutions "_SERVICE=my-quizz:v1,_REGION=europe-west1,_INSTANCE_NAME=my-sql-database,_DATABASE_URL=mysql://my-quizz:password@localhost?unix_socket=/cloudsql/deploy-276111:europe-west1:my-sql-database;dbname=my_quizz"
```