<?php
use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Request;
use Cyantree\Grout\PhpUnit\GroutAppTestCase;

class SampleTestModuleTest extends GroutAppTestCase {

    public function test_indexRouteWorks()
    {
        $request = new Request('');

        $result = $this->app->processRequest($request);

        $this->assertEquals('Hello Grout!', $result->content);
    }
}