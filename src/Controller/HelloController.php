<?php //src/Controller/HelloController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller; // tambahkan class Controller
/**
 * Description of HelloController
 *
 * @author NUR HIDAYAT
 */
class HelloController extends Controller{ // class diubah sehingga menjadi extends Controller
    
    /**
     * 
     * @Route("/hello", name="hello")
     */
    public function index(LoggerInterface $logger, Request $request)
    {
        $a = 10; // variabel pertama yang akan dihitung
        $b = 100;// variabel kedua yang akan dihitung
        $calculator = $this->get('app.calculator'); // memanggil service calculator sesuai nama di service.yml
        $number = $calculator->tambah($a, $b); // memanggil fungsi tambah
        $logger->info(date('d M Y H:i:s').' : '.$request->getClientIp().' access path => '.$request->getRequestUri());
        return new Response(
            '<html><body>Lucky '.$a.' + '.$b.' : '.$number.'</body></html>'
        );
    }
}
