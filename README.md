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

Dokumentacija za ShareBOX API

- [Grupe](#grupe)
	- [Brisanje grupe](#brisanje-grupe)
	- [Izbacivanje korisnika iz grupe](#izbacivanje-korisnika-iz-grupe)
	- [Informacije o datoj grupi](#informacije-o-datoj-grupi)
	- [Spisak korisnika koji pripadaju datoj grupi](#spisak-korisnika-koji-pripadaju-datoj-grupi)
	- [Spisak grupa](#spisak-grupa)
	- [Izmena date grupe](#izmena-date-grupe)
	- [Dodavanje korisnika u grupu](#dodavanje-korisnika-u-grupu)
	
- [Korisnici](#korisnici)
	- [Brisanje datog korisnika](#brisanje-datog-korisnika)
	- [Spisak svih korisnika](#spisak-svih-korisnika)
	- [Informacije o datom korisniku](#informacije-o-datom-korisniku)
	- [Izmena uloge datog korisnika](#izmena-uloge-datog-korisnika)
	- [Pretraga korisnika](#pretraga-korisnika)
	
- [Merenja](#merenja)
	- [Brisanje merenja](#brisanje-merenja)
	- [Brisanje odabranog reda za dato merenje](#brisanje-odabranog-reda-za-dato-merenje)
	- [Spisak svih merenja](#spisak-svih-merenja)
	- [Informacije o datom merenju](#informacije-o-datom-merenju)
	- [Prikaz podataka za grafikon](#prikaz-podataka-za-grafikon)
	- [Prikaz jednog reda iz tabele podataka za dato dinamicko merenje](#prikaz-jednog-reda-iz-tabele-podataka-za-dato-dinamicko-merenje)
	- [Prikaz jednog reda iz tabele podataka za dato staticko merenje](#prikaz-jednog-reda-iz-tabele-podataka-za-dato-staticko-merenje)
	- [Prikaz svih podataka za dato dinamicko merenje](#prikaz-svih-podataka-za-dato-dinamicko-merenje)
	- [Prikaz svih podataka za dato staticko merenje](#prikaz-svih-podataka-za-dato-staticko-merenje)
	- [Kreiraj novo merenje](#kreiraj-novo-merenje)
	- [Uvoz CSV datoteke u dato merenje](#uvoz-csv-datoteke-u-dato-merenje)
	- [Dodavanje podataka u dato dinamicko merenje](#dodavanje-podataka-u-dato-dinamicko-merenje)
	- [Dodavanje podataka u dato staticko merenje](#dodavanje-podataka-u-dato-staticko-merenje)
	- [Ažuriraj postojeće merenje](#ažuriraj-postojeće-merenje)
	
- [Uloge](#uloge)
	- [Naziv uloge](#naziv-uloge)
	- [Spisak svih uloga](#spisak-svih-uloga)
	


# Grupe

## Brisanje grupe

Brisanje grupe

	DELETE /grupe/:id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator grupe         

-----------------------------------------------------------------


## Izbacivanje korisnika iz grupe

Izbacivanje korisnika iz grupe

	DELETE /grupe/:id/korisnici/:user_id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator grupe         

user_id    Number        Jedinstveni identifikator korisnika     

-----------------------------------------------------------------


## Informacije o datoj grupi



	GET /grupe/:id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator grupe         

-----------------------------------------------------------------


## Spisak korisnika koji pripadaju datoj grupi

Informacije o korisnicima koji pripadaju datoj grupi

	GET /grupe/:id/korisnici


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator grupe         

-----------------------------------------------------------------


## Spisak grupa



	GET /grupe


## Izmena date grupe

Izmena grupe

	POST /grupe/:id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator grupe         

name       String        Naziv grupe                             

-----------------------------------------------------------------


## Dodavanje korisnika u grupu

Dodaj korisnika u grupu

	PUT /grupe/:id/korisnici/:user_id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator grupe         

user_id    Number        Jedinstveni identifikator korisnika     

-----------------------------------------------------------------


# Korisnici

## Brisanje datog korisnika



	DELETE /korisnici/:id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator korisnika     

-----------------------------------------------------------------


## Spisak svih korisnika



	GET /korisnici


## Informacije o datom korisniku



	GET /korisnici/:id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator korisnika     

-----------------------------------------------------------------


## Izmena uloge datog korisnika



	PUT /korisnici/:id/uloga/:id_uloge


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator korisnika     

id_uloge   Number        Jedinstveni identifikator uloge         

-----------------------------------------------------------------


## Pretraga korisnika

Pretraga korisnika po imenu, prezimenu i korisničkom imenu

	DELETE /korisnici?query=:query


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
query      String        Upit za pretragu                        

-----------------------------------------------------------------


# Merenja

## Brisanje merenja



	DELETE /merenja/:id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator merenja       

-----------------------------------------------------------------


## Brisanje odabranog reda za dato merenje



	DELETE /merenja/:id/redovi/:row_id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator merenja       

row_id     Number        Jedinstveni identifikator reda          

-----------------------------------------------------------------


## Spisak svih merenja



	GET /merenja


## Informacije o datom merenju



	GET /merenja/:id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator merenja       

-----------------------------------------------------------------


## Prikaz podataka za grafikon

Dobavi podatke vezane za merenje potrebne za iscrtavanje grafikona

	GET /merenja/:id/tacke


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator merenja       

-----------------------------------------------------------------


## Prikaz jednog reda iz tabele podataka za dato dinamicko merenje



	GET /merenja/:id/redovi/:row_id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator merenja       

row_id     Number        Jedinstveni identifikator reda          

-----------------------------------------------------------------


## Prikaz jednog reda iz tabele podataka za dato staticko merenje



	GET /merenja/:id/redovi/:row_id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator merenja       

row_id     Number        Jedinstveni identifikator reda          

-----------------------------------------------------------------


## Prikaz svih podataka za dato dinamicko merenje



	GET /merenja/:id/redovi


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator merenja       

-----------------------------------------------------------------


## Prikaz svih podataka za dato staticko merenje



	GET /merenja/:id/redovi


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator merenja       

-----------------------------------------------------------------


## Kreiraj novo merenje



	POST /merenja/


### Parameters

-------------------------------------------------------------------
Naziv        Tip           Opis                                    
------------ ------------- ----------------------------------------
group_id     Number        Jedinstveni identifikator grupe         

name         String        Naziv merenja                           

description  String        Opis merenja                            

-------------------------------------------------------------------


## Uvoz CSV datoteke u dato merenje



	POST /merenja/:id/datoteka


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        Jedinstveni identifikator merenja       

file       File          CSV datoteka                            

-----------------------------------------------------------------


## Dodavanje podataka u dato dinamicko merenje



	POST /merenja/:id/redovi


### Parameters

------------------------------------------------------------------------
Naziv      Tip           Opis                                           
---------- ------------- -----------------------------------------------
id         Number        Jedinstveni identifikator merenja              

time       Number        Vreme u sekundama proteklo od pocetka merenja  

flow       Number        Protok [l/min]                                 

pressure   Number        Pritisak [bar]                                 

------------------------------------------------------------------------


## Dodavanje podataka u dato staticko merenje



	POST /merenja/:id/redovi


### Parameters

---------------------------------------------------------------------------------------------------------
Naziv          Tip           Opis                                                                        
-------------- ------------- ----------------------------------------------------------------------------
id             Number        Jedinstveni identifikator merenja                                           

datetime       DateTime      Datum i vreme kada je izvrešeno očitavanje formata &quot;Y-m-d H:i:s&quot;  

ms             Number        Broj milisekundi od gore navedenog datuma i vremena                         

flow           Number        Protok [l/min]                                                              

flow_relative  Number        Protok [*l/min]                                                             

pressure       Number        Pritisak [bar]                                                              

density        Number        Gustina [kg/m3]                                                             

temperature    Number        Temperatura [°C]                                                            

volume         Number        Zapremina [m³]                                                              

---------------------------------------------------------------------------------------------------------


## Ažuriraj postojeće merenje



	POST /merenja/:id


### Parameters

-------------------------------------------------------------------
Naziv        Tip           Opis                                    
------------ ------------- ----------------------------------------
id           Number        Jedinstveni identifikator Merenja       

group_id     Number        Jedinstveni identifikator grupe         

name         String        Naziv merenja                           

description  String        Opis merenja                            

-------------------------------------------------------------------


# Uloge

## Naziv uloge



	GET /uloge/:id


### Parameters

-----------------------------------------------------------------
Naziv      Tip           Opis                                    
---------- ------------- ----------------------------------------
id         Number        jedinstveni identifikator uloge         

-----------------------------------------------------------------


## Spisak svih uloga



	GET /uloge




# AUTOR

Djordje Ungar (mail@djordjeungar.com)

Ova aplikacija je master rad gore pomenutog autora. Aplikacija je završena u decembru 2014. godine.
