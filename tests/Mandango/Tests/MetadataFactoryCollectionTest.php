<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Tests;

use Mandango\MetadataFactory;
use Mandango\MetadataFactoryCollection;

use Mandango\Mandango;
use Mandango\Cache\ArrayCache;

class MetadataFactoryOne extends MetadataFactory
{
    protected $classes = array(
        'Model\Article'  => false,
        'Model\Author'   => false,
    );
}

class MetadataFactoryOneInfo
{
    public function getModelArticleClass()
    {
        return 'article';
    }

    public function getModelAuthorClass()
    {
        return 'author';
    }
}

class MetadataFactoryTwo extends MetadataFactory
{
    protected $classes = array(
        'Model\Comment'  => true,
        'Model\Category' => false,
        'Model\Source'  => true,
    );
}

class MetadataFactoryTwoInfo
{
    public function getModelCommentClass()
    {
        return 'comment';
    }
}


class MetadataFactoryCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->metadataFactory = new MetadataFactoryCollection(array(
            new MetadataFactoryOne(),
            new MetadataFactoryTwo(),
        ));
    }

    public function testMandangoUsage()
    {
        $mandango = new Mandango($this->metadataFactory, new ArrayCache(), function ($log) {});
        $this->assertSame($this->metadataFactory, $mandango->getMetadataFactory());
    }

    public function testGetClasses()
    {
        $this->assertSame(array(
            'Model\Article',
            'Model\Author',
            'Model\Comment',
            'Model\Category',
            'Model\Source',
        ), $this->metadataFactory->getClasses());
    }

    public function testGetDocumentClasses()
    {
        $this->assertSame(array(
            'Model\Article',
            'Model\Author',
            'Model\Category',
        ), $this->metadataFactory->getDocumentClasses());
    }

    public function testGetEmbeddedDocumentClasses()
    {
        $this->assertSame(array(
            'Model\Comment',
            'Model\Source',
        ), $this->metadataFactory->getEmbeddedDocumentClasses());
    }

    public function testHasClass()
    {
        $this->assertTrue($this->metadataFactory->hasClass('Model\Article'));
        $this->assertTrue($this->metadataFactory->hasClass('Model\Comment'));
        $this->assertFalse($this->metadataFactory->hasClass('Model\User'));
    }

    public function testIsDocumentClass()
    {
        $this->assertTrue($this->metadataFactory->isDocumentClass('Model\Article'));
        $this->assertTrue($this->metadataFactory->isDocumentClass('Model\Author'));
        $this->assertFalse($this->metadataFactory->isDocumentClass('Model\Comment'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testIsDocumentClassClassDoesNotExist()
    {
        $this->metadataFactory->isDocumentClass('Model\User');
    }

    public function testIsEmbeddedDocumentClass()
    {
        $this->assertTrue($this->metadataFactory->isEmbeddedDocumentClass('Model\Comment'));
        $this->assertTrue($this->metadataFactory->isEmbeddedDocumentClass('Model\Source'));
        $this->assertFalse($this->metadataFactory->isEmbeddedDocumentClass('Model\Article'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testIsEmbeddedDocumentClassClassDoesNotExist()
    {
        $this->metadataFactory->isEmbeddedDocumentClass('Model\User');
    }

    public function testGetClass()
    {
        $this->assertSame('article', $this->metadataFactory->getClass('Model\Article'));
        $this->assertSame('author', $this->metadataFactory->getClass('Model\Author'));
        $this->assertSame('comment', $this->metadataFactory->getClass('Model\Comment'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetClassInfoClassDoesNotExist()
    {
        $this->metadataFactory->getClass('Model\User');
    }
}
