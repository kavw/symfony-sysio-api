<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Validation\ValidationException;
use App\Validation\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

final readonly class RequestResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return iterable<object>
     * @throws ValidationException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType) {
            return [];
        }

        $content = $request->getContent();
        if (!$content) {
            throw new \DomainException("Invalid data format: content is empty");
        }

        $format = $request->getContentTypeFormat();
        if (!$format) {
            throw new \DomainException("Invalid data format: format is empty");
        }

        try {
            $request = $this->serializer->deserialize(
                $content,
                $argumentType,
                $format,
                [DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,]
            );
        } catch (PartialDenormalizationException $e) {
            $violations = new ConstraintViolationList();
            /** @var NotNormalizableValueException $exception */
            foreach ($e->getErrors() as $exception) {
                $message = sprintf(
                    'The type must be one of "%s" ("%s" given).',
                    implode(', ', $exception->getExpectedTypes()),
                    $exception->getCurrentType()
                );

                $parameters = [];
                if ($exception->canUseMessageForUser()) {
                    $parameters['hint'] = $exception->getMessage();
                }

                $violations->add(
                    new ConstraintViolation(
                        $message,
                        '',
                        $parameters,
                        null,
                        $exception->getPath(),
                        null
                    )
                );
            }

            throw new ValidationException($violations);
        } catch (\Exception $e) {
            throw new \DomainException("Invalid format: " . $e->getMessage());
        }

        if (!is_object($request)) {
            throw new \DomainException("Invalid data format: an object expected");
        }

        $this->validator->validate($request);

        yield $request;
    }
}
