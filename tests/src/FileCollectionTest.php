<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class FileCollectionTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $collection = new FileCollection('example.txt');
        return $collection;
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded($collection)
    {
        $collection->set('new file contents');
        return $collection;
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataCanBeRetrieved($collection)
    {
        $collection->set('retrieving file contents');
        $this->assertEquals('retrieving file contents', $collection->get());
        $this->assertEquals('some content', $collection->get('example2.txt'));
        $this->assertEquals('some content', $collection->get('example3.txt'));
        $this->assertEquals('', $collection->get(rand().'.txt'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function inexistentIndexShouldReturnDefaultValue($collection)
    {
        $this->assertFalse($collection->get('undefined.txt'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function newCollectionShouldContainOnlyAItem()
    {
        $collection = new FileCollection('undefined.txt');
        $this->assertEquals(1, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function collectionWithItemsShouldReturnValidCount()
    {
        $collection = new FileCollection('example.txt');
        $file = $collection->setNew('example2.txt', 'some content');
        $file = $collection->setNew('example3.txt', 'some content');
        $file = $collection->setNew('example4.txt', 'some content', '+10');
        $this->assertEquals(4, $collection->count());
    }

    /**
     * @test
     * @depends collectionWithItemsShouldReturnValidCount
     */
    public function collectionCanBeCleaned()
    {
        $collection = new FileCollection('example.txt');
        $collection->set('example.txt', 'value');
        $this->assertEquals(1, $collection->count());

        $collection->clean();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function addedItemShouldExistInCollection()
    {
        $collection = new FileCollection('example.txt');
        $collection->set('value');
        $collection->setNew('newItem.txt', 'new item');

        $this->assertTrue($collection->has('newItem.txt'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function addItemWithExpirationTime()
    {
        $collection = new FileCollection('example.txt');
        $collection->set('some new content', '+10');

        $this->assertTrue($collection->has('example.txt'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function notExpiredItemCanBeAccessed()
    {
        $collection = new FileCollection('example.txt');
        $collection->set('some nice content', '+10');

        $this->assertTrue($collection->has('example.txt'));
    }

    /**
     * @test
     * @depends notExpiredItemCanBeAccessed
     */
    public function expiredItemCanNotBeAccessed()
    {
        $collection = new FileCollection('example.txt');
        $collection->set('some nice content', '-10');

        $this->assertFalse($collection->has('example.txt'));
    }

    /**
     * @depends notExpiredItemCanBeAccessed
     */
    public function cleanAllFiles()
    {
        $collection = new FileCollection('example.txt');
        $collection->set('some nice content', '+10');
        $collection->clean();

        $this->assertEquals(0, $collection->count());
    }
}
