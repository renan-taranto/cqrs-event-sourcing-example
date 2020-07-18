<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Board\Infrastructure\Serializer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Taranto\ListMaker\Board\Application\Query\BoardsOverview;

/**
 * Class BoardsOverviewDenormalizer
 * @package Taranto\ListMaker\Board\Infrastructure\Serializer
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class BoardsOverviewDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return new BoardsOverview(
            isset($data['open']) ?
                filter_var($data['open'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) :
                null
        );
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === BoardsOverview::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}