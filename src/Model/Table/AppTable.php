<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\EventInterface;
use ArrayObject;

class AppTable extends Table
{
    // This function will trim and add slashes to all string fields
    protected function cleanStringFields($entity)
    {
        foreach ($entity->toArray() as $field => $value) {
            if (is_string($value)) {
                $entity->set($field, addslashes(trim($value)));
            }
        }
    }

    // Before save lifecycle callback
    public function beforeSave(EventInterface $event, $entity, ArrayObject $options)
    {
        $this->cleanStringFields($entity);
    }
}
