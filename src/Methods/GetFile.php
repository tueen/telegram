<?php

declare(strict_types=1);

namespace Tueen\Telegram\Methods;

use Tueen\Telegram\Methods\Method;
use Tueen\Telegram\Attributes\ApiMethod;
use Tueen\Telegram\Attributes\ReturnType;
use Tueen\Telegram\Attributes\Field;
use Tueen\Telegram\Types\File;

/**
 * Use this method to get basic information about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
 * Note: This function may not preserve the original file name and MIME type. You should save the file's MIME type and name (if available) when the File object is received.
 *
 * @link https://core.telegram.org/bots/api#getfile
 */
#[ApiMethod('getFile', 'POST')]
#[ReturnType(File::class, isArray: false)]
class GetFile extends Method
{
    /**
     * File identifier to get information about
     */
    #[Field('file_id', required: true)]
    public string $fileId;

    public function __construct(
        string $fileId
    )
    {
        if ($fileId !== null) $this->fileId = $fileId;
    }

    public static function make(
        string $fileId
    ): static
    {
        return new static($fileId);
    }
}
