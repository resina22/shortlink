<?php

use App\Models\Link;

class LinkTest extends TestCase
{
    public function testCreateShortIsValid()
    {
        $link = new Link();
        $created = $link->create('http://www.google.com/', 'aca84e');

        $this->assertInstanceOf(Link::class, $created);
    }

    public function testCreateEmptyIsValid()
    {
        $link = new Link();
        $created = $link->create('http://www.google.com/');

        $this->assertInstanceOf(Link::class, $created);
    }

    public function testCreateIsInvalid()
    {
        $link = new Link();
        $created = $link->create('', 'aca84e');

        $this->assertFalse($created);
    }

    public function testFindIsvalid()
    {
        $short = Link::where('target','http://www.google.com/')->limit(1)->pluck('short')->first();
        $created = Link::where('short', $short)->get()->toArray();

        $this->assertCount(1, $created);
    }

    public function testValidDuplicateShort()
    {
        $link = new Link();
        $created = $link->create('http://www.google.com/', 'aca84e');

        $this->assertTrue(preg_match_all('/[.-]/', $created->short) == 2);
    }
}
