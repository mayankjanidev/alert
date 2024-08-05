<?php

namespace Mayank\Alert\Tests\Models;

use Mayank\Alert\Tests\Models\Post;

trait SampleModelInstances
{
    // Sample model instances which will be used to test Alert::model()
    public function getCreatedModel(): Post
    {
        $post = new Post;
        $post->wasRecentlyCreated = true;
        $post->exists = true;

        return $post;
    }

    public function getUpdatedModel(): Post
    {
        $post = new Post;
        $post->exists = true;

        return $post;
    }

    public function getDeletedModel(): Post
    {
        $post = new Post;
        $post->exists = false;

        return $post;
    }

    public function getModelWithAttributes(): Post
    {
        $post = new Post;
        $post->title = 'Test Model Title';

        return $post;
    }
}
