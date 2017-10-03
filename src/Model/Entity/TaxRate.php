<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TaxRate Entity
 *
 * @property int $id
 * @property string $loc_type
 * @property int $loc_id
 * @property int $category_id
 * @property float $value
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\DataCategory $data_category
 */
class TaxRate extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'loc_type' => true,
        'loc_id' => true,
        'category_id' => true,
        'value' => true,
        'created' => true,
        'modified' => true,
        'loc' => true,
        'category' => true,
        'year' => true
    ];
}
