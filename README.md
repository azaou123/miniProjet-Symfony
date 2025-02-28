📜 README.md
md
Copier
Modifier
# 📌 WordPress & Symfony - Dockerized Project

## 📖 Description
Ce projet combine **WordPress** et **Symfony** dans un environnement Docker, avec **nginx** comme serveur web.  
Il permet d'analyser des sites WordPress via une API en Symfony.

---

## 🚀 Installation & Exécution

### 1️⃣ **Cloner le dépôt**
```bash
git clone https://github.com/votre-repo.git
cd votre-repo
2️⃣ Configurer les fichiers d'environnement
Créer un fichier .env à la racine du projet et définir les variables nécessaires :

ini
Copier
Modifier
# Base de données
DB_HOST=db
DB_NAME=wordpress
DB_USER=root
DB_PASSWORD=root

# Configuration Symfony
APP_ENV=dev
APP_SECRET=your-secret-key
Remarque : Adaptez ces valeurs en fonction de votre environnement.

3️⃣ Lancer Docker
Assurez-vous que Docker et Docker Compose sont installés, puis exécutez :

bash
Copier
Modifier
docker-compose up -d
Cela démarre les services suivants :
✅ nginx → Serveur web
✅ MySQL → Base de données
✅ WordPress → CMS
✅ Symfony → API

4️⃣ Accéder aux services
Service	URL
WordPress	http://localhost:8000
Symfony (API)	http://localhost:8080
phpMyAdmin	http://localhost:8081
🔄 Commandes utiles
🛠️ Gestion de Symfony
Se connecter au conteneur Symfony :

bash
Copier
Modifier
docker exec -it symfony_container bash
Puis exécuter les commandes :

bash
Copier
Modifier
composer install
php bin/console doctrine:migrations:migrate
php bin/console cache:clear
🛠️ Gestion de WordPress
Se connecter au conteneur WordPress :

bash
Copier
Modifier
docker exec -it wordpress_container bash
🛑 Arrêter le projet
bash
Copier
Modifier
docker-compose down
🧹 Nettoyer les volumes Docker
bash
Copier
Modifier
docker-compose down -v
📩 Support
En cas de problème, contactez Nom du Manager à email@company.com

yaml
Copier
Modifier

---

### 🎯 **Pourquoi ce README est bien structuré ?**
✔ **Clair et concis** – Chaque étape est expliquée simplement  
✔ **Pratique** – Toutes les commandes essentielles sont incluses  
✔ **Adaptable** – Le manager peut facilement changer les valeurs  

✅ **Ton projet est maintenant prêt à être compris et utilisé !** 🚀  
Besoin d'ajustements ? 😃