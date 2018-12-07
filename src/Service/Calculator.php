<?php // src/Service/Calculator.php

/**
 * Description of Calculator
 *
 * @author NUR HIDAYAT
 */

namespace App\Service;

use Psr\Log\LoggerInterface; // tambahkan class LoggerInterface

class Calculator {
    
    private $logger; // buat variabel $logger
    
    public function __construct(LoggerInterface $logger) { // buat fungsi __construct dengan memasukkan class LoggerInterface sebagai parameter
        $this->logger = $logger;
    }
    public function tambah($a, $b)
    {
        $this->logger->info('tambah : '.$a.' + '.$b.' = '. ($a + $b)); // menggunakan logger
        return $a + $b;
    }
    
    public function kurang($a, $b)
    {
        $this->logger->info('kurang : '.$a.' - '.$b.' = '. ($a - $b)); // menggunakan logger
        return $a - $b;
    }
    
    public function kali($a, $b)
    {
        $this->logger->info('kali : '.$a.' * '.$b.' = '. ($a * $b)); // menggunakan logger
        return $a * $b;
    }
    
    public function bagi($a, $b)
    {
        $this->logger->info('bagi : '.$a.' / '.$b.' = '. ($a / $b)); // menggunakan logger
        return $a/$b;
    }
    
    public function percen($percen, $nilai)
    {
        return $nilai * $percen / 100;
    }
}
