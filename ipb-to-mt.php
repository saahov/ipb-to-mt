<?php
/*
Copyright Andrey Serebryakov, http://saahov.ru/
This script is distributed under the terms of the
GNU General Public License, version 2.
*/

header('Content-Type: text/plain');

// CONFIG
$hostname = "localhost"; // MySQL Host
$username = "username"; // MySQL Username
$password = "password"; // MySQL Password
$dbname = "db_name"; // MySQL
$url = 'http://example.com'; // Forum URL (without slash)
 
mysql_connect($hostname, $username, $password) OR DIE("Could not connect to MySQL"); 
mysql_select_db($dbname) or die(mysql_error()); 

// MySQL encoding (may be need to change this)
$query = mysql_query("SET NAMES 'utf8'");
// You can set LIMIT for posts (entries and comments)
$query = "
         SELECT * FROM `ibf_posts` AS p
         LEFT JOIN ibf_topics AS t ON p.topic_id = t.tid
         LEFT JOIN ibf_forums AS f ON f.id = forum_id
         INNER JOIN ibf_members AS m ON m.id = author_id 
         WHERE t.approved = 1 AND p.queued = 0
         ORDER BY topic_id ASC
         LIMIT 10000
         ";
$query = mysql_query($query) or die("Query Error: ".mysql_error());

// Print results
while ($row = mysql_fetch_array($query)) {
    
    $body = $row['post'];
    $body = str_replace ("<#EMO_DIR#>","default", $body);
    $body = str_replace ("style_emoticons/","$url/style_emoticons/", $body);
    // Strip HTML tags; allow tags, which is allowed by default (plus img) in MT
    $body = strip_tags($body, '<a><img><br><b><strong><i><p><em><ul><ol><li><blockquote><pre>');
    
    //print_r ($row);
    
    if ($row['new_topic'] == '1')
    {
        echo "\n" . '--------' . "\n" . 'AUTHOR: ' . $row['author_name'] . "\n";
        echo 'TITLE: ' . $row['21'] . "\n";
        echo 'CONVERT BREAKS: 0' . "\n";
        echo 'BASENAME: ' . $row['tid'] . "\n";
        echo 'STATUS: Publish' . "\n";
        echo 'ALLOW COMMENTS: 1' . "\n";
        echo 'ALLOW PINGS: 0' . "\n";
        echo 'PRIMARY CATEGORY: ' . $row['55'] . "\n";
        echo 'CATEGORY: ' . $row['55'] . "\n";
        echo 'DATE: ' . date("m/d/Y H:i:s", $row['post_date']) . "\n";
        echo '-----' . "\n" . 'BODY:' . "\n" . $body . "\n" . '-----' . "\n";
        echo 'EXTENDED BODY:' . "\n\n" . '-----' . "\n";
        echo 'EXCERPT:' . "\n\n" . '-----' . "\n";
        echo 'KEYWORDS:' . "\n\n" . '-----' . "\n\n";
    }
    elseif ($row['queued' == '0']) {
        echo 'COMMENT:' . "\n";
        echo 'AUTHOR: ' . $row['author_name'] . "\n";
        echo 'EMAIL: ' . $row['email'] . "\n";
        echo 'IP: ' . $row['ip_address'] . "\n";
        echo 'URL: ' . "\n";
        echo 'DATE: ' . date("m/d/Y H:i:s", $row['post_date']) . "\n";
        echo $body . "\n" . '-----' . "\n";
    }
}

?>
