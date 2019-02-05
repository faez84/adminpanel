<?php
namespace App\Utils\Models;


use App\Entity\Book;
use App\Entity\Domain;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Spider\Models\AbstractModel;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Application extends AbstractModel
{
    /** @var  \stdClass */
    protected $entity;

    /** @var  string */
    protected $aliasName = 'm';

    /** @var  ObjectRepository */
    protected $repository;

    /** @var  ManagerRegistry */
    protected $managerRegistry;

    /** @var  string */
    protected $namespace;

    protected $isDeleted = false;

    protected $options;

    public $fields = [
        ['name' => 'id', 'type' => 'IntegerType', 'list' => true, 'addEdit' => false],
        ['name' => 'name', 'type' => 'TextType', 'list' => true, 'addEdit' => true],
        ['name' => 'domains', 'type' => 'CollectionType', 'entry_type' => 'App\Form\DomainType',
            'list' => true, 'addEdit' => true, 'listFunction' => 'listDomains'],
    ];

    public function __construct()
    {
        $this->entity = new \App\Entity\Application();
        $this->modelName = 'Application';

    }
    public function callBackFunction(string $callbackFunction, int $modelId)
    {
        return $this->{$callbackFunction}($modelId);
    }

    public function listDomains(int $modelId)
    {
        return implode(',', array_map(
            function ($fields) {
                return  $fields['name'];
            }, $this->managerRegistry->getRepository(Domain::class)
            ->createQueryBuilder('d')
            ->select('d.name')
            ->innerJoin('d.applications', 'a')
            ->where('d.id = :modelId')
            ->setParameter('modelId', $modelId)
            ->getQuery()
            ->getResult()));

    }

    public function listData()
    {
        return $this->repository->createQueryBuilder($this->aliasName)
            ->select($this->getListFieldsAsString())
            ->getQuery()
            ->getResult();
    }

    public function getListFieldsAsString(): string
    {
        return implode(',', array_filter(array_map(
            function ($fields) {
                if (isset($fields['list']) && $fields['list']&& $fields['type'] !== 'CollectionType')
                    return $this->aliasName . '.' . $fields['name'];
                }, $this->fields), 'strlen'));
    }

    public function getListFields(): array
    {
        return
            array_map(function ($fields) {
                if (isset($fields['list']) && $fields['list'])
                    return  $fields;
            }, $this->fields);
    }

    public function getAddEditFields(): array
    {
        return
            array_filter(array_map(function ($fields) {
                if (isset($fields['addEdit']) && $fields['addEdit'])
                    return  $fields;
            }, $this->fields));
    }

    public function setRepository(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getModelName()
    {
        return $this->modelName;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setManagerRegistry(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function getGroupValidation(): string
    {
        return 'bazaar_store_admin';
    }

    public function delete(): bool
    {
        try {
            if ($this->isDeleted) {
                $this->entity->setDeleted = true;
                $this->managerRegistry->getManager()->flush($this->entity);

                return true;
            }
            $this->managerRegistry->getManager()->remove($this->entity);
            $this->managerRegistry->getManager()->flush($this->entity);
            return true;
        } catch(\Exception $exception) {
            return false;
        }
    }

    public function find(int $id)
    {
        $this->entity = $this->managerRegistry->getRepository(get_class($this->entity))->find($id);
    }

    public function getOptions(array $options): array
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
        return $this->options;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'host'       => 'smtp.example.org',
            'username'   => 'user',
            'password'   => 'pa$$word',
            'port'       => 25,
            'encryption' => null,
        ));

        $resolver->setAllowedTypes('host', 'string');
    }
}