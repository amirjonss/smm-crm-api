<?php

declare(strict_types=1);

namespace App\Controller\Base;

use ApiPlatform\Validator\Exception\ValidationException;
use ApiPlatform\Validator\ValidatorInterface;
use App\Component\User\CurrentUser;
use App\Component\User\Dtos\JwtUserDto;
use App\Controller\Base\Constants\ResponseFormat;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly CurrentUser $currentUser,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    /**
     * @return ValidatorInterface
     */
    protected function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param object $data
     * @param array  $context
     *
     * @throws ValidationException
     */
    protected function validate(object $data, array $context = []): void
    {
        $this->getValidator()->validate($data, $context);
    }

    /**
     * @return SerializerInterface
     */
    protected function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    protected function getNormalizer(): NormalizerInterface
    {
        return $this->normalizer;
    }

    protected function response(
        mixed $content,
        int $status = Response::HTTP_OK,
        string $format = ResponseFormat::JSONLD,
    ): Response {
        return new Response(
            $this->getSerializer()->serialize($content, $format), $status
        );
    }

    /**
     * @param int $status
     *
     * @return Response
     */
    protected function responseEmpty(int $status = Response::HTTP_NO_CONTENT): Response
    {
        return $this->response('{}', $status);
    }

    /**
     * @param mixed  $content
     * @param int    $status
     * @param string $format
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    protected function responseNormalized(
        mixed $content,
        int $status = Response::HTTP_OK,
        string $format = ResponseFormat::JSONLD,
    ): Response {
        $result = $this->getNormalizer()->normalize($content, $format);

        return $this->response($result, $status);
    }

    /**
     * @param Request $request
     * @param string  $dtoClass
     * @param string  $format
     *
     * @return object
     *
     * @throws ExceptionInterface
     */
    protected function getDtoFromRequest(
        Request $request,
        string $dtoClass,
        string $format = ResponseFormat::JSONLD,
    ): object {
        return $this->getSerializer()->deserialize(
            $request->getContent(),
            $dtoClass,
            $format
        );
    }

    protected function getUser(): User
    {
        return $this->currentUser->getUser();
    }

    protected function getJwtUser(): JwtUserDto
    {
        return $this->currentUser->getJwtUser();
    }

    protected function findEntityOrError(ServiceEntityRepository $repository, ?int $id): object
    {
        if ($id === null) {
            $this->throwNotFoundException();
        }

        $model = $repository->find($id);

        if ($model === null) {
            $this->throwNotFoundException();
        }

        return $model;
    }

    protected function throwNotFoundException($text = 'Object is not found'): never
    {
        throw new NotFoundHttpException($text);
    }
}
