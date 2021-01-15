# ToDoList - Guide pour contribuer aux évolution de l'application <br/>

Bienvenue dans ce guide du développeur qui vous permettra de contribuer aux évolutions de l'application avec les bonnes pratiques à suivre.

### 1. Prérequis

*   Installez le projet en suivant les instructions dans le fichier [README.md](README.md).

### 2. Contribution à l'application

*   Créez une nouvelle branche sous Git
*   Codez une nouvelle fonctionnalité ou codez un fix pour un bug encore inconnu (Attention de bien vérifier la liste des [Issues](https://github.com/ssanchez91/ToDoList/issues) en cours avant de commencer votre développement)
*   Testez votre code avec PHPUnit en éxécutant la commande suivante : php bin/phpunit
*   Commit et push de votre nouveau code sur votre branche.

### 3. Intégration de votre code via une PULL REQUEST

*   Créer une pull request à partir de votre branche
*   Attendre que l'intégration Continue via Travis éxécute les tests.
*   une fois les tests validés, effectuer un merge de votre code sur la branche principale.
*   Enfin, s'assurer via le badge CodeClimate présent dans le fichier [README.md](README.md) que vous obtenez bien toujours la note de A. 

### 4. Rappels sur les STANDARDS

*    <a href="https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md" target="_blank">PSR-1: Basic Coding Standard</a>
*    <a href="https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md" target="_blank">PSR-2: Coding Style Guide</a>
*    <a href="https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md" target="_blank">PSR-4: Autoloading Standard</a>
*    <a href="https://symfony.com/doc/4.4/contributing/code/standards.html" target="_blank">Symfony Coding Standards</a>
*    <a href="https://twig.symfony.com/doc/2.x/coding_standards.html" target="_blank">Twig Coding Standards</a>
*    <a href="https://www.doctrine-project.org/projects/doctrine-coding-standard/en/8.2/index.html" target="_blank">Doctrine Coding Standards</a>

### 5. Rappels sur les bonnes pratiques Symfony

*    <a href="https://symfony.com/doc/4.4/best_practices.html" target="_blank">Symfony Best Practices</a>
