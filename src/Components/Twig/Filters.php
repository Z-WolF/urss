<?php

namespace RssApp\Components\Twig;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Twig\TwigFilter;

class Filters
{

    public static function all()
    {
        $filterClasses = [self::class];

        $allFilterFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.DS.'Filters'));
        $filterFiles    = new RegexIterator($allFilterFiles, '/\.php$/');
        foreach ($filterFiles as $filterFile) {
            $content   = file_get_contents($filterFile->getRealPath());
            $tokens    = token_get_all($content);
            $namespace = '';
            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }
                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2;
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }
                if (T_CLASS === $tokens[$index][0] && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
                    $index += 2;
                    $filterClasses[] = $namespace.'\\'.$tokens[$index][1];
                    break;
                }
            }
        }

        $filters = [];
        foreach ($filterClasses as $filterClass) {
            $methods = get_class_methods($filterClass);
            $key     = array_search('all', $methods);
            if ($key !== false) {
                unset($methods[$key]);
            }

            foreach ($methods as $method) {
                $filters[] = new TwigFilter($method, [$filterClass, $method]);
            }
        }

        return $filters;
    }
}
