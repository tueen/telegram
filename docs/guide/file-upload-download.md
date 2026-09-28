# File Upload & Download

`tueen/telegram` makes file transfers seamless using the `InputFile` wrapper, streaming HTTP transports, and real-time upload/download progress callbacks.

---

## 1. File Uploads with `InputFile`

`InputFile` can wrap local files, streams, raw strings, or PSR-7 streams:

### From Local File Path
```php
use Tueen\Telegram\Types\Custom\InputFile;

$photo = InputFile::fromPath('/path/to/cat.jpg');

$bot->sendPhoto(
    chatId: 123456,
    photo: $photo,
    caption: 'My cute cat!'
);
```

### From Stream Resource
```php
$fp = fopen('https://example.com/audio.mp3', 'rb');
$audio = InputFile::fromResource($fp, 'audio.mp3');

$bot->sendAudio(
    chatId: 123456,
    audio: $audio
);
```

### From Raw In-Memory String
```php
$csv = InputFile::fromString("id,name\n1,Alice\n2,Bob", 'report.csv', 'text/csv');

$bot->sendDocument(
    chatId: 123456,
    document: $csv
);
```

---

## 2. Sending Media Groups (Albums)

To send multiple photos or videos as an album in a single message:

```php
$bot->sendMediaGroup(
    chatId: 123456,
    media: [
        [
            'type' => 'photo',
            'media' => 'https://example.com/photo1.jpg',
            'caption' => 'Holiday album'
        ],
        [
            'type' => 'photo',
            'media' => 'https://example.com/photo2.jpg',
        ]
    ]
);
```

---

## 3. Real-Time Upload Progress

You can track upload progress per-request:

```php
use Tueen\Telegram\Methods\SendDocument;
use Tueen\Telegram\Types\Custom\InputFile;

$bot->send(
    new SendDocument(
        chatId: 123456,
        document: InputFile::fromPath('/path/to/large_video.mp4')
    ),
    uploadProgress: function (int $bytesUploaded, int $totalBytes, float $percentage) {
        printf("Upload: %d / %d bytes (%.2f%%)\r", $bytesUploaded, $totalBytes, $percentage);
    }
);
```

---

## 4. Streaming File Downloads with Progress

Telegram allows downloading files up to 20MB (or up to 2GB when using a self-hosted Bot API server).

`$bot->downloadFile` downloads directly to a local file or stream without loading the entire payload into RAM:

```php
$bot->downloadFile(
    file: 'photos/file_0.jpg', // or File object / file_id
    destination: '/local/path/saved.jpg',
    progress: function (int $bytesDownloaded, int $totalBytes, float $percentage) {
        printf("Download: %.2f%%\r", $percentage);
    }
);
```
