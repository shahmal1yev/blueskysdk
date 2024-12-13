<?php

require "vendor/autoload.php";

use \Atproto\Client as LexiconSmith;
use \Atproto\DataModel\Blob\Blob;
use \Atproto\Lexicons\App\Bsky\RichText\RichText;

$smith = new LexiconSmith();

$post = $smith->app()->bsky()->feed()->post()->forge()
    ->text("Hello, BlueSky!\n\n")
    ->text("This post was sent via ")
    ->link("https://blueskysdk.shahmal1yev.dev", "Bluesky PHP SDK")
    ->text(". It was built by ")
    ->mention("did:plc:bdkw6ic5ugy6ni4pqvljcpva", "shahmal1yev")
    ->text("\n\n")
    ->text(
        RichText::tag("php", "PHP"),
        " ",
        RichText::tag("bsky_sdk", "Bsky SDK")
    )
    ->embed(
        $smith->app()->bsky()->embed()->external()->forge(
            'https://blueskysdk.shahmal1yev.dev',
            'Bluesky PHP SDK',
            'Official documentation of the BlueSky PHP SDK'
        )
        ->thumb(Blob::viaArray([
            'size' => 1000,
            'ref' => [
                '$link' => '...'
            ],
            'mimeType' => 'image/png',
        ]))
    );

echo json_encode($post, JSON_PRETTY_PRINT);
