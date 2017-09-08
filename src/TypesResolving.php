<?php

namespace Barryvdh\LaravelIdeHelper;


class TypesResolving
{
    const TYPES = [
        'bool',
        'boolean',
        'integer',
        'int',
        'float',
        'double',
        'string',
        'number',

        'array',
        'object',
        'callable',
        'iterable',

        'callback',
        'resource',
        'NULL',
        'null',

        'mixed',
        'self',
        '$this',
        'static',
        'void',
        'true',
        'false',
    ];

    static function resolveType($namespace, $type, $source, $file = null)
    {
        $suffix = '';
        if (substr($type, -2) == '[]') {
            $suffix = '[]';
            $type = substr($type, 0, -2);
        }
        if (strpos($type, '\\') === 0 || in_array($type, self::TYPES)) {
            return $type . $suffix;
        } else {
            $quoted = preg_quote($type);
            $pattern = (
                '@^\s*use\s+('
                . '(?P<name>([^;\s]*\\\\)?' . $quoted . ')'
                . '|(?P<name_alias>[^;\s]*)\s+as\s+' . $quoted
                . ')\s*;@m'
            );
            if (preg_match($pattern, $source, $m)) {
                if (!empty($m['name'])) {
                    return '\\' . $m['name'] . $suffix;
                } else if (!empty($m['name_alias'])) {
                    return '\\' . $m['name_alias'] . $suffix;
                } else {
                    return '\\' . $namespace . '\\' . $type . $suffix;
                }
            } else {
            }
        }
        return '\\' . $namespace . '\\' . $type . $suffix;
    }
}