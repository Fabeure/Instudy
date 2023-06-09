<h1 align="center">INSTUDY</h1>

This is a platform that aims to facilitate every problem faced by students during their education process 

* access to courses and ask your professors
* use ChatGPT API to explain some TOPICS
* communicate with your classmates and profs via chat in a real-time interface
* send in your homework and be graded
* get notifications of every new message, course, or homework in a real-time interface
* communicate via Tickets with the ADMIN who will be the class delegate
* The admin manages accounts and privileged action
* the profs can answer questions and upload courses and homework 
* ...


This platform have been done in the context of Academic Project @ INSAT for 2nd year software pre-engineering Student in Web Development.

Powered By <ins> Symfony FrameWork </ins>


<h3>To Run The Project</h3>

clone the project

```
    git clone https://github.com/Fabeure/Projet-Techno-Web.git
```

install all the dependencies

```
    composer install
    npm install
```

create the database and make all the migrations update its schema <br> then run the symfony server

```
    php bin/console doctrine:database:create
    
    php bin/console make:migration
    
    php bin/console doctrine:migrations:migrate

    symfony serve
```
install and run the docker with mercure and mailer container

```
    docker-compose up
```




<h3> Diagrams </h3>

* Use Cases

![usecase](https://github.com/Fabeure/Instudy/assets/47497916/2c4c43e9-b67b-4db8-87f8-5bf5f1accb56)

* Class Diagram

![Capture d'écran 2023-05-10 213141](https://github.com/Fabeure/Instudy/assets/47497916/98935396-7276-4c95-b99c-87932626aa97)


------

with Love and Passion from

* Saber AZOUZI 
* Idris SADDI
* Mohamed HANNACHI
* Naoures BZEOUICH

<h1  align="center">
    <a href="https://symfony.com/" target="_blank" rel="noreferrer"> 
        <img 
             src="https://raw.githubusercontent.com/devicons/devicon/master/icons/symfony/symfony-original-wordmark.svg" 
             alt="symfony" 
             width="200" 
             height="200"
        /> 
    </a> 
</h1>
