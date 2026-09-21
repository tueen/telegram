# File Upload & Download

## File Uploads with `InputFile`

`tueen/telegram` makes file uploads seamless using the `InputFile` class.

### From Local File Path
```php
use Tueen\Telegram\Types\Custom\InputFile;

$photo = InputFile::fromPath('/path/to/cat.jpg');
```

### From Stream Resource
```php
$fp = fopen('https://example.com/stream.mp3', 'rb');
$audio = InputFile::fromResource($fp, 'stream.mp3');
```

### From Raw String Data
```php
$csv = InputFile::fromString("id,name\n1,Alice", 'report.csv', 'text/csv');
```

## Real-Time Upload Progress

You can track upload progress per-request:

```php
use Tueen\Telegram\Methods\SendDocument;

$telegram->send(
    new SendDocument(
        chatId: 123456,
        document: InputFile::fromPath('/path/to/large_video.mp4')
    ),
    uploadProgress: function (int $bytesUploaded, int $totalBytes, float $percentage) {
        printf("Upload: %d / %d bytes (%.2f%%)\r", $bytesUploaded, $totalBytes, $percentage);
    }
);
```

## Streaming File Downloads with Progress

Download files directly to disk without exhausting memory:

```php
$telegram->downloadFile(
    file: 'photos/file_0.jpg', // or $message->photo[0]->fileId
    destination: '/local/path/saved.jpg',
    progress: function (int $bytesDownloaded, int $totalBytes, float $percentage) {
        printf("Download: %.2f%%\r", $percentage);
    }
);
```
