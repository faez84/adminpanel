<?php


namespace App\Controller;

use App\Entity\Application;
use App\Entity\Movie;
use App\Form\GeneralFormType;
use App\Utils\HelperClass;
use GuzzleHttp\Client;
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
use Psr\Log\LoggerInterface;

class AdminController extends AbstractController
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
            // throw new AccessDeniedException();
        }
        $modelCreator = new ModelCreator();
        $this->model = $modelCreator->getModel('App\\Utils\\Models\\' . $modelName);
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
            case 'del':
                return $this->deleteAction($request, $request->request->get('idF'));
                break;
        }
    }

    public function addAction(Request $request)
    {
        $form = $this->createForm(
            GeneralFormType::class,
            $this->model->getEntity(),
            ['configuration' => $this->model->getAddEditFields()]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute(
                'cpanel_globalroutes',
                [
                    'sectionName' => 'sdasd',
                    'modelName' => $this->model->getModelName(),
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
        $freqs = [];
        $arr = [
            5,
            3,
            1,
            1,
            2,
            2,
            2,
            2,
            4
        ];

        foreach ($arr as $item) {
            $freqs = $this->addToFreqs($freqs, $item);
        }


        for ($j = sizeof($freqs) - 1; $j > 0; $j--) {
            for ($i = 0; $i < $j; $i++) {
                if (($freqs [$i][0] > $freqs [$j][0]) ) {

                        $temp = $freqs[$j];
                        $freqs[$j] = $freqs[$i];
                        $freqs[$i] = $temp;
                }
                if (($freqs [$j][1] < $freqs [$i][1])) {
                    $temp = $freqs[$i];
                    $freqs[$i] = $freqs[$j];
                    $freqs[$j] = $temp;
                }
            }
        }

        foreach ($freqs as $key => $ii) {
            echo $key . '=>' . $ii[0] . ' ' . $ii[1] . '<br>';
        }
        echo '<br>';
       //print_r($freqs);

        return new Response('sasd');
//        $data = $this->model->listData();
//        return $this->render('index.html.twig', [
//            'model' => $this->model,
//            'data' => $data,
//            'fields' => $this->model->getListFields(),
//            'modelName' => (new \ReflectionClass($this->model))->getShortName()
//        ]);
    }
public function addToFreqs(array $arr, int $number)
{
    foreach ($arr as $key =>$item) {
        if ($item[0] == $number) {
            $arr[$key][1]++;
            return $arr;
        }
    }
    $arr[] = [$number, 1];
    return $arr;
}
    public function deleteAction(Request $request, int $id)
    {
        $this->model->find($id);
        $response = new Response('', Response::HTTP_NOT_FOUND);

        if ($this->model->delete()) {
            $response = new Response('', Response::HTTP_NO_CONTENT);
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function luckynamber(LoggerInterface $logger)
    {
        $logger->info('I just got the logger');
        $logger->error('An error occurred');

        $logger->critical('I left the oven on!', [
            // include extra "context" info in your logs
            'cause' => 'in_hurry',
        ]);

        /** @var Models\Application $application */
        $application = new Models\Application();
        $op = $application->getOptions([
            'host' => 'df'
        ]);
//        $rcleint = new \Symfony\Bundle\FrameworkBundle\Client();
//        $client = new Client();
//
//        $response = $client->request('GET', 'http://localhost:8045/api/hi?apikey=12345');
//
//        //$response = Request::create('http://localhost:8045/api/hi?apikey=12345');
//        $body = $response->getBody()->getContents();

        return new Response(json_encode($op, true));
    }
}