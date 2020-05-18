# MVC_MY_Quiz

My first project with Symfony !

* Register _(with email validation)_ and login
* CRUD user _(with email validation when you change the password or email)_
* Play quiz
* See your quiz history _(if user is not authenticated, he can plays a quiz and see history)_

With the admin role : 
* CRUD question
* CRUD response
* CRUD category 
* CRUD user
 
[My app](https://my-quizz.sekhmset.me/)

## Versions

* PHP 7.4.5
* Symfony 5.0.*
* Composer 1.10.5

## In local
### Start and quite the server 

You need to have instal Symfony before use the following command in your terminal.
To instal Symfony, refer you to the links at the end of this readme

```bash
symfony server:start
```

```bash
ctrl + c
```

### Database

Start migration to have all the tables :

```bash
php bin/console doctrine:migrations:migrate 
```

Don't forget to update your username, password and table in `.env`
 
 **To deploy :** in your Google Cloud Platform create your database and an user
 
 How to configure your variable ? 
 ```bash
DATABASE_URL = mysql://username:password@localhost?unix_socket=/cloudsql/project_id:region:your_database;dbname=database_name
 ```

### Admin 
I used `easyAdmin` a Symfony bundle. 
To install easyAdmin I followed the instructions of the documentation.

[Link of the documentation](https://symfony.com/doc/master/bundles/EasyAdminBundle/index.html)

## Mail
### Dev env.
[MailTrap](https://mailtrap.io/)

Create your inbox then update your config in `.env`

### Prod env.

[Sendgrid](https://sendgrid.com/)

Create the API Key then update your config in `.env`

*PS : if you don't receive the email think to check your indesirable email*

## Docker and Google Cloud

Create a `docker` directory, `docker-compose.yml` file and `.dockerignore`.
In your docker directory you have to create the following folders : `Dockerfile` and `opcache.ini`

### Make your migration on cloud 

Create a `.cloudbuild` directory and a `seeder-deploy.yaml` in this directory.

```bash
EXPORT PROJECT_ID=your_project_id
EXPORT DOCKER_IMAGE=your_docker_image
EXPORT YOUR_TAG=name_of_your_tag

docker build -f docker/Dockerfile -t eu.gcr.io/${PROJECT_ID}/${DOCKER_IMAGE}:${YOUR_TAG} .
docker push eu.gcr.io/${PROJECT_ID}/${DOCKER_IMAGE}:${YOUR_TAG}
```

Start your migration with cloud build

```bash
gcloud builds submit \
  --project ${PROJECT_ID} \
  --config .cloudbuild/seeder-deploy.yaml \
  --substitutions "_SERVICE=${DOCKER_IMAGE}:${YOUR_TAG},_REGION=europe-west1,_INSTANCE_NAME=my-sql-database,_DATABASE_URL=mysql://login_database:password_database@localhost?unix_socket=/cloudsql/${PROJECT_ID}:europe-west1:my-sql-database;dbname=db_name"
```

### DNS and Google Analytics

If you want to use Google Analytics or somthing else create your property and configure it _(Google A.)_
Then add the code at the end of the template : `base.html.twig`

To use a DNS add your DNS in your Cloud DNS and add a mapping to your service

## Links

- Link to download : [Link](https://symfony.com/download)
- Link to install and configure Symfony : [Link](https://symfony.com/doc/current/setup.html)
- Composer : [Link](https://getcomposer.org/)

## Preview

![article](.github/preview/home.png "Home page")
![article](.github/preview/indexquizz.png "Quizz page")
![article](.github/preview/showquizz.png "Play a quiz page")
![article](.github/preview/historyindex.png "History page")
![article](.github/preview/historyshow.png "History of a quiz")
![article](.github/preview/register.png " Register page")
![article](.github/preview/login.png " Login page")
![article](.github/preview/admin.png " Admin page")
