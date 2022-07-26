# Úvod
Tento návod se týká pro spuštění na Windows. Pro spuštění na unixových systémech postupujte dle zvyklostí daného systému.
Pro zprovoznění je zapotřebí mít nainstalové [Symfony](https://symfony.com/ "Symfony") a databázový server, například MySQL v [XMAPP](https://www.apachefriends.org/ "XMAPP"). Doporučuji použít PowerShell pro instalaci.

# Instalace
Stáhněte repozitář a umístětě např. do `C:\xampp\htdocs\restapi` a poté v adresáři spusťte
```sh
composer install
```

Nastavte spojení s databazí v souboru `.env` v kořenovém adresáři.
```env
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7&charset=utf8mb4"
```

Vytvořte databázi pomocí:
```sh
php bin/console doctrine:database:create
```

A vytvořtě potřebné tabulky pomocí:
```sh
php bin/console doctrine:migrations:migrate
```

Nového uživatele vložíte pomocí:
```sql
INSERT INTO user (username,roles,password) VALUES ('test','["ROLE_USER"]','$2y$13$Ii9Km58hMRMedyZtWhAA2ueIrTslAUV85GEmfZ9azLYmc.eVcqMNS');
```

Spusťte server:
```sh
symfony serever:start
```

Ziskejte JWT token:
```sh
curl -k -X POST -H "Content-Type: application/json" https://localhost:8000/api/login_check --data {\"username\":\"test\",\"password\":\"test\"}
```

Otevřete dokumentaci k projektu:
```url
https://localhost:8000/api/doc
```

V levo nahoře klikněte na `Authorize` a vyplňte token ve tvaru:
```sh
Bearer token
```
A klikněte na `Login`
A nyní můžete spouštět všechny dostupné metody.

# Používání
K práci s REST API doporučuji použít [Postman](https://www.postman.com/ "Postman")

# Data pro testy
```sql
INSERT INTO `user` (`username`, `roles`, `password`) VALUES
('test1', '[\"ROLE_USER\"]', '$2y$13$Ii9Km58hMRMedyZtWhAA2ueIrTslAUV85GEmfZ9azLYmc.eVcqMNS'),
('test2', '[\"ROLE_USER\"]', '$2y$13$Ii9Km58hMRMedyZtWhAA2ueIrTslAUV85GEmfZ9azLYmc.eVcqMNS'),
('test3', '[\"ROLE_USER\"]', '$2y$13$Ii9Km58hMRMedyZtWhAA2ueIrTslAUV85GEmfZ9azLYmc.eVcqMNS'),
('test4', '[\"ROLE_USER\"]', '$2y$13$Ii9Km58hMRMedyZtWhAA2ueIrTslAUV85GEmfZ9azLYmc.eVcqMNS'),
('test5', '[\"ROLE_USER\"]', '$2y$13$Ii9Km58hMRMedyZtWhAA2ueIrTslAUV85GEmfZ9azLYmc.eVcqMNS');
```
