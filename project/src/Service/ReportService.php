<?php

namespace App\Service;

use App\Repository\GroupRepository;
use Doctrine\DBAL\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ReportService
{
    private const FILE_PREFIX = 'group_user';
    private const FILE_PATCH = '/tmp/files/';
    private const FILE_FORMAT = '.csv';

    private Filesystem $filesystem;
    private Serializer $serializer;
    private GroupRepository $groupRepository;

    public function __construct(Filesystem $filesystem, GroupRepository $groupRepository)
    {
        $this->filesystem = $filesystem;
        $this->groupRepository = $groupRepository;
        $this->serializer = new Serializer([new ObjectNormalizer()],  [new CsvEncoder(), new JsonEncoder()]);
    }

    /**
     * @throws ExceptionInterface|Exception
     */
    public function generateReport(): string
    {
        $data = $this->normalizeRepositoryDataToCSV();
        $this->filesystem->dumpFile(self::getFullFilePatch(), $data);

        return self::getFullFilePatch();
    }

    /**
     * @throws ExceptionInterface|Exception
     */
    private function normalizeRepositoryDataToCSV(): string
    {
        return $this->serializer->encode($this->groupRepository->findGroupsWithUsers(), 'csv');
    }

    private static function getFullFilePatch(): string
    {
        return self::FILE_PATCH . self::FILE_PREFIX . self::FILE_FORMAT;
    }

    public static function getFilename(): string
    {
        return self::FILE_PREFIX . self::FILE_FORMAT;
    }
}
