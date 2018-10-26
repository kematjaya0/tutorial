<?php

namespace App\Controller\Base;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use App\Resource\PaginationResource;
use App\Service\SerializationService;
use App\Controller\Base\BaseControllerInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;


/**
 * Description of BaseController
 *
 * @author NUR HIDAYAT
 */
class BaseController extends FOSRestController implements BaseControllerInterface{
    
    /**
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     * @var SerializerInterface
     */
    protected $serializer;
    
    /**
     * @var SerializationService
     */
    protected $serializationService;
    
    /*
     * @var limit 
     */
    protected $limit = 10;
    
    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, SerializationService $serializationService)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->serializationService = $serializationService;
    }
    
    /**
     * @param type Form
     */
    public function createFormApi(string $type, $data = null, array $options = array())
    {
        $form = parent::createForm($type, $data, $options);
        $data = [];
        foreach($form as $k => $v) {
            $vars = $v->createView()->vars;
            unset($vars['form']);
            unset($vars['block_prefixes']);
            $data[$k] = $vars;
        }
        return $data;
    }
    
    protected function uploadFiles($obj, Form $form, Request $request)
    {
        // upload file 
        // $file = $request->files->get($form->getName()); // field type file
        // if($file){
        //    $fileUploader = $this->container->get('app.file_uploader');
        //    $result = $fileUploader->upload($file['images'], '/category');
        //    if($result){
        //        $obj->setImages($result);
        //    }
        //}
    }
    
    protected function processForm($obj, Form $form, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $con = $entityManager->getConnection();
        try{
            $con->beginTransaction();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $obj = $form->getData();
                
                $this->uploadFiles($obj, $form, $request);
                $entityManager->persist($obj);
                $entityManager->flush();
                $con->commit();
            }else{
                return $this->getErrors($form);
            }
        } catch (Exception $ex) {
            $con->rollback();
            return array("status" => false, "messages" => 'error : '.$ex->getMessage());
        }
        
        return $obj;
    }
    
    protected function getErrors($form) {
        $errors = array();
        foreach ($form as $k => $child) {
            foreach($child->getErrors() as $key => $error){
                $errors[$k][$key] = $error->getMessageTemplate();
            }
            if(isset($errors[$k])){
                $errors[$k] = implode(", ", $errors[$k]);
            }
        }
        if(!empty($errors)){
            return array("status" => false, "errors" => $errors);
        }else{
            return array("status" => false, "errors" => 'key name must :'. $form->getName());
        }
        return $errors;
    }
    
    /**
     * 
     * @param type $paginator
     * @return PagerFanta
     */
    public function getPaginationData(PagerFanta $paginator)
    {
        $data = $this->serialize(
                $paginator->getIterator()->getArrayCopy(),
                $this->serializationService->createBaseOnRequest()
            );
        $datas['data'] = $data;
        $pagination = PaginationResource::createFromPagerfanta($paginator);
        if($pagination) {
            $datas['pagination'] = $pagination->toJsArray();
        }
        return $datas;
    }
    
    /**
     * 
     * @param type $data
     * @param type $response
     * @return FOS\Controller\ControllerTrait
     */
    public function getResponseSuccess($datas = array(), $response = Response::HTTP_OK) 
    {
        $view = $this->view( $datas, $response);
        return $this->handleView($view);
    }
    
    /**
     * @param Request $request
     * @param Query $query
     *
     * @return Pagerfanta
     */
    public function createPaginator(Request $request, Query $query): Pagerfanta
    {
        //  Construct the doctrine adapter using the query.
        $adapter = new DoctrineORMAdapter($query);
        $paginator = new Pagerfanta($adapter);
        $paginator->setAllowOutOfRangePages(true);
        //  Set pages based on the request parameters.
        $paginator->setMaxPerPage($request->query->get('limit', $this->limit));
        $paginator->setCurrentPage($request->query->get('page', 1));

        return $paginator;
    }
    
    /**
     * {@internal}.
     *
     * @since Entities are messed up as hell!
     *
     * @param string $entityClassName
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(string $entityClassName): QueryBuilder
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('this')
            ->from($entityClassName, 'this');

        return $queryBuilder;
    }
    
    /**
     * @param array $data
     * @param SerializationContext $context
     *
     * @return array
     */
    protected function serialize($data, SerializationContext $context = null): array
    {
        return $this->serializer->toArray($data, $context);
    }
}
