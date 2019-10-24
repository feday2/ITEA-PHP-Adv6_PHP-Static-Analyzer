<?php

declare(strict_types=1);

namespace Greeflas\StaticAnalyzer\Analyzer;

use Greeflas\StaticAnalyzer\Exception\ClassNotExistException;

final class ClassOverviewAnalyzer
{

    private $className;
    private $reflect;
    CONST VISIBILITIES = [
        'PUBLIC',
        'PRIVATE',
        'PROTECTED'
    ];
    private $resultCount = [];

    public function __construct(string $className)
    {
        $this->isClassExist($className);
        $this->className = $className;
        $this->reflect = new \ReflectionClass($className);
    }

    private function isClassExist($className)
    {
        if (!class_exists($className)) {
            throw new ClassNotExistException('Class '.$className.' not exist');
        }
    }

    private function calculate (array $elements) {
        $count = 0;
        foreach ($elements as $el) {
            if ($el->class === $this->className) {
                $count++;
            }
        }
        return $count;
    }

    public function analyze()
    {
        foreach (self::VISIBILITIES as $visibility) {
            $isVisibility = 'IS_'.$visibility;

            $methodCount = $this->calculate($this->reflect->getMethods(constant('\ReflectionMethod::'. $isVisibility)));
            $this->resultCount['Methods'][$visibility] = $methodCount;

            $propCount = $this->calculate($this->reflect->getProperties(constant('\ReflectionMethod::'. $isVisibility)));
            $this->resultCount['Properties'][$visibility] = $propCount;
        }
        return $this->resultCount;
    }

    public function getClassType()
    {
        if ($this->reflect->isFinal()){
            return 'final';
        }
        if ($this->reflect->isAbstract()){
            return 'abstruct';
        }
        return 'normal';
    }

}