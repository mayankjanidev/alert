<?php

namespace Mayank\Alert;

use Illuminate\Support\Facades\Session;

class Alert
{
	protected string $title = 'Alert Message';

	protected ?string $description = null;

	protected string $type = 'info';

	private function __construct(string $type)
	{
		$this->type = $type;
	}

	public static function type(string $alertType): static
	{
		return new static($alertType);
	}

	public static function info(): static
	{
		return new static('info');
	}

	public static function success(): static
	{
		return new static('success');
	}

	public static function warning(): static
	{
		return new static('warning');
	}

	public static function failure(): static
	{
		return new static('failure');
	}

	public static function current(): ?static
	{
		$sessionAlert = Session::get('alert');

		if ($sessionAlert == null)
			return null;

		$alert = static::type($sessionAlert['type'])->title($sessionAlert['title']);

		if ($sessionAlert['description'] != null)
			$alert = $alert->description($sessionAlert['description']);

		return $alert;
	}

	public static function exists(): bool
	{
		return Session::has('alert');
	}

	public function title(string $title): self
	{
		$this->title = $title;
		return $this;
	}

	public function description(string $description): self
	{
		$this->description = $description;
		return $this;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function flash(): void
	{
		Session::flash('alert', array('type' => $this->type, 'title' => $this->title, 'description' => $this->description));
	}
}
