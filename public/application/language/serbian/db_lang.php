<?php 
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This file contains the Serbian language localisations for the CodeIgniter database library.
 *
 * @author      Djordje Ungar (djordje@ungar.rs)
 * @copyright   Copyright (c) 2014-2016 Djordje Ungar
 * @version     1.0.0
 * @package     ShareBOX
 * @license http://opensource.org/licenses/MIT  MIT License
 */

$lang['db_invalid_connection_str'] = 'Nije moguće odrediti podešavanja baze podataka na osnovu string za konekciju koji ste poslali.';
$lang['db_unable_to_connect'] = 'Nije moguće povezati se na vaš server baze podataka koristeći dobavljena podešavanja.';
$lang['db_unable_to_select'] = 'Nije moguće odabrati navedenu bazu podataka: %s';
$lang['db_unable_to_create'] = 'Nije moguće kreirati navedenu bazu podataka: %s';
$lang['db_invalid_query'] = 'Upit koji ste poslali nije važeći.';
$lang['db_must_set_table'] = 'Morate postaviti tabelu baze poadtaka da se korissti sa vašim upitom.';
$lang['db_must_use_set'] = 'Morate da koristite "set" metod da ažurirate unos.';
$lang['db_must_use_index'] = 'Morate navesti indeks da odgovara batch updates.';
$lang['db_batch_missing_index'] = 'Jednom ili više redova dostavljenih za batch aćuriranje, nedostaje navedeni indexe.';
$lang['db_must_use_where'] = 'Ažuriranja nisu dozvoljena osim ako ne sadrže "where" klauzulu.';
$lang['db_del_must_use_where'] = 'Brisanja nisu dozvoljena osim ako sadrže "where" ili "like" klasuzulu.';
$lang['db_field_param_missing'] = 'Dobavljanje polja, zahteva naziv tabele kao parametar.';
$lang['db_unsupported_function'] = 'Ova osobina nije dostupna za bazu podataka koju koristite.';
$lang['db_transaction_failure'] = 'Neuspeh transakcije: Vrši se ponovno izvršavanje.';
$lang['db_unable_to_drop'] = 'Nije moguće isprazniti navedenu bazu podatakaUnable to drop the specified database.';
$lang['db_unsuported_feature'] = 'Nepodržana karakteristika platforme baze podataka koju koristite.';
$lang['db_unsuported_compression'] = 'Kompresioni format fajlova koji ste odabrali nije podržan od strane vašeg servera.';
$lang['db_filepath_error'] = 'Nemogućnost pisanja podataka na fajl putanji koju ste uneli.';
$lang['db_invalid_cache_path'] = 'Keš putanja koju ste uneli nije važeća ili upisiva.';
$lang['db_table_name_required'] = 'Naziv tabele je obavezan za tu operaciju.';
$lang['db_column_name_required'] = 'Za ovu operaciju je neophodno ime kolone.';
$lang['db_column_definition_required'] = 'Naziv kolone je obavezan za tu operaciju.';
$lang['db_unable_to_set_charset'] = 'Nije moguće promeniti skup karaktera: %s';
$lang['db_error_heading'] = 'Dogodila se greška sa bazom podataka';

/* End of file db_lang.php */
/* Location: ./system/language/english/db_lang.php */
