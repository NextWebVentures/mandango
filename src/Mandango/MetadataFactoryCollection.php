<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango;

/**
 * Metadata.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class MetadataFactoryCollection extends MetadataFactory
{
    /**
     * Map of class to conctere provider (factory)
     * @var array
     */
    protected $map = array();

    /**
     * Aggregate map of class types (standalone document/embedded) from all
     * added factories; allows for direct acces and almost no overloading.
     * @var array
     */
    protected $classes = array();

    /**
     * Class constructor
     * @param array $factories Array of factories to add
     */
    public function __construct(array $factories = array())
    {
        foreach ($factories as $factory) {
            $this->addFactory($factory);
        }
    }

    /**
     * Add concrete factory to aggregate
     * @param MetadataFactory $factory Concrete factory that will be aggregated under this object
     */
    public function addFactory(MetadataFactory $factory)
    {
        /**
         * Map class name to factory; PHP always stores objects as references
         * so this isn't memory intensive (keeping object as value for each class key)
         * @var array
         */
        $map = array_fill_keys($factory->getClasses(), $factory);
        $this->map = array_merge($this->map, $map);

        $this->classes = array_merge($this->classes, $factory->getClassesTypes());
    }

    /**
     * Returns the metadata of a class.
     *
     * @param string $class The class.
     * @return array The metadata of the class.
     * @throws \LogicException If the class does not exist in the metadata factory.
     */
    public function getClass($class)
    {
        $this->checkClass($class);
        return $this->map[$class]->getClass($class);
    }


}
