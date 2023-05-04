<?php

$DBUSER = "u945187807_nova";
$DBPASSWD = "4g0nT3ch";
$DATABASE = "u945187807_remiseria";

$filename = "backup-" . date("YmdHis") . ".sql.gz";

$cmd = "mysqldump --opt --routines --user=$DBUSER --password=$DBPASSWD $DATABASE | gzip -f > ./back/$filename";
exec( $cmd );

