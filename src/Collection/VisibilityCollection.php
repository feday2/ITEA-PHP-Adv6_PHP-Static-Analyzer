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

use Greeflas\StaticAnalyzer\Exception\UnsupportedVisibilityException;

/**
 * @author Feday2 <feday2@gmail.com>
 */
class VisibilityCollection
{
    private $public = 0;
    private $private = 0;
    private $protected = 0;

    /**
     * @param string $visibilityName
     */
    public function incVisCount(string $visibilityName): void
    {
        switch ($visibilityName) {
            case 'public':
                $this->public++;

                break;
            case 'private':
                $this->private++;

                break;
            case 'protected':
                $this->protected++;

                break;
            default:
                throw new UnsupportedVisibilityException('Unsupported visibility');
        }
    }

    /**
     * @return int
     */
    public function getPublicCount(): int
    {
        return $this->public;
    }

    /**
     * @return int
     */
    public function getPrivateCount(): int
    {
        return $this->private;
    }

    /**
     * @return int
     */
    public function getProtectedCount(): int
    {
        return $this->protected;
    }

    /**
     * @param string $header
     *
     * @return string
     */
    public function toConsoleOutput(string $header = 'Visibilities'): string
    {
        $string = \sprintf("%s\n", $header);
        $string .= \sprintf("\tpublic: %d\n", $this->public);
        $string .= \sprintf("\tprotected: %d\n", $this->protected);
        $string .= \sprintf("\tprivate: %d\n", $this->private);

        return $string;
    }
}
