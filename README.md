# ToDoList

<a href="https://codeclimate.com/github/ssanchez91/ToDoList/maintainability"><img src="https://api.codeclimate.com/v1/badges/ef8614340af01b657945/maintainability" /></a>
[![Build Status](https://travis-ci.com/ssanchez91/ToDoList.svg?branch=main)](https://travis-ci.com/ssanchez91/ToDoList)

<h3>Screen /tasks page :</h3>


![frontend](https://github.com/ssanchez91/ToDoList/blob/main/docs/Visuel.PNG)

<h3>Documentation</h3>
<p>L'ensemble du code source a été commenté. L'utilsation de PhpDocBlocker a permis de générer une documentation claire et précise.</p>

<h3>Langage de programmation</h3>

L'application ToDoListe a été initiallisé ave la version 3.1 du framework Symfony et a été migrée vers la dernière version LTS ( SF 4.4)

<hr>
<h2>Installation du projet</h2>
<h3>Environnement nécessaire</h3>
<ul>
  <li>PHP 7.3.*</li>
  <li>MySql 5.7</li>
</ul>
<h3>Suivre les étapes suivantes :</h3>
<ul>
  <li><b>Etape 1.1 :</b> Cloner le repository suivant depuis votre terminal :</li>
  <pre>
  <code>git clone https://github.com/ssanchez91/ToDoList.git</code></pre>     
  <li>
   <li><b>Etape 1.2 :</b> Executer la commande suivante :</li>
  <pre>
  <code>composer install</code></pre>     
  <li>
    <li><b>Etape 1.3* :</b> Si besoins, ajouter le package symfony/apache-pack (en fonction de votre environnement de déploiement) :</li>
  <pre>
  <code>composer require symfony/apache-pack</code></pre>     
  <li><b>Etape 2 :</b> Editer le fichier .env </li>
    - pour renseigner vos paramètres de connexion à votre base de donnée dans la variable DATABASE_URL
  <li><b>Etape 3 :</b> Démarrer votre environnement local (Par exemple : Wamp Server)</li>
  <li><b>Etape 4 :</b> Exécuter les commandes symfony suivantes depuis votre terminal</li>
  <pre><code>
    symfony console doctrine:database:create (ou php bin/console d:d:c si vous n'avez pas installé le client symfony)<br/>
    symfony console doctrine:migrations:migrate<br/>
    symfony console doctrine:fictures:load  
  </code></pre>
  <li><b>Etape 5.1 :</b> Executer dans votre terminal les tests pour vous assurer que tout foncionne correctement</li>
  <pre><code>
    php bin/phpunit
  </code></pre>
</ul>
  
<h3>Vous êtes fin prêt pour lancer l'application ToDoList!</h3>
