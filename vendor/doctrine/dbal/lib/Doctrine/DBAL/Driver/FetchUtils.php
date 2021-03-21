<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Driver;

/**
 * @internal
 */
final class FetchUtils
{
    /**
     * @return mixed|false
     *
     * @throws Exception
     */
    public static function fetchOne(Result $result)
    {
        $row = $result->fetchNumeric();

        if ($row === false) {
            return false;
        }

        return $row[0];
    }

    /**
     * @return array<int,array<int,mixed>>
     *
     * @throws Exception
     */
    public static function fetchAllNumeric(Result $result): array
    {
        $rows = [];

        while (($row = $result->fetchNumeric()) !== false) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @return array<int,array<string,mixed>>
     *
     * @throws Exception
     */
    public static function fetchAllAssociative(Result $result): array
    {
        $rows = [];

        while (($row = $result->fetchAssociative()) !== false) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @return array<int,mixed>
     *
     * @throws Exception
     */
    public static function fetchFirstColumn(Result $result): array
    {
        $rows = [];

        while (($row = $result->fetchOne()) !== false) {
            $rows[] = $row;
        }

        return $rows;
    }
}
