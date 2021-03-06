<?php
require '../../../vendor/autoload.php';

use piwidict\Piwidict;
use piwidict\sql\{TLang, TPage, TPOS, TRelationType};
use piwidict\widget\WForm;

require '../config_examples.php';
require '../config_password.php';

// $pw = new Piwidict();
Piwidict::setDatabaseConnection($config['hostname'], $config['user_login'], $config['user_password'], $config['dbname']);
$link_db = Piwidict::getDatabaseConnection();

$wikt_lang = "ru"; // Russian language is the main language in ruwikt (Russian Wiktionary)
Piwidict::setWiktLang ($wikt_lang);

$lang_id = TLang::getIDByLangCode("ru");
$pos_ids = array(
	TPOS::getIDbyName('noun') => "сущ", 
	TPOS::getIDbyName('adjective') => "прил",
	TPOS::getIDbyName('verb') => "глаг", 
	TPOS::getIDbyName('adverb') => "нареч"
);
$relation_type_id = (int)TRelationType::getIDByName("synonyms");

$fh = fopen('ru.wiktionary.with.synonyms.json','w');

$query = "SELECT page_title, lang_pos.id as id, pos_id  FROM lang_pos,page WHERE lang_pos.page_id = page.id AND lang_id=$lang_id order by page_title";
print "<p>$query";
$result = $link_db -> query_e($query,"Query failed in file <b>".__FILE__."</b>, string <b>".__LINE__."</b>");

while ($row = $result -> fetch_object()) {
    $def_arr = $synonyms = array();
    $is_exists_syn = 0;
    $query = "SELECT text, meaning.id as meaning_id FROM meaning, wiki_text WHERE lang_pos_id=".(int)$row->id." and meaning.wiki_text_id=wiki_text.id order by meaning_n";
    $result_meaning = $link_db -> query_e($query,"Query failed in file <b>".__FILE__."</b>, string <b>".__LINE__."</b>");

    if ($link_db -> query_count($result_meaning)) {
	while ($row_meaning = $result_meaning -> fetch_object()) {
            $def_arr[] =  PWString::escapeQuotes($row_meaning->text);
	    
	    $query = "SELECT text FROM relation, wiki_text WHERE relation.wiki_text_id=wiki_text.id AND relation.meaning_id=".(int)$row_meaning->meaning_id." AND relation_type_id=".(int)$relation_type_id;
//print "<p>$query";
	    $result_relation = $link_db -> query_e($query,"Query failed in file <b>".__FILE__."</b>, string <b>".__LINE__."</b>");
	    $synonym = array();
	    while ($row_relation = $result_relation -> fetch_object()) {
	    	$synonym[] = $row_relation->text;
 	        $is_exists_syn = 1;
	    }
	    if (sizeof($synonym))
	        $synonyms[] = '"'.join('", "',$synonym).'"';
	    else $synonyms[] = '';
	}

        if (isset($pos_ids[$row->pos_id])) $pos_name = $pos_ids[$row->pos_id]; 
        else $pos_name='';

	$line = '{"word":["'.PWString::escapeQuotes($row->page_title).'"], "POS":"'.$pos_ids[$row->pos_id].'"';
	if ($is_exists_syn)
	    $line .=  ', "synonym":[['.join('],[',$synonyms).']]';
	
     	fwrite($fh,$line.', "definition":["'.join('","',$def_arr)."\"]}\n");
    }
}

fclose($fh);
?>
<p>done.