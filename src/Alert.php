<?php

namespace Mayank\Alert;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Alert
{
	protected string $title;

	protected ?string $description;

	protected string $type = 'info';

	protected ?string $action = null;

	protected ?Model $model = null;

	protected ?string $entity = null;

	protected array $langParameters = [];

	private function __construct(string $type)
	{
		$this->type = $type;
	}

	public static function custom(string $alertType): static
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

	public static function model(Model $model, array $langParameters = []): static
	{
		$alert = new static('success');
		$alert->model = $model;
		$alert->langParameters = $langParameters;

		return $alert;
	}

	public static function for(string $entity, array $langParameters = []): static
	{
		$alert = new static('success');
		$alert->entity = Str::snake($entity);
		$alert->langParameters = $langParameters;

		return $alert;
	}

	public static function current(): ?static
	{
		$sessionAlert = Session::get('alert');

		if ($sessionAlert == null)
			return null;

		$alert = static::custom($sessionAlert['type']);
		$alert->title = $sessionAlert['title'];
		$alert->description = $sessionAlert['description'];
		$alert->action = $sessionAlert['action'];

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

	public function type(string $type): self
	{
		$this->type = $type;
		return $this;
	}

	public function action(string $action): self
	{
		$this->action = $action;
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

	public function getAction(): ?string
	{
		return $this->action;
	}

	protected function customizeAlertMessageForModel(): void
	{
		$modelName = class_basename($this->model);
		$modelNameSnakeCase = Str::snake($modelName);
		$modelNameSnakeCase = trans()->has("alert::messages.$modelNameSnakeCase") ? $modelNameSnakeCase : 'model';

		if ($this->action == null) {
			if ($this->model->wasRecentlyCreated) {
				$this->action = 'created';
			} else if (!$this->model->exists) {
				$this->action = 'deleted';
			} else {
				$this->action = 'updated';
			}
		}

		$this->langParameters['model_name'] = $modelName;

		if (!isset($this->title)) {
			$this->title = trans("alert::messages.$modelNameSnakeCase.$this->action.title", $this->langParameters);
		}

		if (!isset($this->description)) {
			$this->description = trans("alert::messages.$modelNameSnakeCase.$this->action.description", $this->langParameters);
		}
	}

	protected function customizeAlertMessageForEntity(): void
	{
		if ($this->action == null) {
			$this->action = 'updated';
		}

		if (!isset($this->title)) {
			$this->title = trans("alert::messages.$this->entity.$this->action.title", $this->langParameters);
		}

		if (!isset($this->description)) {
			$this->description = trans("alert::messages.$this->entity.$this->action.description", $this->langParameters);
		}
	}

	protected function setDefaultAlertMessageIfNotSupplied(): void
	{
		if (!isset($this->title)) {
			$this->title = 'Alert Message';
		}

		if (!isset($this->description)) {
			$this->description = null;
		}
	}

	public function flash(): void
	{
		if ($this->model != null) {
			$this->customizeAlertMessageForModel();
		} else if ($this->entity != null) {
			$this->customizeAlertMessageForEntity();
		} else {
			$this->setDefaultAlertMessageIfNotSupplied();
		}

		Session::flash('alert', array('title' => $this->getTitle(), 'description' => $this->getDescription(), 'type' => $this->getType(), 'action' => $this->getAction()));
	}
}