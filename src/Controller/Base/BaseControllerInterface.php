<?php

namespace App\Controller\Base;

use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
/**
 * Description of BaseControllerInterface
 *
 * @author NUR HIDAYAT
 */
interface BaseControllerInterface {
    
    public function createFormApi(string $type, $data = null, array $options = array());
    
    public function getPaginationData(PagerFanta $paginator);
    
    public function getResponseSuccess($datas = array(), $response = Response::HTTP_OK);
    
    public function createPaginator(Request $request, Query $query): Pagerfanta;
    
    public function getQueryBuilder(string $entityClassName): QueryBuilder;
}
