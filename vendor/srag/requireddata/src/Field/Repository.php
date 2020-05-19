<?php

namespace srag\RequiredData\HelpMe\Field;

use srag\DIC\HelpMe\DICTrait;
use srag\RequiredData\HelpMe\Field\Field\Group\GroupField;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class Repository
 *
 * @package srag\RequiredData\HelpMe\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use RequiredDataTrait;

    /**
     * @var self|null
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
     * @param AbstractField[] $fields
     *
     * @return GroupField|null
     */
    public function createGroupOfFields(array $fields) : ?GroupField
    {
        $fields = array_filter($fields, function (AbstractField $field) : bool {
            return !($field instanceof GroupField);
        });
        if (empty($fields)) {
            return null;
        }

        $first_field = current($fields);

        $fields = array_filter($fields, function (AbstractField $field) use ($first_field): bool {
            return ($field->getType() === $first_field->getType() && $field->getParentContext() === $first_field->getParentContext() && $field->getParentId() === $first_field->getParentId());
        });
        if (empty($fields)) {
            return null;
        }

        /**
         * @var GroupField $group
         */
        $group = $this->factory()->newInstance(GroupField::getType());

        $group->setParentContext($first_field->getParentContext());
        $group->setParentId($first_field->getParentId());
        $this->storeField($group);

        foreach ($fields as $field) {
            $field->setParentContext(GroupField::PARENT_CONTEXT_FIELD_GROUP);
            $field->setParentId($group->getFieldId());
            $this->storeField($field);
        }

        $this->storeField($group);

        return $group;
    }


    /**
     * @param AbstractField $field
     */
    public function deleteField(AbstractField $field) : void
    {
        $field->delete();

        $this->reSortFields($field->getParentContext(), $field->getParentId());
    }


    /**
     * @param int $parent_context
     * @param int $parent_id
     */
    public function deleteFields(int $parent_context, int $parent_id) : void
    {
        foreach ($this->getFields($parent_context, $parent_id, null, false) as $field) {
            $this->deleteField($field);
        }
    }


    /**
     * @internal
     */
    public function dropTables() : void
    {
        foreach ($this->factory()->getClasses() as $class) {
            self::dic()->database()->dropTable($class::getTableName(), false);
        }
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param int    $parent_context
     * @param int    $parent_id
     * @param string $type
     * @param int    $field_id
     *
     * @return AbstractField|null
     */
    public function getFieldById(int $parent_context, int $parent_id, string $type, int $field_id) : ?AbstractField
    {
        foreach ($this->factory()->getClasses() as $type_class => $class) {
            if ($type_class === $type) {
                /**
                 * @var AbstractField|null $field
                 */
                $field = $class::where(["parent_context" => $parent_context, "parent_id" => $parent_id, "field_id" => $field_id])->first();

                return $field;
            }
        }

        return null;
    }


    /**
     * @param int    $parent_context
     * @param int    $parent_id
     * @param string $name
     *
     * @return AbstractField|null
     */
    public function getFieldByName(int $parent_context, int $parent_id, string $name) : ?AbstractField
    {
        foreach ($this->factory()->getClasses() as $type_class => $class) {
            /**
             * @var AbstractField|null $field
             */
            $field = $class::where(["parent_context" => $parent_context, "parent_id" => $parent_id, "name" => $name])->first();

            if ($field !== null) {
                return $field;
            }
        }

        return null;
    }


    /**
     * @param int        $parent_context
     * @param int        $parent_id
     * @param array|null $types
     * @param bool       $only_enabled
     *
     * @return AbstractField[]
     */
    public function getFields(int $parent_context, int $parent_id, ?array $types = null, bool $only_enabled = true) : array
    {
        $fields = [];

        foreach ($this->factory()->getClasses() as $type => $class) {
            if (!empty($types) && !in_array($type, $types)) {
                continue;
            }

            $where = $class::where(["parent_context" => $parent_context, "parent_id" => $parent_id]);

            if ($only_enabled) {
                $where = $where->where(["enabled" => true]);
            }

            /**
             * @var AbstractField $field
             */
            foreach ($where->get() as $field) {
                $fields[$field->getId()] = $field;
            }
        }

        uasort($fields, function (AbstractField $field1, AbstractField $field2) : int {
            if ($field1->getSort() < $field2->getSort()) {
                return -1;
            }
            if ($field1->getSort() > $field2->getSort()) {
                return 1;
            }

            return 0;
        });

        return $fields;
    }


    /**
     * @internal
     */
    public function installTables() : void
    {
        foreach ($this->factory()->getClasses() as $class) {
            $class::updateDB();
        }
    }


    /**
     * @param AbstractField $field
     */
    public function moveFieldUp(AbstractField $field) : void
    {
        $field->setSort($field->getSort() - 15);

        $this->storeField($field);

        $this->reSortFields($field->getParentContext(), $field->getParentId());
    }


    /**
     * @param AbstractField $field
     */
    public function moveFieldDown(AbstractField $field) : void
    {
        $field->setSort($field->getSort() + 15);

        $this->storeField($field);

        $this->reSortFields($field->getParentContext(), $field->getParentId());
    }


    /**
     * @param int $parent_context
     * @param int $parent_id
     */
    protected function reSortFields(int $parent_context, int $parent_id) : void
    {
        $fields = $this->getFields($parent_context, $parent_id, null, false);

        $i = 1;
        foreach ($fields as $field) {
            $field->setSort($i * 10);

            $this->storeField($field);

            $i++;
        }
    }


    /**
     * @param AbstractField $field
     */
    public function storeField(AbstractField $field) : void
    {
        if (empty($field->getFieldId())) {
            $field->setSort(((count($this->getFields($field->getParentContext(), $field->getParentId(), null, false)) + 1) * 10));
        }

        $field->store();
    }


    /**
     * @param GroupField $group
     *
     * @return AbstractField[]
     */
    public function ungroup(GroupField $group) : array
    {
        $fields = $this->getFields(GroupField::PARENT_CONTEXT_FIELD_GROUP, $group->getFieldId(), null, false);

        foreach ($fields as $field) {
            $field->setParentContext($group->getParentContext());
            $field->setParentId($group->getParentId());
            $this->storeField($field);
        }

        $this->deleteField($group);

        return $fields;
    }
}
