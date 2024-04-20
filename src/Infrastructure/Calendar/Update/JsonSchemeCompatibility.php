<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace App\Infrastructure\Calendar\Update;

use App\Domain\Calendar\Update\CalendarCompatibility;
use App\Domain\Calendar\Update\CalendarCompatibilityResult;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\ValidationResult;
use Opis\JsonSchema\Validator as JsonValidator;
use Override;

final readonly class JsonSchemeCompatibility implements CalendarCompatibility
{
    public function __construct(
        private JsonValidator $validator,
        private string $schemaFile,
        private string $schema,
    ) {
    }

    #[Override] public function isCompatible(string $calendar): CalendarCompatibilityResult
    {
        $result = $this->validate($calendar);

        return new CalendarCompatibilityResult(
            isCompatible: $result->isValid(),
            errorMsg: $this->errorMessage($result),
        );
    }

    private function validate(string $calendar): ValidationResult
    {
        $this->registerSchema();
        $data = json_decode($calendar);

        return $this->validator->validate($data, schema: $this->schema);
    }

    private function errorMessage(ValidationResult $result): string
    {
        $validationError = $result->error();

        if (!$validationError) {
            return '';
        }

        $errors = (new ErrorFormatter())->format($validationError);

        return sprintf('%s: %s', key($errors), current($errors)[0]);
    }

    private function registerSchema(): void
    {
        $this->validator->resolver()->registerFile(
            id: $this->schema,
            file: $this->schemaFile,
        );
    }
}
