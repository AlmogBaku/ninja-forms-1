<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

function is_serialized( $data ) {
    return @unserialize( $data );
}

final class SubmissionsBuilderTest extends TestCase
{
    public function testReturnsNormalizedKeys(): void
    {
        $submissions = [[
            '_field_1' => 'Example Form',
            'my_textbox' => 'Example Form',
        ]];
        $submissionsBuilder = new NinjaForms\Views\DataBuilder\SubmissionsBuilder($submissions);
        $this->assertEquals(
            $submissionsBuilder->get(),
            [
                [
                    '1' => 'Example Form',
                ]
            ]
        );
    }

    public function testReturnsFlattenedFileUploads(): void
    {
        $submissions = [[
            '_field_1' => serialize([
                '/uploads/image.png',
                '/uploads/image2.png',
            ]),
        ]];
        $submissionsBuilder = new NinjaForms\Views\DataBuilder\SubmissionsBuilder($submissions);
        $this->assertEquals(
            $submissionsBuilder->get(),
            [
                [
                    '1' => '/uploads/image.png, /uploads/image2.png',
                ]
            ]
        );
    }
}