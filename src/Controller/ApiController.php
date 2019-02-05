<?php


namespace App\Controller;

use App\Entity\Application;
use App\Entity\Movie;
use App\Form\GeneralFormType;
use App\Utils\HelperClass;
use Spider\Models\AbstractModel;
use Spider\Models\ModelCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\Models;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ApiController extends AbstractController
{
    /** @var  AbstractModel */
    private $model;

    /**
     * @param Request $request
     * @param string $sectionName
     * @param string $modelName
     * @param string $action
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function globalAction(Request $request, string $sectionName, string $modelName, string $action)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException();
        }
        $modelCreator = new ModelCreator();
        $this->model = $modelCreator->getModel('App\\Utils\\Models\\'.$modelName);
        $this->model->setRepository($this->getDoctrine()->getRepository(get_class($this->model->getEntity())));
        $this->model->setManagerRegistry($this->getDoctrine());
        HelperClass::$model = $this->model;
        switch ($action) {
            case 'list' :
                return $this->listAction($request);
                break;
            case 'add':
                return $this->addAction($request);
                break;
        }
    }

    public function addAction(Request $request)
    {
        $form = $this->createForm(
            GeneralFormType::class,
            $this->model->getEntity(),
            ['configuration' =>  $this->model->getAddEditFields()]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data= $form->getData();
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($data);
             $entityManager->flush();

            return $this->redirectToRoute(
                'cpanel_globalroutes',
                [
                    'sectionName' => 'sdasd',
                    'modelName'=> $this->model->getModelName(),
                    'action' => 'list'
                ]
            );
        }
        $j = $form->createView();
        return $this->render('addEdit.html.twig', array(
            'form' => $form->createView(),
            'modelName' => (new \ReflectionClass($this->model))->getShortName(),
            'fields' => $this->model->getAddEditFields()
        ));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $data = $this->model->listData();
        return $this->render('index.html.twig', [
            'model' => $this->model,
            'data' => $data,
            'fields' => $this->model->getListFields(),
            'modelName' => (new \ReflectionClass($this->model))->getShortName()
        ]);
    }

    public function insert(Request $request)
    {
        $this->model-$this->insert();
    }

    public function index()
    {
        phpinfo();
    }

    public function number()
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}