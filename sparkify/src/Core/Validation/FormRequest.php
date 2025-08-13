<?php

declare(strict_types=1);

namespace Sparkify\Core\Validation;

use Respect\Validation\Factory;
use Respect\Validation\Validatable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class FormRequest
{
	abstract public function rules(): array;

	public function validate(Request $request): array
	{
		$payload = array_merge($request->query->all(), $request->request->all());
		$rules = $this->rules();
		$errors = [];
		foreach ($rules as $field => $rule) {
			if ($rule instanceof Validatable) {
				$value = $payload[$field] ?? null;
				if (!$rule->validate($value)) {
					$errors[$field] = 'Invalid';
				}
			}
		}
		if (!empty($errors)) {
			throw new BadRequestException(json_encode(['errors' => $errors], JSON_THROW_ON_ERROR));
		}
		return $payload;
	}
}