<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TaxRatesFixture
 *
 */
class TaxRatesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'loc_type' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => 'county', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'loc_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'category_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'value' => ['type' => 'decimal', 'length' => 19, 'precision' => 9, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'year' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'MyISAM',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'loc_type' => 'county',
            'loc_id' => 1,
            'category_id' => 1,
            'value' => 1.124,
            'year' => 2011,
            'created' => null,
            'modified' => '2011-06-03 12:19:39'
        ],
        [
            'id' => 183,
            'loc_type' => 'state',
            'loc_id' => 14,
            'category_id' => 2,
            'value' => 3.4,
            'year' => 2011,
            'created' => null,
            'modified' => '2011-06-03 14:18:41'
        ],
        [
            'id' => 184,
            'loc_type' => 'state',
            'loc_id' => 13,
            'category_id' => 2,
            'value' => 5.0,
            'year' => 2011,
            'created' => null,
            'modified' => '2011-06-03 14:21:20'
        ],
        [
            'id' => 185,
            'loc_type' => 'county',
            'loc_id' => 1,
            'category_id' => 3,
            'value' => 2.22,
            'year' => 2011,
            'created' => null,
            'modified' => '2011-07-06 12:05:48'
        ],
        [
            'id' => 277,
            'loc_type' => 'county',
            'loc_id' => 93,
            'category_id' => 3,
            'value' => 1.54,
            'year' => 2011,
            'created' => null,
            'modified' => '2011-06-03 14:54:54'
        ],
        [
            'id' => 379,
            'loc_type' => 'county',
            'loc_id' => 93,
            'category_id' => 4,
            'value' => 6.5,
            'year' => 2017,
            'created' => null,
            'modified' => null
        ],
        [
            'id' => 380,
            'loc_type' => 'county',
            'loc_id' => 93,
            'category_id' => 5,
            'value' => 8.0,
            'year' => 2017,
            'created' => null,
            'modified' => null
        ],
        [
            'id' => 583,
            'loc_type' => 'state',
            'loc_id' => 13,
            'category_id' => 2,
            'value' => 3.75,
            'year' => 2017,
            'created' => '2017-10-09 20:20:03',
            'modified' => '2017-10-09 16:20:03'
        ],
        [
            'id' => 584,
            'loc_type' => 'state',
            'loc_id' => 14,
            'category_id' => 2,
            'value' => 3.23,
            'year' => 2017,
            'created' => '2017-10-09 20:20:03',
            'modified' => '2017-10-09 16:20:03'
        ],
        [
            'id' => 585,
            'loc_type' => 'county',
            'loc_id' => 93,
            'category_id' => 3,
            'value' => 1.68,
            'year' => 2017,
            'created' => '2017-10-09 20:29:15',
            'modified' => '2017-10-09 20:29:15'
        ],
        [
            'id' => 687,
            'loc_type' => 'county',
            'loc_id' => 1,
            'category_id' => 3,
            'value' => 1.74,
            'year' => 2017,
            'created' => '2017-10-09 20:58:48',
            'modified' => '2017-10-09 20:58:48'
        ],
        [
            'id' => 779,
            'loc_type' => 'county',
            'loc_id' => 1,
            'category_id' => 1,
            'value' => 1.624,
            'year' => 2017,
            'created' => '2017-10-09 21:34:22',
            'modified' => '2017-10-09 21:34:22'
        ]
    ];
}
