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

namespace Greeflas\StaticAnalyzer\Collection;

use Greeflas\StaticAnalyzer\Exception\UnsupportedClassElementException;

/**
 * @author Feday2 <feday2@gmail.com>
 */
class ClassStatisticCollection
{
    private $class;
    private $classType;
    private $metVisibl;
    private $propVisibl;

    /**
     * @param ReflectionClass $class
     */
    public function __construct(\ReflectionClass $class)
    {
        $this->class = $class;
        $this->metVisibl = new VisibilityCollection();
        $this->propVisibl = new VisibilityCollection();
        $this->setClassType($class);
    }

    /**
     * @return string
     */
    public function toConsoleOutput(): string
    {
        $string = \sprintf(
            "Class: %s is %s\n",
            $this->class->name,
            $this->classType
        );

        $string .= $this->propVisibl->toConsoleOutput('Properties:');
        $string .= $this->metVisibl->toConsoleOutput('Methods:');

        return $string;
    }

    private function setClassType(): void
    {
        if ($this->class->isFinal()) {
            $this->classType = 'final';
        } elseif ($this->class->isAbstract()) {
            $this->classType = 'abstruct';
        } else {
            $this->classType = 'normal';
        }
    }

    /**
     * @param string $classEl
     * @param string $visibilityName
     */
    public function updateVisibilityCount(string $classEl, string $visibilityName): void
    {
        switch ($classEl) {
            case 'method':
                $this->metVisibl->incVisCount($visibilityName);

                break;
            case 'property':
                $this->propVisibl->incVisCount($visibilityName);

                break;
            default:
                throw new UnsupportedClassElementException('Unsupported element of class');
        }
    }
}
