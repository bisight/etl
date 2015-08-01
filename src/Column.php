<?php

namespace BiSight\Etl;

class Column
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    private $alias;

    public function getAlias()
    {
        if ($this->alias) {
            return $this->alias;
        }

        return $this->name;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }


    private $length;

    public function getLength()
    {
        return $this->length;
    }

    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    private $type;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    private $precision;

    public function getPrecision()
    {
        return $this->precision;
    }

    public function setPrecision($precision)
    {
        $this->precision = $precision;

        return $this;
    }

    public static function createNew()
    {
        return new self();
    }

    /**
     * Serialization format: `column1:string(64),column2:text,col:integer,col2:datetime`
     *
     * @param  string $serializedColumns
     * @return Column[]
     */
    public static function unserializeArray($serializedColumns)
    {
        return self::reassignAliases(
            array_map(function($serializedColumn){
                return self::unserialize(trim($serializedColumn));
            }, explode(',', $serializedColumns))
        );
    }

    public static function reassignAliases($columns)
    {
        $result = array();
        foreach ($columns as $column) {
            $result[$column->getAlias()] = $column;
        }
        return $result;
    }

    /**
     * Serialization format: `column1:string(64)`
     * @todo precision support
     *
     * @param  string $serializedColumn
     * @return Column
     */
    public static function unserialize($serializedColumn, $defaultType = 'text', $defaultLength = null)
    {
        if (!preg_match('/^(?<name>[a-zA-Z_]+)((\/(?<alias>[^:]+))?:(?<type>[^(]+)(\((?<length>[^\;]+)(\;(?<precision>\d+))?\))?)?$/', $serializedColumn, $matches)) {
            throw new \InvalidArgumentException(sprintf(
                "Invalid column serialized definition: '%s'",
                $serializedColumn
            ));
        }

        return Column::createNew()
            ->setName(trim($matches['name']))
            ->setAlias(isset($matches['alias']) ? trim($matches['alias']) : '')
            ->setType(isset($matches['type']) ? trim($matches['type']) : $defaultType)
            ->setLength(isset($matches['length']) ? intval(trim($matches['length'])) : $defaultLength)
            ->setPrecision(isset($matches['precision']) ? intval(trim($matches['precision'])) : null)
        ;
    }
}
