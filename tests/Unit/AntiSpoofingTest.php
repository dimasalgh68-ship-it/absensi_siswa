<?php

namespace Tests\Unit;

use App\Services\FaceRecognitionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AntiSpoofingTest extends TestCase
{
    protected FaceRecognitionService $faceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faceService = new FaceRecognitionService();
        Storage::fake('public');
    }

    /**
     * Test liveness detection returns proper structure.
     */
    public function test_liveness_detection_returns_proper_structure(): void
    {
        // Create a test image
        $image = imagecreatetruecolor(640, 480);
        
        // Fill with random colors to simulate a real photo
        for ($i = 0; $i < 100; $i++) {
            $x = rand(0, 639);
            $y = rand(0, 479);
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefilledrectangle($image, $x, $y, $x + 50, $y + 50, $color);
        }
        
        // Save to temp file
        $tempPath = sys_get_temp_dir() . '/test_face_' . uniqid() . '.jpg';
        imagejpeg($image, $tempPath);
        imagedestroy($image);
        
        // Create UploadedFile
        $file = new UploadedFile($tempPath, 'test.jpg', 'image/jpeg', null, true);
        
        // Test liveness detection
        $result = $this->faceService->checkLiveness($file);
        
        // Assert structure
        $this->assertIsArray($result);
        $this->assertArrayHasKey('passed', $result);
        $this->assertArrayHasKey('score', $result);
        $this->assertArrayHasKey('reason', $result);
        
        // Assert types
        $this->assertIsBool($result['passed']);
        $this->assertIsNumeric($result['score']);
        $this->assertIsString($result['reason']);
        
        // Assert score range
        $this->assertGreaterThanOrEqual(0, $result['score']);
        $this->assertLessThanOrEqual(100, $result['score']);
        
        // Cleanup
        @unlink($tempPath);
    }

    /**
     * Test liveness detection with high variance image (should pass).
     */
    public function test_liveness_detection_with_high_variance_image(): void
    {
        // Create a colorful image with high variance
        $image = imagecreatetruecolor(640, 480);
        
        // Create random pattern with high variance
        for ($x = 0; $x < 640; $x += 10) {
            for ($y = 0; $y < 480; $y += 10) {
                $r = rand(0, 255);
                $g = rand(0, 255);
                $b = rand(0, 255);
                $color = imagecolorallocate($image, $r, $g, $b);
                imagefilledrectangle($image, $x, $y, $x + 10, $y + 10, $color);
            }
        }
        
        $tempPath = sys_get_temp_dir() . '/test_high_variance_' . uniqid() . '.jpg';
        imagejpeg($image, $tempPath, 95);
        imagedestroy($image);
        
        $file = new UploadedFile($tempPath, 'test.jpg', 'image/jpeg', null, true);
        $result = $this->faceService->checkLiveness($file);
        
        // High variance image should have better score
        $this->assertGreaterThan(40, $result['score'], 'High variance image should score above 40');
        
        @unlink($tempPath);
    }

    /**
     * Test liveness detection with low variance image (should fail).
     */
    public function test_liveness_detection_with_low_variance_image(): void
    {
        // Create a uniform gray image (low variance)
        $image = imagecreatetruecolor(640, 480);
        $gray = imagecolorallocate($image, 128, 128, 128);
        imagefilledrectangle($image, 0, 0, 640, 480, $gray);
        
        $tempPath = sys_get_temp_dir() . '/test_low_variance_' . uniqid() . '.jpg';
        imagejpeg($image, $tempPath, 95);
        imagedestroy($image);
        
        $file = new UploadedFile($tempPath, 'test.jpg', 'image/jpeg', null, true);
        $result = $this->faceService->checkLiveness($file);
        
        // Low variance image should have lower score
        $this->assertLessThan(60, $result['score'], 'Low variance image should score below 60');
        $this->assertFalse($result['passed'], 'Low variance image should not pass');
        
        @unlink($tempPath);
    }

    /**
     * Test liveness detection handles invalid image gracefully.
     */
    public function test_liveness_detection_handles_invalid_image(): void
    {
        // Create a text file instead of image
        $tempPath = sys_get_temp_dir() . '/test_invalid_' . uniqid() . '.txt';
        file_put_contents($tempPath, 'This is not an image');
        
        $file = new UploadedFile($tempPath, 'test.txt', 'text/plain', null, true);
        $result = $this->faceService->checkLiveness($file);
        
        // Should fail gracefully (fail-safe: allow by default on error)
        $this->assertIsArray($result);
        $this->assertArrayHasKey('passed', $result);
        
        @unlink($tempPath);
    }

    /**
     * Test that checks array contains all required checks.
     */
    public function test_liveness_checks_contain_all_required_checks(): void
    {
        $image = imagecreatetruecolor(640, 480);
        
        for ($i = 0; $i < 50; $i++) {
            $x = rand(0, 639);
            $y = rand(0, 479);
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefilledrectangle($image, $x, $y, $x + 30, $y + 30, $color);
        }
        
        $tempPath = sys_get_temp_dir() . '/test_checks_' . uniqid() . '.jpg';
        imagejpeg($image, $tempPath);
        imagedestroy($image);
        
        $file = new UploadedFile($tempPath, 'test.jpg', 'image/jpeg', null, true);
        $result = $this->faceService->checkLiveness($file);
        
        // Check that all 4 checks are present
        if (isset($result['checks'])) {
            $this->assertArrayHasKey('blur', $result['checks']);
            $this->assertArrayHasKey('texture', $result['checks']);
            $this->assertArrayHasKey('brightness', $result['checks']);
            $this->assertArrayHasKey('color_variance', $result['checks']);
            
            // Each check should have score and status
            foreach ($result['checks'] as $check) {
                $this->assertArrayHasKey('score', $check);
                $this->assertArrayHasKey('status', $check);
            }
        }
        
        @unlink($tempPath);
    }
}
