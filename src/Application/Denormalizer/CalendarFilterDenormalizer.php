<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Application\Denormalizer;

use Override;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class CalendarFilterDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function __construct(
        private readonly DenormalizerInterface $normalizer,
    ) {
    }

    #[Override] public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        /** @var InputBag $data */

        $filter = [
            'categories' => explode(',', $data->get('categories', 'women,men')),
            'rounds' => explode(',', $data->get('rounds', 'semi-final,final')),
            'disciplines' => explode(',', $data->get('disciplines', 'boulder,lead,speed')),
            'leagues' => explode(',', $data->get('leagues', 'boulder,lead,speed')),
        ];

        return $this->denormalizer->denormalize($filter, $type, $format, $context);
    }

    #[Override] public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $data instanceof InputBag;
    }

    #[Override] public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => true,
        ];
    }
}