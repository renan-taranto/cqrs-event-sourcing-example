<?php
/**
 * This file is part of list-maker.
 * (c) Renan Taranto <renantaranto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Taranto\ListMaker\Infrastructure\Serializer;

use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Taranto\ListMaker\Domain\Model\Common\DomainEvent;

/**
 * Class DomainEventDenormalizer
 * @package Taranto\ListMaker\Infrastructure\Serializer
 * @author Renan Taranto <renantaranto@gmail.com>
 */
final class DomainEventDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @var ClassDiscriminatorResolverInterface
     */
    private $resolver;

    /**
     * DomainEventDenormalizer constructor.
     * @param ClassDiscriminatorResolverInterface $resolver
     */
    public function __construct(ClassDiscriminatorResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param mixed $data
     * @param string $class
     * @param null $format
     * @param array $context
     * @return object|void
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $discriminatorMapping = $this->resolver->getMappingForClass($class);
        $mappedClass = $discriminatorMapping->getClassForType($data[$discriminatorMapping->getTypeProperty()]);
        if ($mappedClass === null) {
            throw new \RuntimeException(sprintf(
                'No mapped class found for the domain event of type "%s"',
                $data[$discriminatorMapping->getTypeProperty()]
            ));
        }

        return $mappedClass::occur($data['aggregate_id'], $data['payload']);
    }

    /**
     * @param mixed $data
     * @param string $type
     * @param null $format
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === DomainEvent::class;
    }

    /**
     * @return bool
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
