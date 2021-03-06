<?php
require '../../../vendor/autoload.php';

use piwidict\Piwidict;
//use piwidict\sql\{TLang, TPage, TPOS, TRelationType};
//use piwidict\widget\WForm;

require '../config_examples.php';
require '../config_password.php';

include(LIB_DIR."header.php");

// $pw = new Piwidict();
Piwidict::setDatabaseConnection($config['hostname'], $config['user_login'], $config['user_password'], $config['dbname']);
$link_db = Piwidict::getDatabaseConnection();

$wikt_lang = "ru"; // Russian language is the main language in ruwikt (Russian Wiktionary)
Piwidict::setWiktLang ($wikt_lang);

$lang_id = TLang::getIDByLangCode("ru");
$pos_id = TPOS::getIDByName("noun");

$proper_noun_word = array();
//$counter = 0; 
    
$query = "SELECT page_title,id as page_id FROM page WHERE id in (SELECT page_id FROM lang_pos where lang_id=$lang_id and pos_id=$pos_id) order by page_title";
$result = $link_db -> query_e($query,"Query failed in file <b>".__FILE__."</b>, string <b>".__LINE__."</b>");

while ($row = $result -> fetch_object()) {
    $word = $row->page_title;
    $l_word = mb_strtolower($word);

    if ($word != $l_word) {
      $proper_noun_word[] = $word; 
    }
}

print "<hr><p><b>There are ".sizeof($proper_noun_word).
    " proper nouns </b><br> (вычислено по существительным, это <i>предположительно</i> <b>имена собственные</b>, а точнее те слова, для которых:<br> нижний_регистр (слово) != слово)\n";

$fh = fopen('pos_proper_noun.txt','w');

ksort($proper_noun_word);
foreach ($proper_noun_word as $word) {
    fwrite($fh, "$word\n");        
}
print "<p>done.\n";

include(LIB_DIR."footer.php");
