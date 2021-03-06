<?php
namespace App\Shell;

use App\Model\Entity\TaxRate;
use App\Model\Table\TaxRatesTable;
use Cake\Console\Shell;
use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;
use Queue\Model\Table\QueuedJobsTable;

class ImportShell extends Shell
{
    /**
     * Display help for this console.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('il_sales_tax', [
            'help' => 'Imports Illinois sales tax rates',
        ]);
        $parser->addSubcommand('property_tax', [
            'help' => 'Imports property tax rates',
        ]);
        $parser->addSubcommand('county_income_tax', [
            'help' => 'Imports income tax rates',
        ]);

        return $parser;
    }

    /**
     * Imports Illinois sales tax rates from a tab-delimited text file
     *
     * @return void
     */
    public function iLSalesTax()
    {
        $file = new File(ROOT . DS . 'data' . DS . 'illinois-sales-tax-rates.txt');
        $contents = explode("\n", $file->read());
        $year = trim(array_shift($contents));

        // Remove header
        array_shift($contents);

        $countiesTable = TableRegistry::get('Counties');
        $taxRatesTable = TableRegistry::get('TaxRates');
        foreach ($contents as $row) {
            if (trim($row) === '') {
                continue;
            }

            $values = explode("\t", $row);

            $fips = $values[0];
            $countyName = $values[1];
            $county = $countiesTable->find()
                ->select(['id'])
                ->where(['fips' => $fips])
                ->first();
            if (!$county) {
                $this->abort('Unknown county: ' . $countyName . ' (' . $fips . ')');
            }

            $taxRates = [
                'min' => [
                    'value' => str_replace('%', '', $values[2]),
                    'category_id' => TaxRatesTable::COUNTY_LOWEST_SALES
                ],
                'max' => [
                    'value' => str_replace('%', '', $values[3]),
                    'category_id' => TaxRatesTable::COUNTY_HIGHEST_SALES
                ]
            ];
            foreach ($taxRates as $key => $rate) {
                /** @var TaxRate $existingRecord */
                $existingRecord = $taxRatesTable->find()
                    ->select(['value'])
                    ->where([
                        'category_id' => $rate['category_id'],
                        'loc_type' => 'county',
                        'loc_id' => $county->id,
                        'year' => $year
                    ])
                    ->first();

                $msgPrefix = $msg = $countyName . ' County (' . $fips . '): ';
                if (!$existingRecord) {
                    // Insert
                    $newRecord = $taxRatesTable->newEntity([
                        'category_id' => $rate['category_id'],
                        'loc_type' => 'county',
                        'loc_id' => $county->id,
                        'value' => trim($rate['value']),
                        'year' => $year
                    ]);
                    if ($newRecord->getErrors()) {
                        $msg = $msgPrefix . 'Error adding tax rate of ' . $rate['value'];
                        $this->abort($msg);
                    } elseif ($taxRatesTable->save($newRecord)) {
                        $msg = $msgPrefix . 'Added tax rate of ' . $rate['value'];
                        $this->out($msg);
                    } else {
                        $msg = $msgPrefix . 'Error saving tax rate of ' . $rate['value'];
                        $this->abort($msg);
                    }
                } elseif ($existingRecord->value != $rate['value']) {
                    // Update
                    $originalValue = $existingRecord->value;
                    $existingRecord = $taxRatesTable->patchEntity($existingRecord, [
                        'value' => $rate['value']
                    ]);
                    $changeMsg = 'from  ' . $originalValue . ' to ' . $rate['value'];
                    if ($taxRatesTable->save($existingRecord)) {
                        $msg = $msgPrefix . 'Updated tax rate ' . $changeMsg;
                        $this->out($msg);
                    } else {
                        $msg = $msgPrefix . 'Error updating tax rate ' . $changeMsg;
                        $this->abort($msg);
                    }
                }
            }
        }
        $this->out('Done');
    }

    /**
     * Imports property tax rates from a tab-delimited text file
     *
     * @return void
     */
    public function propertyTax()
    {
        $this->import('property-tax-rates.txt', TaxRatesTable::PROPERTY);
    }

    /**
     * Imports income tax rates from a tab-delimited text file
     *
     * @return void
     */
    public function countyIncomeTax()
    {
        $this->import('income-tax-rates.txt', TaxRatesTable::COUNTY_INCOME);
    }

    /**
     * Imports data from the specified tab-delimited file, for the specified category
     *
     * Assumes the file's first row contains only a year, its second row contains an arbitrary header,
     * and subsequent rows contain a FIPS code, then a county name, then a tax rate
     *
     * @param string $filename Filename for tab-delimited data file
     * @param int $categoryId Category ID
     * @return void
     */
    private function import($filename, $categoryId)
    {
        $file = new File(ROOT . DS . 'data' . DS . $filename);
        $contents = explode("\n", $file->read());
        $year = trim(array_shift($contents));

        // Remove header
        array_shift($contents);

        $countiesTable = TableRegistry::get('Counties');
        $taxRatesTable = TableRegistry::get('TaxRates');
        foreach ($contents as $row) {
            if (trim($row) === '') {
                continue;
            }

            $values = explode("\t", $row);

            $fips = $values[0];
            $countyName = $values[1];
            $county = $countiesTable->find()
                ->select(['id'])
                ->where(['fips' => $fips])
                ->first();
            if (!$county) {
                $this->abort('Unknown county: ' . $countyName . ' (' . $fips . ')');
            }

            $taxRate = str_replace('%', '', $values[2]);

            /** @var TaxRate $existingRecord */
            $existingRecord = $taxRatesTable->find()
                ->select(['value'])
                ->where([
                    'category_id' => $categoryId,
                    'loc_type' => 'county',
                    'loc_id' => $county->id,
                    'year' => $year
                ])
                ->first();

            $msgPrefix = $msg = $countyName . ' County (' . $fips . '): ';
            if (!$existingRecord) {
                // Insert
                $newRecord = $taxRatesTable->newEntity([
                    'category_id' => $categoryId,
                    'loc_type' => 'county',
                    'loc_id' => $county->id,
                    'value' => trim($taxRate),
                    'year' => $year
                ]);
                if ($newRecord->getErrors()) {
                    $msg = $msgPrefix . 'Error adding tax rate of ' . $taxRate;
                    $this->abort($msg);
                } elseif ($taxRatesTable->save($newRecord)) {
                    $msg = $msgPrefix . 'Added tax rate of ' . $taxRate;
                    $this->out($msg);
                } else {
                    $msg = $msgPrefix . 'Error saving tax rate of ' . $taxRate;
                    $this->abort($msg);
                }
            } elseif ($existingRecord->value != $taxRate) {
                // Update
                $originalValue = $existingRecord->value;
                $existingRecord = $taxRatesTable->patchEntity($existingRecord, [
                    'value' => trim($taxRate)
                ]);
                $changeMsg = 'from  ' . $originalValue . ' to ' . $taxRate;
                if ($taxRatesTable->save($existingRecord)) {
                    $msg = $msgPrefix . 'Updated tax rate ' . $changeMsg;
                    $this->out($msg);
                } else {
                    $msg = $msgPrefix . 'Error updating tax rate ' . $changeMsg;
                    $this->abort($msg);
                }
            }
        }
        $this->out('Done');
    }
}
