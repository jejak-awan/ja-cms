<?php

namespace App\Support;

use Illuminate\Support\Facades\App;

trait Translatable
{
    /**
     * Get translated attribute value based on current locale.
     *
     * @param string $key The base attribute key (e.g., 'title', 'content')
     * @param string|null $locale Optional locale, uses current if not provided
     * @return mixed
     */
    public function trans(string $key, ?string $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        $column = "{$key}_{$locale}";

        // Return translated value if exists and not empty
        if ($this->hasAttribute($column) && !empty($this->$column)) {
            return $this->$column;
        }

        // Fallback to default locale
        $fallbackLocale = config('locales.fallback', 'id');
        $fallbackColumn = "{$key}_{$fallbackLocale}";
        
        if ($this->hasAttribute($fallbackColumn) && !empty($this->$fallbackColumn)) {
            return $this->$fallbackColumn;
        }

        // Last resort: return null or empty string
        return null;
    }

    /**
     * Get all translations for a specific key.
     *
     * @param string $key The base attribute key
     * @return array
     */
    public function getTranslations(string $key): array
    {
        $translations = [];
        $supported = array_keys(config('locales.supported', []));

        foreach ($supported as $locale) {
            $column = "{$key}_{$locale}";
            if ($this->hasAttribute($column)) {
                $translations[$locale] = $this->$column;
            }
        }

        return $translations;
    }

    /**
     * Set translated attribute value for specific locale.
     *
     * @param string $key The base attribute key
     * @param mixed $value The value to set
     * @param string|null $locale Optional locale, uses current if not provided
     * @return self
     */
    public function setTranslation(string $key, $value, ?string $locale = null): self
    {
        $locale = $locale ?? App::getLocale();
        $column = "{$key}_{$locale}";

        if ($this->hasAttribute($column)) {
            $this->$column = $value;
        }

        return $this;
    }

    /**
     * Check if attribute column exists.
     *
     * @param string $key
     * @return bool
     */
    protected function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->attributes) || 
               in_array($key, $this->fillable);
    }

    /**
     * Magic getter for translated attributes.
     * Allows $model->title to automatically get trans('title')
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        // If value exists in attributes, return it
        if (!is_null($value)) {
            return $value;
        }

        // Check if this is a translatable field
        $translatableFields = $this->getTranslatableFields();
        
        if (in_array($key, $translatableFields)) {
            return $this->trans($key);
        }

        return $value;
    }

    /**
     * Get list of translatable fields for this model.
     *
     * @return array
     */
    protected function getTranslatableFields(): array
    {
        // Override this in your model or use config
        $modelClass = class_basename($this);
        $config = config("locales.translatable_models.{$modelClass}.fields", []);
        
        return $config ?: ($this->translatable ?? []);
    }
}
