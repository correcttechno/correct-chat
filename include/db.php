<?php
function connect_db()
{
    $bag=new PDO('mysql:host='.$database['host'].';dbname='.$database['db_name'].';charset=utf8;',$database['username'],$database['password']);
    return $bag;
}
function disconnect_db()
{
    $bag=null;
    return 'DATABASEYE BAGLANTI KESILDI';
}
?>