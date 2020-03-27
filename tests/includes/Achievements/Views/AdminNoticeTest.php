<?php

use PHPUnit\Framework\TestCase;
use NinjaForms\Achievements\Views\AdminNotice;

final class AdminNoticeTest extends TestCase
{
    public function testSnapshot()
    {
        $view = new AdminNotice( 'Hello, world!', [ 'test' ] );

        $this->assertEquals( '<div class=\'test notice\'><p>Hello, world!</p></div>', $view->render() );
    }
}