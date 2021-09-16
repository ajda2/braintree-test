# Braintree test projekt

Zakoupení předplatného skrze "platební bránu" [Braintree](https://www.braintreepayments.com/cz)   
Platební brána je v testovacím (sandbox) režimu, takže **NEPROBÍHÁJÍ reálné platby**.

## Spuštění aplikace

**1. Spustit "server" v Dockeru:**
```
docker-compose up -d
```
*Pozn.: Při prvním spuštění může build Docker image trvat déle*

**2. Nastavení práv na zápis pro složky:**   
Adresářů `/temp` a `/log` je potřeba nastavit práva pro zápis dle Vašeho systému (ideálně rekurzivně i pro podadresáře).

**3. Instalace závislostí na spuštěném prostředí**
```
docker exec -it php-apache composer install
```

**4. Nastavení klíčů pro Braintree API**   
Pro nastavení klíčů je možné použít 2 způsoby, podle toho, které Ti vyhovují více: 
1. **Konfigurační soubor `.neon`** - *Výchozí stav. Stačí pouze doplnit klíče*
   1. V souboru [config/local.neon](./config/local.neon) je potřeba doplnit Vaše `merchan_id`, `public_key` a `private_key` pro Braintree.
   2. V konfig souboru [config/local.neon](./config/local.neon) je zaregistrována služba `app.brainTree.gatewayFactory: Mrcek\BraintreeTest\Braintree\GatewayManualConfigFactory`
2. **DB tabulka s konfigurací aplikace**
   1. Skrze [Adminer](http://localhost:8081/) v DB tabulce `config` vyplň hodnoty klíčů
   2. V konfig souboru [config/local.neon](./config/local.neon) zaregistruj službu `app.brainTree.gatewayFactory: Mrcek\BraintreeTest\Braintree\GatewayFromDbConfigFactory`
   

**5. Otevři v prohlížeč a testuj :-)**  
Aplikace je dostupná na portu 8080: [http://localhost:8080/](http://localhost:8080/) 
Na úvodní stránce jsou vypsány plány pro předplatné, které lze zakoupit. 
Při platbě použij testovací kartu `4111111111111111`. 

## Databáze
Pro administraci DB je připraven nástroj Adminer.   
Dostupný na portu [8081](http://localhost:8081/)   


Databáze se vytvoří automaticky při vytvoření Docker image skrze `docker-compose`.    
Výchozí stav DB je uložen v [.database/create.sql](./database/create.sql)

### Přístupy do DB
*Pro Nette aplikaci jsou již nastaveny*
```
host: mysql
dbname: 'braintree'
user: 'braintree'
password: '123'
```


## Kontrola kódu a testy
Pro testy je použit nástroj [Codeception](https://codeception.com/)   
Jednotlivé kontroly a testy je možné spouštět samostatně i dohromady jako composer skripty dle potřeby.  
Jejich nastavení najdeš v souboru [composer.json](./composer.json) v sekci `scripts`

**Spuštění všech kontrol a testů**
```
docker exec -it php-apache composer build:complete
```

**Spuštění kontroly a Unit testů bez integračních testů**
```
docker exec -it php-apache composer build
```

**Spuštění kontroly kódu Code sniffer**  
Pravidla jsou definovány v souboru [.cs/ruleset.xml](./.cs/ruleset.xml)
```
docker exec -it php-apache composer cs
``` 

Pro automatickou opravu chyb lze spustit jako
```
docker exec -it php-apache composer cs-fix
``` 

**Spuštění kontroly kódu PHP Stan level 8**  
Pravidla jsou definovány v souboru [.cs/phpstan.neon](./.cs/phpstan.neon)
```
docker exec -it php-apache composer phpstan
``` 

**Spuštění Unit testů**   
Testy najdeš v adresáři [tests/Unit](./tests/Unit)
```
docker exec -it php-apache composer tests:unit
``` 

**Spuštění Integračních testů**   
Testy najdeš v adresáři [tests/Integration](./tests/Integration)
```
docker exec -it php-apache composer tests:integration
``` 

**TODO: Spuštění Akceptačních testů**


## Běhové prostředí
Aplikace je připravena k okamžitému spuštění v Docker kontejnerech.   
Konfigurace celého prostředí je v souboru [docker-compose.yml](./docker-compose.yml).

**Docker Image:**
* [php-apache](./.docker/php-apache/Dockerfile) 
  * PHP 7.4 s apache
    * TODO: Update na 8.1
  * Dostupný na portu [8080](http://localhost:8080/)
  * Obsahuje všechyn potřebné rozšíření a balíčky
  * Nakonfigurováno odchytávání e-mailů do služby Mailhog
* [php-apache-debug](./.docker/php-apache-debug/Dockerfile)
  * PHP 7.4 s apache a aktivním XDebug
    * TODO: Update na 8.1
  * Dostupný na portu [8083](http://localhost:8083/)
* mysql
  * Oficiální image MySQL 5.7 s daty aplikace
  * Dostupná na portu `8306` 
* mysql-test
  * Oficiální image MySQL 5.7 pro integrační testy apod.
  * Dostupná na portu `9306`
* mailhog
  * Služba pro zachytávání všech odeslaných e-mailů z aplikace
  * Dostupná na portu [8082](http://localhost:8082/)