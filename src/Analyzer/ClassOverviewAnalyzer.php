<?php

declare(strict_types=1);

/*
 * This file is part of the "PHP Static Analyzer" project.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Greeflas\StaticAnalyzer\Analyzer;

use Greeflas\StaticAnalyzer\Collection\ClassStatisticCollection;

/**
 * @author Feday2 <feday2@gmail.com>
 */
class ClassOverviewAnalyzer
{
    private const VISIBILITIES = [
        'public',
        'private',
        'protected',
    ];
    private $className;
    private $reflect;
    private $statCollection;

    /**
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
        $this->reflect = new \ReflectionClass($className);
        $this->statCollection = new ClassStatisticCollection($this->reflect);
    }

    /**
     * @param array  $elements
     * @param string $classEl
     * @param string $visibility
     *
     * @return int
     */
    private function calculate(array $elements, string $classEl, string $visibility): void
    {
        foreach ($elements as $el) {
            if ($el->class === $this->className) {
                $this->statCollection->updateVisibilityCount($classEl, $visibility);
            }
        }
    }

    /**
     * @return ClassStatisticCollection
     */
    public function analyze(): ClassStatisticCollection
    {
        foreach (self::VISIBILITIES as $visibility) {
            $isVisibility = 'IS_' . \strtoupper($visibility);
            $methods = $this->reflect->getMethods(\constant('\ReflectionMethod::' . $isVisibility));
            $properties = $this->reflect->getProperties(\constant('\ReflectionMethod::' . $isVisibility));
            $this->calculate($methods, 'method', $visibility);
            $this->calculate($properties, 'property', $visibility);
        }

        return $this->statCollection;
    }
}
