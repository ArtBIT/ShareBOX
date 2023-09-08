<?php
    $faq_entries = array(
        array(
            'question' => 'Kako da kreiram novi korisnički nalog?',
            'answer'   => 'Ukoliko želite da registrujete novi korisnički nalog idite na <a href="/auth/register">stranicu za registrovanje</a>.<br>Prikazaće vam se forma {{slika(1,2.png)}}<br> Formu ćete popuniti na sledeći način:<br><ul><li>u prvo polje za unos unesite vaše novo korisničko ime</li><li>u drugo polje za unos unesite vaše ime</li><li>u treće polje za unos unesite vaše prezime</li><li>u četvrto polje za unos unesite vašu e-mail adresu</li><li>u peto polje za unos unesite vašu novu lozinku</li><li>u šestom polju za unos potvrdite vašu novu lozinku</li><li>kliknite na "nisam robot" i rešite jednostavan zadatak ukoliko to bude bilo potrebno</li></ul><br> Klikom na dugme <strong>Registruj se</strong>, na vašu, prethodno unetu e-mail adresu biće poslat konfirmacioni e-mail koji će sadržati link na koji je neophodno da kliknete kako biste potvrdili vašu registraciju. Nakon ove potvrde bićete prosledjeni na stranicu za prijavljivanje na sistem.'
        ),
        array(
            'question' => 'Kako da se prijavim na sistem?',
            'answer'   => 'Ukoliko želite da se prijavite na sistem idite na <a href="/auth/login">stranicu za prijavu</a>. Prikazaće vam se forma {{slika(2,1.png)}}<br>U prvo polje za unos možete uneti vašu e-mail adresu ili vaše korisničko ime, a u drugo polje unesite lozinku. Kada se ulogujete vi ste autentifikovani za sledećih 8 sat. Nakon 8 sati, bićete automatski odjavljeni. Ukoliko ne želite da budete automatski odjavljeni, označite opciju <strong>Zapamti me</strong>. Klikom na dugme <strong>Prijavi se</strong>, prijavićete se na sistem. Ukoliko nemate kreiran nalog, možete kreirati novi nalog klikom na vezu <strong>Registrujte se</strong>.'
        ),
        array(
            'question' => 'Ako ste zaboravili lozinku, kako da je resetujete?',
            'answer'   => 'Ukoliko ste zaboravili svoju lozinku, na formi za prijavu {{slika(3,1.png)}}<br> kliknite na vezu <strong>Zaboravljena lozinka</strong> koja će vas preusmeriti na formu prikazanoj na {{slika(4,4.png)}}<br> U polje za unos unesite ili vašu e-mail adresu ili vaše korisničko ime, a zatim kliknite na dugme <strong>Zahtev nove lozinke</strong>. Nakon toga na vašu e-mail adresu biće poslat e-mail koji će sadržati link na koji treba da kliknete, a koji će vas odvesti na novu formu za promenu lozinke (prikazanoj na {{slika(5,5.png)}}<br>U prvo polje za unos unesite novu lozinku, a u drugom polju za unos potvrdite tu novu lozinku, a zatim kliknite na dugme <strong>Promeni lozinku</strong>. Ukoliko ste uspešno promenili lozinku, prikazaće vam se poruka koja će sadržati vezu do forme za prijavu na sajt gde ćete se prijaviti koristeći novu lozinku. {{slika(6,1.png)}}'
        ),
    );
    if (!$user->has_role(User::ROLE_NONE)) {
        $faq_entries = array_merge($faq_entries, array(
            array(
                'question' => 'Kako da promenim lozinku na svom korisničkom nalogu?',
                'answer'   => 'Ako želite da promenite lozinku na vašem korisničkom nalogu neophodno je da se prijavite na sajt sa starom lozinkom, a zatim da kliknete na padajući meni u gornjem desnom uglu stranice sa ikonicom čoveka, i odaberete opciju <strong>Korisnički Profil</strong> {{slika(7,3.png)}}<br>'
            ),
            array(
                'question' => 'Šta je statičko merenje?',
                'answer'   => 'Statičko merenje se koristi da bi se izmerila potrošnja vazduha tokom pasivnog rada sistema, odnosno rada sistema kada nema potrošnje vazduha. Statičkim merenjem se meri curenje vazduha u sistemu.'
            ),
            array(
                'question' => 'Šta je dinamičko merenje?',
                'answer'   => 'Dinamičkim merenjem se meri potrošnja vazduha u određenom režimu rada.'
            ),
            array(
                'question' => 'Kako da kreiram novo merenje?',
                'answer'   => 'Kreiranje novog statičkog ili dinamičkog merenja je jednostavno i vrlo slično. Kliknite na dugme <strong>Novo merenje</strong>, <br> {{slika(8,6.png)}}<br> prikazaće vam se forma za unos novog merenje {{slika(9,7.png)}}<br>U prvom polju za unos unesite naziv merenja, u drugom polju za unos opišite merenje. U padajućem meniju pod nazivom <strong>Grupa</strong>, odaberite grupu u kojoj će merenje koje kreirate biti vidljivo. Zatim ispod naslova <strong>CSV datoteka</strong>, odaberite datoteku sa merenjima izvezenih sa AIRBOX-a. Klikom na dugme <strong>Novo merenje</strong>, novo merenje se prikazuje u listi merenja, <br>{{slika(10,6.png)}}<br>'
            ),
            array(
                'question' => 'Kako da izvezem statičko (ili dinamičko) merenje u .CSV datoteku?',
                'answer'   => 'Podatke vezano za merenje možete da izvezete u <a href="http://internetzanatlija.com/2012/06/18/kako-da-otvorite-csv-format-u-excel-u/">CSV datoteku</a> i da podatke dalje obradite u nekom programu za tabelarnu obradu podataka kao što je Excel.<br>{{slika(11,10b.png)}}<br>'
            ),
            array(
                'question' => 'Kako da obrišem statičko (ili dinamičko) merenje?',
                'answer'   => 'Samo korisnici kojima je dodeljena privilegija brisanja merenja mogu da bršu merenja jednostavnim klikom na dugme <strong>Obriši</strong> koje se nalazi u svakom redu liste merenja, <br>{{slika(12,9.png)}}<br>'
            ),
            array(
                'question' => 'Kako da vidim grafički prikaz odredjenog merenja?',
                'answer'   => 'U svakom redu liste merenja nalazi se dugme <strong>Prikaži</strong> uz pomoć kojeg možete da vidite grafički prikaz svakog merenja u listi merenja, <br>{{slika(13,10a.png)}}<br>'
            ),
            array(
                'question' => 'Želim da se fokusiram samo na deo grafikona, kako to da postignem?',
                'answer'   => 'Ukoliko želite da uvećate ili smanjite grafikon, postavite miš preko samog grafikona i pomerite točkić na mišu. Pomeranje točkića prema sebi će kao posledicu imati smanjenje grafikona, dok će pomeranje točkića od sebe povećati grafikon.<br>{{slika(14, chart.zoom.gif)}}<br>Ukoliko želite da pomerite grafikon, postavite miš preko samog grafikona, kliknite i pomerajte miš.<br>{{slika(15, chart.translate.gif)}}<br>'
            ),
            array(
                'question' => 'Želim da se fokusiram samo na deo merenja, kako da skaliram/transliram jednu osu??',
                'answer'   => 'Ukoliko želite da uvećate ili smanjite jednu osu, postavite miš preko željene ose i pomerite točkić na mišu. Pomeranje točkića prema sebi će kao posledicu imati smanjenje funkcije vezane za tu osu, dok će je pomeranje točkića od sebe povećati.<br>{{slika(16, axes.scale.gif)}}<br>Ukoliko želite da translirate prikaz funkcije duž jedne ose, postavite miš preko željene ose, kliknite i pomerajte miš.<br>{{slika(17, axes.translate.gif)}}<br>'
            ),
            array(
                'question' => 'Gde mogu da pronadjem dokumentaciju za API?',
                'answer'   => 'Dokumentacija za API se nalazi <a href="/api/dokumentacija">ovde</a>.'
            ),
        ));
    }
    if ($user->has_role(User::ROLE_SYSTEM_ADMINISTRATOR, User::ROLE_DEVELOPER, User::ROLE_USER_ADMINISTRATOR)) {
        $faq_entries = array_merge($faq_entries, array(
            array(
                'question' => 'Kako da kreiram novu korisničku grupu?',
                'answer'   => 'Ukoliko želite da kreirate novu korisničku grupu, na vrhu stranice u padajućem meniju pod nazivom <strong>Korisnici</strong>, odaberite opciju <strong>Nova Grupa</strong>, <br>{{slika(18,11.png)}}<br> nakon čega će vam se prikazati forma za kreiranje nove korisničke grupe, <br>{{slika(19,12.png)}}<br>U polje za unos upišite ime nove grupe, a zatim kliknite na dugme <strong>Snimi</strong>.'
            ),
            array(
                'question' => 'Kako da dodam korisnika u korisničku grupu?',
                'answer'   => 'Ukoliko iz padajućeg menija pod nazivom <strong>korisnici</strong>, odaberete opciju <strong>Grupe</strong>, na stranici će se prikazati lista svih kreiranih grupa. Klikom na dugme <strong>Izmeni</strong> neke odredjene grupe, u prozoru će se prikazati odabrana korisnička grupa sa listom korisnika, <br>{{slika(20,14.png)}}<br>Kako bi dodali novog korisnika, na dnu ove stranice se nalazi opcija za dodavanje novog korisnika. Jednostavnim upisivanjem imena i prezimena korisnika u polje za unos, kao i potvrdom na dugme <strong>Dodaj korisnika</strong>, novi korisnik će biti dodat i prikazan u korisničkoj listi prikazanoj iznad polja za unos novog korisnika.'
            ),
            array(
                'question' => 'Kako da uklonim korisnika iz korisničke grupe?',
                'answer'   => 'Na stranici koja prikazuje odredjenu korisničku grupu sa listom korisnika, za svakog korisnika u listi se nalazi dugme <strong>Izbaci</strong> koje omogućava uklanjanje korisnika iz grupe. Ova opcija neće obrisati korisnika zauvek već će samo ukloniti korisnika iz grupe.<br>{{slika(21,15.png)}}<br>'
            ),
        ));
    }

    // Expand "{{slika(index, src)}}" using the following function:
    $template_data = array(
        'slika' => function ($index, $src) {
            return "(<label>Slika $index.</label>)<br><figure class='figure'><img src='/assets/images/pomoc/{$src}' class='figure-img img-fluid small' alt='Help image'><figcaption class='figure-caption text-center'>Slika {$index}.</figcaption></figure>";
        },
    );

?>
<?php $this->load->view('partials/pagetitle', array('title' => 'Pomoć')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Često postavljana pitanja
            </div>
            <div class="panel-body">
                <div id="faq" role="faq">
                    <?php foreach ($faq_entries as $faq_entry) {
    ?>
                        <div class="faq-c">
                            <div class="faq-q"><span class="faq-t">+</span><?= $faq_entry['question']; ?></div>
                            <div class="faq-a">
                                <p><?= str_template($faq_entry['answer'], $template_data); ?></p>
                            </div>
                        </div>
                    <?php 
} ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->assets->add_css_file('faq.css'); ?>
<?php $this->assets->add_js_file('jquery-sieve/dist/jquery.sieve.min.js'); ?>
<?php $this->js_begin(); ?>
    $(document).ready(function() {
        // Initialize click to toggle FAQ item
        $(".faq-q").click( function () {
          var container = $(this).parents(".faq-c");
          var answer = container.find(".faq-a");
          var trigger = container.find(".faq-t");
          
          answer.slideToggle(200);
          
          if (trigger.hasClass("faq-o")) {
            trigger.removeClass("faq-o");
          }
          else {
            trigger.addClass("faq-o");
          }
        });

        $("figure img").click( function () {
            $(this).toggleClass('small');
        });
        // Initialize Search
        var searchTemplate = '<div class="input-group col-lg-2"><input name="search" type="text" class="form-control" placeholder="Upit za pretragu..." value=""><span class="input-group-btn"><button class="btn btn-default" type="submit">Pretraži</button></span></div>';
        $("[role=faq]").sieve({ searchTemplate: searchTemplate, itemSelector: ".faq-c" });
    });
<?php $this->js_end(DOCUMENT_BODY_END);
