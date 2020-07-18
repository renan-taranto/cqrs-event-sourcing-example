<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Shared\Infrastructure\Serializer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Taranto\ListMaker\Shared\Domain\Message\DomainEvent;

/**
 * Class DomainEventNormalizer
 * @package Taranto\ListMaker\Shared\Infrastructure\Serializer
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class DomainEventNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @var PropertyNormalizer
     */
    private $propertyNormalizer;

    /**
     * DomainEventNormalizer constructor.
     * @param PropertyNormalizer $propertyNormalizer
     */
    public function __construct(PropertyNormalizer $propertyNormalizer)
    {
        $this->propertyNormalizer = $propertyNormalizer;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->propertyNormalizer->normalize($object, $format, $context);
        unset($data['event_type']);

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof DomainEvent;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}