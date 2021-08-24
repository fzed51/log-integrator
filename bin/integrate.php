<?php
declare(strict_types=1);

namespace Integrate;

use DateTime;
use JsonException;
use PDO;

$filenames = glob('D:\work-dir\ws-aru\test\logs\event-*');
$dbname = __DIR__ . "/../db/data.sqlite";
$pdo = new PDO('sqlite:' . $dbname);


function integrateFile($filename, PDO $pdo)
{
    $index = 0;
    $handle = fopen($filename, 'rb');
    if ($handle) {
        while (!feof($handle)) {
            $line = fgets($handle);
            $index++;
            if ($line !== false) {
                integrateLine(basename($filename) . ":$index", $line, $pdo);
            }
        }
        fclose($handle);
    }
}

function integrateLine(string $reflog, string $line, PDO $pdo)
{
    try {
        $log = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
        //var_dump($log);
        $reflog = $log['channel'] . ':' . $reflog;
        if (!refLogExist($reflog, $pdo)) {
            newlog(
                $reflog,
                new DateTime($log['datetime']),
                (string)$log['level_name'],
                (string)$log['extra']['uid'],
                (string)$log['message'],
                $pdo,
                $log['extra']['file'] ?? null,
                $log['extra']['line'] ?? null,
                $log['extra']['class'] ?? null,
                $log['extra']['function'] ?? null,
                $log['extra']['ip'] ?? null,
                $log['extra']['url'] ?? null,
                $log['extra']['method'] ?? null,
            );
        }
    } catch (JsonException $exception) {
        msg($exception->getMessage() . " ($reflog");
    }
}

function newlog(
    string   $reflog,
    DateTime $time,
    string   $level,
    string   $refReq,
    string   $message,
    PDO      $pdo,
    ?string  $file = null,
    ?int     $line = null,
    ?string  $class = null,
    ?string  $function = null,
    ?string  $ip = null,
    ?string  $url = null,
    ?string  $method = null
)
{
    static $reqInsert = null;
    $fields = [
        'l_reflog',
        'l_time',
        'l_level',
        'l_refreq',
        'l_logdata',
        'l_create_at',
        'l_file',
        'l_line',
        'l_class',
        'l_function',
        'l_ip',
        'l_url',
        'l_method',
    ];
    $sql = "insert into li_logs("
        . implode(',', $fields)
        . ") values ("
        . implode(',', array_fill(0, count($fields), '?'))
        . ")";
    if ($reqInsert === null) {
        $reqInsert = $pdo->prepare($sql);
    }
    $reqInsert->execute([
        $reflog,
        $time->format('U.u'),
        $level,
        $refReq,
        $message,
        (new DateTime())->format('U.u'),
        $file,
        $line,
        $class,
        $function,
        $ip,
        $url,
        $method
    ]);

}

function msg(string $message)
{
    echo $message . PHP_EOL;
}

function refLogExist(string $reflog, PDO $pdo): bool
{
    static $reqSelect = null;
    $sql = 'select count(*) from li_logs where l_reflog = ?';
    if ($reqSelect === null) {
        $reqSelect = $pdo->prepare($sql);
    }
    $reqSelect->execute([$reflog]);
    $nb = $reqSelect->fetchColumn();
    return $nb > 0;
}

foreach ($filenames as $filename) {
    integrateFile($filename, $pdo);
}