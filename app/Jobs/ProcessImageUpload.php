<?php

namespace App\Jobs;

use App\Models\PortfolioImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProcessImageUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $portfolioImage;
    protected $originalPath;

    /**
     * Create a new job instance.
     */
    public function __construct(PortfolioImage $portfolioImage, string $originalPath)
    {
        $this->portfolioImage = $portfolioImage;
        $this->originalPath = $originalPath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Load the original image
            $image = Image::make(Storage::path($this->originalPath));
            
            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();
            
            // Process different sizes
            $this->processLargeImage($image);
            $this->processMediumImage($image);
            $this->processSmallImage($image);
            $this->processThumbnail($image);
            
            // Update portfolio image record
            $this->portfolioImage->update([
                'processing_status' => 'completed',
                'original_width' => $originalWidth,
                'original_height' => $originalHeight,
                'file_size' => Storage::size($this->originalPath),
                'processed_at' => now(),
            ]);
            
            // Clean up temporary files if needed
            $this->cleanup();
            
        } catch (\Exception $e) {
            // Mark as failed
            $this->portfolioImage->update([
                'processing_status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            // Re-throw to mark job as failed
            throw $e;
        }
    }

    private function processLargeImage($image)
    {
        // Large image for full view (max 1920px width)
        $largeImage = clone $image;
        $largeImage->resize(1920, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        $largePath = $this->generatePath('large');
        $largeImage->save(Storage::path($largePath), 85);
        
        $this->portfolioImage->update(['large_path' => $largePath]);
    }

    private function processMediumImage($image)
    {
        // Medium image for gallery (max 800px width)
        $mediumImage = clone $image;
        $mediumImage->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        $mediumPath = $this->generatePath('medium');
        $mediumImage->save(Storage::path($mediumPath), 80);
        
        $this->portfolioImage->update(['medium_path' => $mediumPath]);
    }

    private function processSmallImage($image)
    {
        // Small image for mobile (max 400px width)
        $smallImage = clone $image;
        $smallImage->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        $smallPath = $this->generatePath('small');
        $smallImage->save(Storage::path($smallPath), 75);
        
        $this->portfolioImage->update(['small_path' => $smallPath]);
    }

    private function processThumbnail($image)
    {
        // Square thumbnail for previews (200x200)
        $thumbnail = clone $image;
        $thumbnail->fit(200, 200);
        
        $thumbnailPath = $this->generatePath('thumb');
        $thumbnail->save(Storage::path($thumbnailPath), 70);
        
        $this->portfolioImage->update(['thumbnail_path' => $thumbnailPath]);
    }

    private function generatePath($size)
    {
        $filename = pathinfo($this->originalPath, PATHINFO_FILENAME);
        $extension = pathinfo($this->originalPath, PATHINFO_EXTENSION);
        
        return "portfolios/processed/{$this->portfolioImage->portfolio_id}/{$filename}_{$size}.{$extension}";
    }

    private function cleanup()
    {
        // Remove temporary upload file if exists
        if (str_contains($this->originalPath, 'temp/')) {
            Storage::delete($this->originalPath);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        $this->portfolioImage->update([
            'processing_status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}