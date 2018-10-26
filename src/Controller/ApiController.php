<?php
namespace App\Controller;

use App\Entity\MCategory;
use App\Controller\Base\BaseController;
use App\Form\MCategoryType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
/**
 * @Route("/api/test")
 */
class ApiController extends BaseController
{
    /**
     * @Route("/", name="api_index")
     */
    public function index(Request $request, TranslatorInterface $translator)
    {
        return $translator->trans('test');
        $queryBuilder = $this->getQueryBuilder(MCategory::class);
        $paginator = $this->createPaginator($request, $queryBuilder->getQuery());
        return $this->getPaginationData($paginator);
    }
    
    /**
     * @Route("/insert", name="api_insert", methods={"GET","POST"})
     */
    public function insert(Request $request, ValidatorInterface $validator)
    {
        $MCategory = new MCategory(); // membuat object MCategory
        if($request->getMethod() == Request::METHOD_POST) { // jalankan jika method POST
            $form = $this->createForm(MCategoryType::class, $MCategory); // membuat object form untuk menangkap inputan dari user
            $MCategory = $this->processForm($MCategory, $form, $request); // memanggil function proccessForm yang ada di BaseController untuk memproses data input dari user
            $valid = $MCategory instanceof MCategory;   // check apakah data yang dikirim valid / tidak
            if(!$valid){ // kirimkan pesan error jika data yang dikirim tidak valid
                return $this->getResponseSuccess($MCategory);
            }

            $result =["status" => true, "messages" => 'data saved successfully'];
            return $this->getResponseSuccess($MCategory);
        }
        $form = $this->createFormApi(MCategoryType::class, $MCategory); // membuat data form untuk ditampilkan ke user
        return $form;
    }
    
    /**
     * @Route("/update/{id}", name="api_update", methods={"GET","POST"})
     */
    public function update(MCategory $MCategory, Request $request)
    {
        if($request->getMethod() == Request::METHOD_POST) { // jalankan jika method == POST
            $form = $this->createForm(MCategoryType::class, $MCategory); // membuat object form untuk melakukan proses data
            $MCategory = $this->processForm($MCategory, $form, $request); // memanggil funcgsi processForm di BaseController untuk memproses data
            $valid = $MCategory instanceof MCategory;
            if(!$valid){
                return $this->getResponseSuccess($MCategory); // tampilkan pesan error jika ada error
            }

            $result =["status" => true, "messages" => 'data saved successfully'];
            return $this->getResponseSuccess($MCategory);
        }
        $form = $this->createFormApi(MCategoryType::class, $MCategory);
        return $this->getResponseSuccess($form);
    }
    
    /**
     * @Route("/delete/{id}", name="api_delete", methods={"DELETE"})
     */
    public function deleted(MCategory $MCategory)
    {
        // menghapus data 
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($MCategory);
        $entityManager->flush();
        $data = ['status' => true, 'messages' => 'data berhasil dihapus'];
        return $this->getResponseSuccess($data);
    }
}
