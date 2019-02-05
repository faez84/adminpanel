<?php
namespace App\Utils\Models;


use App\Entity\Book;
use Doctrine\Common\Persistence\ObjectRepository;

class Movie
{
    /** @var  \stdClass */
    protected $entity;

    /** @var  string */
    protected $aliasName = 'm';

    /** @var  ObjectRepository */
    protected $repository;

    /** @var  string */
    protected $namespace;

    public $fields = [
        ['name' => 'name', 'type' => 'TextType', 'list' => true, 'add' => true],
    ];

    public function listData()
    {
        return $this->repository->createQueryBuilder($this->aliasName)
            ->select($this->getListFieldsAsString())
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getListFieldsAsString(): string
    {
        return implode(',', array_map(
            function ($fields) {
                if (isset($fields['list']) && $fields['list'])
                    return $this->aliasName . '.' . $fields['name'];
                }, $this->fields));
    }

    public function getListFields(): array
    {
        return
            array_map(function ($fields) {
                if (isset($fields['list']) && $fields['list'])
                    return  $fields;
            }, $this->fields);
    }

    public function setRepository(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAddEditFields(): array
    {
        return $this->fields;
    }
}