<?php

/*
 * (c) Kristijan Grozdanovski <kgrozdanovski7@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Kernel\Persistence\Doctrine\EventListener;

use App\Kernel\Persistence\Doctrine\Entity\TimestampAuditInterface;
use App\Kernel\Persistence\Doctrine\Mapping\AppCustomGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Events::prePersist, method: 'prePersist', priority: 499)]
final readonly class CustomGeneratorPrePersistListener
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @return void
     * @throws \ReflectionException
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        // Get the entity's properties with Reflection
        $entityProperties = (new \ReflectionClass($entity))->getProperties();

        // Iterate each property of the object which has an attribute of type AppCustomGenerator
        foreach ($entityProperties as $property) {
            // Check if the property has the AppCustomGenerator attribute
            $customGeneratorAttribute = $property->getAttributes(AppCustomGenerator::class);
            // If the property does not have the AppCustomGenerator attribute, skip it
            if (empty($customGeneratorAttribute)) {
                continue;
            }

            // Get the generator class from the attribute
            $customGenerator = $customGeneratorAttribute[0]->newInstance();
            $generatorClass = $customGenerator->class;

            // Generate the value
            $generator = new $generatorClass();
            $generatedValue = $generator->generateValue($this->entityManager, $entity);

            // Set the generated value to the entity
            $property->setValue($entity, $generatedValue);
        }
    }
}
