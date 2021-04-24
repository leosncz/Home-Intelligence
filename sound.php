<?php
if($_GET['audio'] != "")
{
echo $_GET['audio'] . ' added to config file';
$systemcall = 'cd /var/www/html/scripts/ && echo "' . $_GET['audio'] . '" > audioState';
echo 'command is : '.$systemcall;
echo shell_exec($systemcall);
header('Location: settings.php');
}
else
{
echo 'no audio parameter included';
}
?>
