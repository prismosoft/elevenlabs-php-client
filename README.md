# ElevenLabs PHP Client

A production-ready PHP client for the ElevenLabs API, providing access to text-to-speech, sound effects generation, and music composition.

## Features

- **Text-to-Speech**: Generate voice overs with timestamps
- **Sound Effects**: Create custom sound effects
- **Music Generation**: Compose music with detailed parameters
- **Voice Management**: List and search available voices
- **Laravel Integration**: Seamless integration with Laravel applications

## Installation

```bash
composer require prismosoft/elevenlabs-php-client
```

## Usage

```php
use ElevenLabs\ElevenLabsClient;

$client = ElevenLabsClient::factory()
    ->withApiKey('your-api-key')
    ->make();

// Generate text-to-speech
$response = $client->textToSpeech()
    ->withVoiceId('voice-id')
    ->withText('Hello world')
    ->withTimestamps()
    ->generate();

// Generate sound effects
$sound = $client->soundEffects()
    ->withPrompt('A door creaking open')
    ->generate();

// Generate music
$music = $client->music()
    ->withPrompt('Upbeat electronic track')
    ->withDuration(30)
    ->generate();
```

## Testing

```bash
./vendor/bin/pest
```

## License

MIT
