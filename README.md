# SADRŽAJ

 - Uvod
 - Instalacija
   - Neophodni softverski paketi
   - Kreiranje baze podataka
   - Inicijalizacija softverskih paketa
 - Podešavanje lokalnog domena
 - Autor

# UVOD

ShareBOX je web aplikacija za prikupljanje i analizu podataka o potrošnji vazduha pod pritiskom izmerenih pomoću AirBOX portabilne laboratorije (tip GHDA-FQ-M-FDMJ-A) kompanije Festo.

ShareBOX koristi CodeIgniter frejmvork. Više informacija potražite na https://www.codeigniter.com/docs

DOI: https://doi.org/10.24867/01GI00Ungar


# INSTALACIJA

 - Neophodni softverski paketi

   - Apache (https://httpd.apache.org/docs/current/install.html)
   - MySQL (http://dev.mysql.com/doc/refman/5.7/en/installing.html)
   - PHP (http://php.net/manual/en/install.php)
   - Composer (https://getcomposer.org/download/)
   - Node (https://docs.npmjs.com/getting-started/installing-node)
   - Bower (https://bower.io/#install-bower)

   Pojedinačna instalacija i podešavanje ovih paketa može biti prilično kompleksna i vremenski zahtevna, zato je autor kreirao instalacioni skript za automatizovanu instalaciju koristeći Vagrant, softverski paket za upravljanje virtuelnim mašinama. Instalacijom [Vagrant softverskog paketa](https://www.vagrantup.com/downloads.html) proces instalacije svih ostalih neophodnih paketa se svodi na izvršavanje sledeće komande u osnovnom direktorijumu web aplikacije: `vagrant up`

 - Podešavanje domena
   Da bi aplikacija bila dostupna putem interneta, neophodno je
   obezbediti javnu IP adresu, odnosno domen, a zatim podesiti
   `base_url` u public/application/config/config.php`


# ShareBOX API v1.0.0

[Dokumentacija za ShareBOX API](https://artbit.github.io/ShareBOX/)


# AUTOR

Djordje Ungar (mail@djordjeungar.com)

Ova aplikacija je master rad gore pomenutog autora. Aplikacija je završena u decembru 2014. godine.
