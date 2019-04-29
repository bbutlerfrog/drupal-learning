<?php 
namespace Drupal\track_changes\Entity;


use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;


/**
 * Defines the Change entity
 * 
 * 
 * @ContentEntityType(
 * id = "change",
 * label = "Changes",
 * base_table = "changes",
 * entity_keys = {
 *   "id" = "id",
 *   "uuid" = "uuid",
 *   },
 * )
 * 
 */


class Change extends ContentEntityBase implements ContentEntityInterface {

    public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

        $fields['id'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('ID'))
            ->setDescription(t('The ID of the change'))
            ->setReadOnly(TRUE);

        $fields['uuid'] = BaseFieldDefinition::create('uuid')
            ->setLabel(t('UUID'))
            ->setDescription(t('The UUID of the change'))
            ->setReadOnly(TRUE);

        $fields['uid'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('User ID'))
            ->setDescription(t('The User for who were are tracking this change'));    
        
        $fields['was_seen'] = BaseFieldDefinition::create('boolean')
            ->setLabel(t('Change was seen'))
            ->setDescription(t('This change was seen by the user'));
        
        return $fields;
    }

}