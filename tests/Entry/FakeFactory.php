<?php

namespace Kristuff\Parselog\Tests\Entry;

class FakeFactory implements \Kristuff\Parselog\Core\LogEntryFactoryInterface
{
    public function create(array $data): \Kristuff\Parselog\Core\LogEntryInterface
    {
        $entry = new Fake();
        $entry->host = $data['host'];

        return $entry;
    }
}
