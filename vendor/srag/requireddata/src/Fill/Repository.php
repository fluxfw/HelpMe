<?php

namespace srag\RequiredData\HelpMe\Fill;

use ilSession;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class Repository
 *
 * @package srag\RequiredData\HelpMe\Fill
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    const SESSION_TEMP_FILL_VALUES_STORAGE = "required_data_temp_fill_values";
    use DICTrait;
    use RequiredDataTrait;
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     *
     */
    public function clearTempFillValues()/*:void*/
    {
        ilSession::clear(self::SESSION_TEMP_FILL_VALUES_STORAGE);
    }


    /**
     * @param FillStorage $fill_storage
     */
    protected function deleteFillStorage(FillStorage $fill_storage)/*: void*/
    {
        $fill_storage->delete();
    }


    /**
     * @param string $fill_id
     */
    protected function deleteFillStorages(string $fill_id)/*: void*/
    {
        foreach ($this->getFillStorages($fill_id) as $fill_storage) {
            $this->deleteFillStorage($fill_storage);
        }
    }


    /**
     * @internal
     */
    public function dropTables()/*:void*/
    {
        self::dic()->database()->dropTable(FillStorage::getTableName(), false);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param int   $parent_context
     * @param int   $parent_id
     * @param array $fill_values
     *
     * @return array
     */
    public function formatAsJsons(int $parent_context, int $parent_id, array $fill_values) : array
    {
        foreach ($fill_values as $field_id => &$value) {
            list($type, $field_id) = explode("_", $field_id);

            $field = self::requiredData()->fields()->getFieldById($parent_context, $parent_id, $type, $field_id);

            if ($field !== null) {
                $value = $this->factory()->newFillFieldInstance($field)->formatAsJson($value);
            }
        }

        return $fill_values;
    }


    /**
     * @param int   $parent_context
     * @param int   $parent_id
     * @param array $fill_values
     * @param bool  $keep_field_id
     *
     * @return array
     */
    public function formatAsStrings(int $parent_context, int $parent_id, array $fill_values, bool $keep_field_id = false) : array
    {
        $formatted_fill_values = [];

        foreach ($fill_values as $field_id => $value) {
            list($type, $field_id) = explode("_", $field_id);

            $field = self::requiredData()->fields()->getFieldById($parent_context, $parent_id, $type, $field_id);

            if ($field !== null) {
                $value = $this->factory()->newFillFieldInstance($field)->formatAsString($value);
                if ($keep_field_id) {
                    if (self::requiredData()->isEnableNames()) {
                        $formatted_fill_values[$type . "_" . $field_id] = [$field->getName(), $field->getLabel(), $value];
                    } else {
                        $formatted_fill_values[$type . "_" . $field_id] = [$field->getLabel(), $value];
                    }
                } else {
                    $formatted_fill_values[$field->getLabel()] = $value;
                }
            }
        }

        return $formatted_fill_values;
    }


    /**
     * @param string $fill_id
     *
     * @return FillStorage[]
     */
    protected function getFillStorages(string $fill_id) : array
    {
        $fill_storages = FillStorage::where([
            "fill_id" => $fill_id
        ])->get();

        return $fill_storages;
    }


    /**
     * @param string $fill_id
     * @param string $field_id
     *
     * @return FillStorage|null
     */
    protected function getFillStorageByField(string $fill_id, string $field_id)/*:?FillStorage*/
    {
        /**
         * @var FillStorage|null $fill_storage
         */

        $fill_storage = FillStorage::where([
            "fill_id"  => $fill_id,
            "field_id" => $field_id
        ])->first();

        return $fill_storage;
    }


    /**
     * @param string|null $fill_id
     *
     * @return array
     */
    public function getFillValues(/*?*/ string $fill_id = null) : array
    {
        if ($fill_id === null) {
            if (isset($_SESSION[self::SESSION_TEMP_FILL_VALUES_STORAGE])) {
                return (array) ilSession::get(self::SESSION_TEMP_FILL_VALUES_STORAGE);
            }

            return [];
        }

        $fill_values = [];

        foreach ($this->getFillStorages($fill_id) as $fill_storage) {
            $fill_values[$fill_storage->getFieldId()] = $fill_storage->getFillValue();
        }

        return $fill_values;
    }


    /**
     * @param string $fill_id
     * @param string $field_id
     *
     * @return mixed
     */
    public function getFillValueByField(string $fill_id, string $field_id)
    {
        $fill_storage = $this->getFillStorageByField($fill_id, $field_id);

        if ($fill_storage !== null) {
            return $fill_storage->getFillValue();
        }

        return null;
    }


    /**
     * @param int $parent_context
     * @param int $parent_id
     *
     * @return array
     */
    public function getFormFields(int $parent_context, int $parent_id) : array
    {
        $fields = [];

        foreach (self::requiredData()->fields()->getFields($parent_context, $parent_id) as $field) {
            $fields["field_" . $field->getId()] = array_merge(
                [
                    "setTitle" => $field->getLabel(),
                    "setInfo"  => $field->getDescription()
                ],
                self::requiredData()->fills()->factory()->newFillFieldInstance($field)->getFormFields(),
                [
                    PropertyFormGUI::PROPERTY_REQUIRED => $field->isRequired()
                ]);
        }

        return $fields;
    }


    /**
     * @internal
     */
    public function installTables()/*:void*/
    {
        FillStorage::updateDB();
    }


    /**
     * @param string|null $fill_id
     * @param array|null  $fill_values
     */
    public function storeFillValues(/*?*/ string $fill_id = null, /*?*/ array $fill_values = null)/*:void*/
    {
        if ($fill_id !== null) {
            if ($fill_values === null) {
                if (isset($_SESSION[self::SESSION_TEMP_FILL_VALUES_STORAGE])) {
                    $fill_values = (array) ilSession::get(self::SESSION_TEMP_FILL_VALUES_STORAGE);

                    $this->clearTempFillValues();
                } else {
                    $fill_values = [];
                }
            }

            foreach ($fill_values as $field_id => $fill_value) {
                $this->storeFillValue($fill_id, $field_id, $fill_value);
            }
        } else {
            ilSession::set(self::SESSION_TEMP_FILL_VALUES_STORAGE, $fill_values);
        }
    }


    /**
     * @param string $fill_id
     * @param string $field_id
     * @param mixed  $fill_value
     */
    public function storeFillValue(string $fill_id, string $field_id, $fill_value)
    {
        $fill_storage = $this->getFillStorageByField($fill_id, $field_id);

        if ($fill_storage === null) {
            $fill_storage = $this->factory()->newFillStorageInstance();

            $fill_storage->setFillId($fill_id);

            $fill_storage->setFieldId($field_id);
        }

        $fill_storage->setFillValue($fill_value);

        $this->storeFillStorage($fill_storage);
    }


    /**
     * @param FillStorage $fill_storage
     */
    protected function storeFillStorage(FillStorage $fill_storage)/*:void*/
    {
        $fill_storage->store();
    }
}
