<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Task;

class ProjectRepository extends ServiceEntityRepository{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Project::class);
    }

}