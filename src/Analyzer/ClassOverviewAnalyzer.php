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

use Greeflas\StaticAnalyzer\Exception\ClassNotExistException;

/**
 * @author Feday2 <feday2@gmail.com>
 */
final class ClassOverviewAnalyzer
{
    private $className;
    private $reflect;
    private const VISIBILITIES = [
        'PUBLIC',
        'PRIVATE',
        'PROTECTED',
    ];
    private $resultCount = [];

    /**
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->isClassExist($className);
        $this->className = $className;
        $this->reflect = new \ReflectionClass($className);
    }

    /**
     * @param string $className
     */
    private function isClassExist(string $className): void
    {
        if (!\class_exists($className)) {
            throw new ClassNotExistException('Class ' . $className . ' not exist');
        }
    }

    /**
     * @param array $elements
     *
     * @return int
     */
    private function calculate(array $elements): int
    {
        $count = 0;

        foreach ($elements as $el) {
            if ($el->class === $this->className) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * @return array
     */
    public function analyze(): array
    {
        foreach (self::VISIBILITIES as $visibility) {
            $isVisibility = 'IS_' . $visibility;

            $methodCount = $this->calculate($this->reflect->getMethods(\constant('\ReflectionMethod::' . $isVisibility)));
            $this->resultCount['Methods'][$visibility] = $methodCount;

            $propCount = $this->calculate($this->reflect->getProperties(\constant('\ReflectionMethod::' . $isVisibility)));
            $this->resultCount['Properties'][$visibility] = $propCount;
        }

        return $this->resultCount;
    }

    /**
     * @return string
     */
    public function getClassType(): string
    {
        if ($this->reflect->isFinal()) {
            return 'final';
        }

        if ($this->reflect->isAbstract()) {
            return 'abstruct';
        }

        return 'normal';
    }
}
