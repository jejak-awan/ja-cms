<?php

namespace Database\Factories;

use App\Modules\Media\Models\Media;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Media\Models\Media>
 */
class MediaFactory extends Factory
{
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isImage = fake()->boolean(70);
        $ext = $isImage ? 'jpg' : 'pdf';
        $filename = fake()->word() . '.' . $ext;
        
        return [
            'filename' => $filename,
            'original_filename' => $filename,
            'extension' => $ext,
            'path' => 'media/' . fake()->word() . '.' . $ext,
            'mime_type' => $isImage ? 'image/jpeg' : 'application/pdf',
            'size' => fake()->numberBetween(1024, 5000000),
            'user_id' => User::factory(),
            'alt_text' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'folder' => null,
        ];
    }

    public function image()
    {
        return $this->state(function (array $attributes) {
            return [
                'filename' => fake()->word() . '.jpg',
                'path' => 'media/' . fake()->word() . '.jpg',
                'mime_type' => 'image/jpeg',
            ];
        });
    }

    public function document()
    {
        return $this->state(function (array $attributes) {
            return [
                'filename' => fake()->word() . '.pdf',
                'path' => 'media/' . fake()->word() . '.pdf',
                'mime_type' => 'application/pdf',
            ];
        });
    }
}
