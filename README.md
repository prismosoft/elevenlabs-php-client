# ElevenLabs PHP Client

A production-ready PHP client for the ElevenLabs API, providing access to text-to-speech, sound effects generation, music composition, and voice management.

## Features

- **Text-to-Speech**: Generate voice overs with timestamps
- **Sound Effects**: Create custom sound effects
- **Music Generation**: Compose music with detailed parameters
- **Voices API**: List, get, edit, delete, and find similar voices
- **Voice Library API**: List shared voices and add shared voices to your account
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
    ->withModelId('eleven_multilingual_v2')
    ->withOutputFormat('mp3_44100_128')
    ->enableLogging(true)
    ->withVoiceSettings([
        'stability' => 0.5,
        'similarity_boost' => 0.75,
        'style' => 0.2,
        'use_speaker_boost' => true,
        'speed' => 1.0,
    ])
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

// List voices
$voices = $client->voices()
    ->search('narrator')
    ->withPageSize(20)
    ->list();

// Get one voice
$voice = $client->voices()->get('21m00Tcm4TlvDq8ikWAM');

// Edit a voice
$updated = $client->voices()->update('21m00Tcm4TlvDq8ikWAM', 'My Voice', [
    'description' => 'Warm and expressive voice',
    'remove_background_noise' => true,
    'labels' => ['accent' => 'american', 'gender' => 'male'],
    'files' => [
        '/absolute/path/to/sample1.wav',
        '/absolute/path/to/sample2.wav',
    ],
]);

// Find similar voices using an audio sample
$similar = $client->voices()->findSimilarVoices([
    'audio_file' => '/absolute/path/to/sample.wav',
    'similarity_threshold' => 0.85,
    'top_k' => 10,
]);

// Delete a voice
$deleted = $client->voices()->delete('21m00Tcm4TlvDq8ikWAM');

// List shared voices from Voice Library
$sharedVoices = $client->voiceLibrary()
    ->withCategory('professional')
    ->withPageSize(10)
    ->list();

// Add a shared voice to your account
$addedVoice = $client->voiceLibrary()
    ->add('public-user-id', 'voice-id', 'John Smith');

// Alias of add()
$sharedVoice = $client->voiceLibrary()
    ->share('public-user-id', 'voice-id', 'John Smith');
```

## Implemented Endpoints

### Voices

- `GET /v2/voices` -> `$client->voices()->list()`
- `GET /v1/voices/{voice_id}` -> `$client->voices()->get($voiceId, $withSettings = true)`
- `POST /v1/voices/{voice_id}/edit` -> `$client->voices()->update($voiceId, $name, $options = [])`
- `DELETE /v1/voices/{voice_id}` -> `$client->voices()->delete($voiceId)`
- `POST /v1/similar-voices` -> `$client->voices()->findSimilarVoices($options = [])`

### Voice Library

- `GET /v1/shared-voices` -> `$client->voiceLibrary()->list()`
- `POST /v1/voices/add/{public_user_id}/{voice_id}` -> `$client->voiceLibrary()->add($publicUserId, $voiceId, $newName)`
- `POST /v1/voices/add/{public_user_id}/{voice_id}` -> `$client->voiceLibrary()->share($publicUserId, $voiceId, $newName)`

## Multipart File Options

For `voices()->update()` and `voices()->findSimilarVoices()`, file fields support:

- Absolute file path string (recommended)
- Stream/resource
- Explicit multipart part array:

```php
[
    'contents' => $binaryOrStream,
    'filename' => 'sample.wav',
    'headers' => ['Content-Type' => 'audio/wav'],
]
```

## Testing

```bash
./vendor/bin/pest
```

## License

MIT
