<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FieldsBuilderTest extends TestCase
{
    public function testReturnsLimitedArray(): void
    {
        $fields = [[
            'id' => 1,
            'type' => 'textbox',
            'label' => 'Example Field',
        ]];
        $fieldsBuilder = new NinjaForms\Views\DataBuilder\FieldsBuilder($fields);
        $this->assertEquals(
            $fieldsBuilder->get(),
            [
                [
                    'id' => 1,
                    'label' => 'Example Field',
                ]
            ]
        );
    }

    public function testReturnsOnlyUserInputFields(): void
    {
        $fields = [
            [
                'id' => 1,
                'type' => 'textbox',
                'label' => 'Example Field',
            ],
            [
                'id' => 2,
                'type' => 'submit',
                'label' => 'Submit',
            ],
            [
                'id' => 3,
                'type' => 'hr',
                'label' => '',
            ],
            [
                'id' => 4,
                'type' => 'html',
                'label' => '',
            ],
        ];
        $fieldsBuilder = new NinjaForms\Views\DataBuilder\FieldsBuilder($fields);
        $this->assertEquals(
            $fieldsBuilder->get(),
            [
                [
                    'id' => 1,
                    'label' => 'Example Field',
                ]
            ]
        );
    }
}