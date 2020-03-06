<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FormsBuilderTest extends TestCase
{
    public function testReturnsKeyedArray(): void
    {
        $formsBuilder = new NinjaForms\Views\DataBuilder\FormsBuilder([[
            'id' => 1,
            'title' => 'Example Form',
        ]]);
        $this->assertEquals(
            $formsBuilder->get(),
            [
                1 => [
                    'formId' => 1,
                    'formTitle' => 'Example Form',
                ]
            ]
        );
    }
}